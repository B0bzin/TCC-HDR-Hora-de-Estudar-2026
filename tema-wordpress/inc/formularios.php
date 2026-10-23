<?php
/**
 * Formulários funcionais do Hora de Estudar:
 *  - Contato "Fale com a gente"  → salva em Mensagens + tenta enviar e-mail
 *  - Cadastro de usuário          → cria conta no WordPress e faz login
 *
 * Usa admin-post.php (padrão seguro do WordPress, com nonce).
 *
 * @package Hora de Estudar
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/* =======================================================================
 * FORMULÁRIO DE CONTATO
 * ==================================================================== */
function epa_processar_contato() {
    // Verifica o nonce (proteção CSRF)
    if ( ! isset( $_POST['epa_contato_nonce'] ) || ! wp_verify_nonce( $_POST['epa_contato_nonce'], 'epa_contato' ) ) {
        wp_safe_redirect( home_url( '/?contato=erro' ) );
        exit;
    }

    $nome     = sanitize_text_field( $_POST['epa_nome'] ?? '' );
    $email    = sanitize_email( $_POST['epa_email'] ?? '' );
    $crianca  = sanitize_text_field( $_POST['epa_crianca'] ?? '' );
    $idade    = sanitize_text_field( $_POST['epa_idade'] ?? '' );
    $mensagem = sanitize_textarea_field( $_POST['epa_mensagem'] ?? '' );

    $retorno = wp_get_referer() ? wp_get_referer() : home_url( '/' );

    // Validação mínima
    if ( empty( $nome ) || ! is_email( $email ) || empty( $mensagem ) ) {
        wp_safe_redirect( add_query_arg( 'contato', 'erro', $retorno ) . '#contato' );
        exit;
    }

    // 1) Salva a mensagem no painel (Mensagens de contato)
    $post_id = wp_insert_post( array(
        'post_type'    => 'epa_mensagem',
        'post_status'  => 'publish',
        'post_title'   => sprintf( 'Mensagem de %s', $nome ),
        'post_content' => $mensagem,
    ) );

    if ( $post_id && ! is_wp_error( $post_id ) ) {
        update_post_meta( $post_id, '_epa_email', $email );
        update_post_meta( $post_id, '_epa_crianca', $crianca );
        update_post_meta( $post_id, '_epa_idade', $idade );
    }

    // 2) Tenta enviar e-mail para o administrador (pode não sair em ambiente local sem SMTP)
    $para    = get_option( 'admin_email' );
    $assunto = 'Hora de Estudar - Nova mensagem de contato de ' . $nome;
    $corpo   = "Nome do responsável: $nome\n";
    $corpo  .= "E-mail: $email\n";
    $corpo  .= "Nome da criança: $crianca\n";
    $corpo  .= "Idade da criança: $idade\n\n";
    $corpo  .= "Mensagem:\n$mensagem\n";
    $headers = array( 'Reply-To: ' . $nome . ' <' . $email . '>' );
    @wp_mail( $para, $assunto, $corpo, $headers );

    wp_safe_redirect( add_query_arg( 'contato', 'ok', $retorno ) . '#contato' );
    exit;
}
add_action( 'admin_post_nopriv_epa_contato', 'epa_processar_contato' );
add_action( 'admin_post_epa_contato', 'epa_processar_contato' );

/* =======================================================================
 * FORMULÁRIO DE CADASTRO (Registrar)
 * ==================================================================== */
function epa_processar_cadastro() {
    if ( ! isset( $_POST['epa_cadastro_nonce'] ) || ! wp_verify_nonce( $_POST['epa_cadastro_nonce'], 'epa_cadastro' ) ) {
        wp_safe_redirect( home_url( '/registrar/?cadastro=erro' ) );
        exit;
    }

    $pagina  = get_permalink( get_page_by_path( 'registrar' ) );
    if ( ! $pagina ) $pagina = home_url( '/registrar/' );

    $nome      = sanitize_text_field( $_POST['epa_nome'] ?? '' );
    $sobrenome = sanitize_text_field( $_POST['epa_sobrenome'] ?? '' );
    $telefone  = sanitize_text_field( $_POST['epa_telefone'] ?? '' );
    $email     = sanitize_email( $_POST['epa_email'] ?? '' );
    $email2    = sanitize_email( $_POST['epa_email2'] ?? '' );
    $senha     = $_POST['epa_senha'] ?? '';
    $senha2    = $_POST['epa_senha2'] ?? '';
    $manter    = isset( $_POST['epa_manter'] );

    // Validações
    $erros = array();
    if ( empty( $nome ) )                 $erros[] = 'nome';
    if ( empty( $sobrenome ) )            $erros[] = 'sobrenome';
    if ( empty( $telefone ) )             $erros[] = 'telefone';
    if ( ! is_email( $email ) )           $erros[] = 'email';
    if ( $email !== $email2 )             $erros[] = 'email_diferente';
    if ( email_exists( $email ) )         $erros[] = 'email_existe';
    if ( strlen( $senha ) < 8 )           $erros[] = 'senha_curta';
    if ( $senha !== $senha2 )             $erros[] = 'senha_diferente';

    if ( ! empty( $erros ) ) {
        wp_safe_redirect( add_query_arg( 'cadastro', implode( ',', $erros ), $pagina ) );
        exit;
    }

    // Gera um login único a partir do e-mail
    $login_base = sanitize_user( current( explode( '@', $email ) ), true );
    if ( empty( $login_base ) ) $login_base = 'usuario';
    $login = $login_base;
    $i = 1;
    while ( username_exists( $login ) ) { $login = $login_base . $i; $i++; }

    $user_id = wp_insert_user( array(
        'user_login'   => $login,
        'user_email'   => $email,
        'user_pass'    => $senha,
        'display_name' => trim( $nome . ' ' . $sobrenome ),
        'first_name'   => $nome,
        'last_name'    => $sobrenome,
        'role'         => 'subscriber',
    ) );

    if ( is_wp_error( $user_id ) ) {
        wp_safe_redirect( add_query_arg( 'cadastro', 'erro', $pagina ) );
        exit;
    }

    // Salva o telefone
    update_user_meta( $user_id, 'hde_telefone', $telefone );

    // Faz login automático (respeitando a opção "Manter login")
    wp_set_current_user( $user_id );
    wp_set_auth_cookie( $user_id, $manter );

    wp_safe_redirect( add_query_arg( 'cadastro', 'ok', home_url( '/' ) ) );
    exit;
}
add_action( 'admin_post_nopriv_epa_cadastro', 'epa_processar_cadastro' );
add_action( 'admin_post_epa_cadastro', 'epa_processar_cadastro' );

/**
 * Mensagens de retorno do cadastro (usado no template Registrar).
 */
function epa_mensagem_cadastro( $codigo ) {
    $mapa = array(
        'nome'            => 'Informe o seu nome.',
        'sobrenome'       => 'Informe o seu sobrenome.',
        'telefone'        => 'Informe um número de telefone.',
        'email'           => 'Informe um e-mail válido.',
        'email_diferente' => 'Os e-mails não coincidem.',
        'email_existe'    => 'Este e-mail já está cadastrado. Tente fazer login.',
        'senha_curta'     => 'A senha deve ter pelo menos 8 caracteres.',
        'senha_diferente' => 'As senhas não coincidem.',
        'erro'            => 'Não foi possível concluir o cadastro. Tente novamente.',
    );
    $saida = array();
    foreach ( explode( ',', $codigo ) as $c ) {
        if ( isset( $mapa[ $c ] ) ) $saida[] = $mapa[ $c ];
    }
    return implode( ' ', $saida );
}
