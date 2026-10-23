<?php
/**
 * Painel de Gestão de Usuários e Comentários (acesso apenas para administradores).
 * Permite buscar, visualizar, editar e excluir contas de usuários (e os perfis
 * dos filhos), além de moderar (editar/excluir) os comentários dos vídeos.
 *
 * @package Hora de Estudar
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/** Cria a página de gestão uma única vez. */
function hde_criar_pagina_gestao() {
    if ( (int) get_option( 'hde_pagina_gestao_v' ) >= 1 ) return;
    if ( ! get_page_by_path( 'gestao-usuarios' ) ) {
        $id = wp_insert_post( array(
            'post_title'  => 'Gestão de Usuários', 'post_name' => 'gestao-usuarios',
            'post_status' => 'publish', 'post_type' => 'page', 'post_content' => '',
        ) );
        if ( $id && ! is_wp_error( $id ) ) update_post_meta( $id, '_wp_page_template', 'template-admin-usuarios.php' );
    }
    update_option( 'hde_pagina_gestao_v', 1 );
}
add_action( 'init', 'hde_criar_pagina_gestao', 31 );

/** Só administradores podem usar o painel. */
function hde_admin_pode() {
    return is_user_logged_in() && current_user_can( 'manage_options' );
}

/** URL da página de gestão. */
function hde_url_gestao() {
    $u = function_exists( 'epa_url_pagina' ) ? epa_url_pagina( 'gestao-usuarios' ) : '';
    return $u ? $u : home_url( '/gestao-usuarios/' );
}

/* =======================================================================
 * EDITAR USUÁRIO (dados da conta + observações dos filhos)
 * ==================================================================== */
function hde_admin_salvar_usuario() {
    if ( ! hde_admin_pode() ||
         ! isset( $_POST['hde_admin_nonce'] ) ||
         ! wp_verify_nonce( $_POST['hde_admin_nonce'], 'hde_admin_user' ) ) {
        wp_safe_redirect( hde_url_gestao() ); exit;
    }
    $uid  = intval( $_POST['uid'] ?? 0 );
    $alvo = $uid ? get_userdata( $uid ) : null;
    if ( ! $alvo ) { wp_safe_redirect( add_query_arg( 'msg', 'erro', hde_url_gestao() ) ); exit; }

    $dados   = array( 'ID' => $uid );
    $nome    = sanitize_text_field( $_POST['nome'] ?? '' );
    $apelido = sanitize_text_field( $_POST['apelido'] ?? '' );
    $email   = sanitize_email( $_POST['email'] ?? '' );
    $bio     = sanitize_textarea_field( $_POST['bio'] ?? '' );

    if ( $nome !== '' )    $dados['first_name'] = $nome;
    if ( $apelido !== '' ) { $dados['nickname'] = $apelido; $dados['display_name'] = $apelido; }
    if ( $email !== '' && is_email( $email ) ) $dados['user_email'] = $email;
    $dados['description'] = $bio;
    wp_update_user( $dados );

    // Observações dos filhos
    if ( isset( $_POST['obs'] ) && is_array( $_POST['obs'] ) && function_exists( 'hde_get_filhos' ) ) {
        $filhos = hde_get_filhos( $uid );
        foreach ( $filhos as &$f ) {
            if ( isset( $f['id'] ) && isset( $_POST['obs'][ $f['id'] ] ) ) {
                $f['obs'] = sanitize_textarea_field( $_POST['obs'][ $f['id'] ] );
            }
        }
        unset( $f );
        update_user_meta( $uid, 'hde_filhos', $filhos );
    }
    wp_safe_redirect( add_query_arg( array( 'ver' => $uid, 'msg' => 'salvo' ), hde_url_gestao() ) );
    exit;
}
add_action( 'admin_post_hde_admin_user', 'hde_admin_salvar_usuario' );

/* =======================================================================
 * EXCLUIR USUÁRIO
 * ==================================================================== */
function hde_admin_excluir_usuario() {
    if ( ! hde_admin_pode() ||
         ! isset( $_GET['uid'], $_GET['_wpnonce'] ) ||
         ! wp_verify_nonce( $_GET['_wpnonce'], 'hde_del_user' ) ) {
        wp_safe_redirect( hde_url_gestao() ); exit;
    }
    $uid = intval( $_GET['uid'] );
    $msg = 'erro';
    // Não permite excluir a si mesmo nem outros administradores
    if ( $uid && $uid !== get_current_user_id() && ! user_can( $uid, 'manage_options' ) ) {
        require_once ABSPATH . 'wp-admin/includes/user.php';
        wp_delete_user( $uid, get_current_user_id() ); // reatribui o conteúdo ao admin atual
        $msg = 'excluido';
    }
    wp_safe_redirect( add_query_arg( 'msg', $msg, hde_url_gestao() ) );
    exit;
}
add_action( 'admin_post_hde_del_user', 'hde_admin_excluir_usuario' );

/* =======================================================================
 * COMENTÁRIOS DOS VÍDEOS (editar / excluir)
 * ==================================================================== */
function hde_admin_salvar_comentario() {
    if ( ! hde_admin_pode() ||
         ! isset( $_POST['hde_coment_nonce'] ) ||
         ! wp_verify_nonce( $_POST['hde_coment_nonce'], 'hde_admin_coment' ) ) {
        wp_safe_redirect( hde_url_gestao() ); exit;
    }
    $cid   = intval( $_POST['cid'] ?? 0 );
    $texto = sanitize_textarea_field( $_POST['conteudo'] ?? '' );
    if ( $cid ) {
        wp_update_comment( array( 'comment_ID' => $cid, 'comment_content' => $texto ) );
    }
    wp_safe_redirect( add_query_arg( array( 'aba' => 'comentarios', 'msg' => 'coment_salvo' ), hde_url_gestao() ) . '#comentarios' );
    exit;
}
add_action( 'admin_post_hde_admin_coment', 'hde_admin_salvar_comentario' );

function hde_admin_excluir_comentario() {
    if ( ! hde_admin_pode() ||
         ! isset( $_GET['cid'], $_GET['_wpnonce'] ) ||
         ! wp_verify_nonce( $_GET['_wpnonce'], 'hde_del_coment' ) ) {
        wp_safe_redirect( hde_url_gestao() ); exit;
    }
    $cid = intval( $_GET['cid'] );
    if ( $cid ) wp_delete_comment( $cid, true ); // remove definitivamente
    wp_safe_redirect( add_query_arg( array( 'aba' => 'comentarios', 'msg' => 'coment_excluido' ), hde_url_gestao() ) . '#comentarios' );
    exit;
}
add_action( 'admin_post_hde_del_coment', 'hde_admin_excluir_comentario' );
