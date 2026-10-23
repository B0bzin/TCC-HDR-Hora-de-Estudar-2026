<?php
/**
 * Fase 1 — Perfis de filhos geridos pelo responsável.
 * O pai/responsável cria os perfis dos filhos dentro da própria conta
 * (armazenados em user meta 'hde_filhos'). É a base para o calendário,
 * o histórico e o controle de conteúdo por filho (próximas fases).
 *
 * @package Hora de Estudar
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/** Cria as páginas do módulo de filhos (controlado por versão). */
function hde_criar_paginas_filhos() {
    if ( (int) get_option( 'hde_paginas_filhos_v' ) >= 2 ) return;
    $paginas = array(
        'meus-filhos'      => array( 'Meus Filhos',       'template-filhos.php' ),
        'quem-esta-usando' => array( 'Quem está usando?', 'template-quem-usando.php' ),
    );
    foreach ( $paginas as $slug => $d ) {
        if ( ! get_page_by_path( $slug ) ) {
            $id = wp_insert_post( array(
                'post_title'  => $d[0], 'post_name' => $slug,
                'post_status' => 'publish', 'post_type' => 'page', 'post_content' => '',
            ) );
            if ( $id && ! is_wp_error( $id ) ) update_post_meta( $id, '_wp_page_template', $d[1] );
        }
    }
    update_option( 'hde_paginas_filhos_v', 2 );
}
add_action( 'init', 'hde_criar_paginas_filhos', 30 );

/** Lista os filhos do usuário. */
function hde_get_filhos( $user_id ) {
    $f = get_user_meta( $user_id, 'hde_filhos', true );
    return is_array( $f ) ? $f : array();
}

/** Retorna um filho específico pelo id (ou null). */
function hde_get_filho( $user_id, $fid ) {
    foreach ( hde_get_filhos( $user_id ) as $f ) {
        if ( isset( $f['id'] ) && $f['id'] === $fid ) return $f;
    }
    return null;
}

/** Adiciona um perfil de filho. */
function hde_add_filho() {
    if ( ! is_user_logged_in() ||
         ! isset( $_POST['hde_filho_nonce'] ) ||
         ! wp_verify_nonce( $_POST['hde_filho_nonce'], 'hde_filho' ) ) {
        wp_safe_redirect( home_url( '/meus-filhos/?filho=erro' ) );
        exit;
    }
    $uid     = get_current_user_id();
    $destino = epa_url_pagina( 'meus-filhos' );
    $nome    = sanitize_text_field( $_POST['hde_filho_nome'] ?? '' );
    $idade   = intval( $_POST['hde_filho_idade'] ?? 0 );
    $email   = sanitize_email( $_POST['hde_filho_email'] ?? '' );
    $serie   = sanitize_text_field( $_POST['hde_filho_serie'] ?? '' );
    $escola  = sanitize_text_field( $_POST['hde_filho_escola'] ?? '' );
    $obs     = sanitize_textarea_field( $_POST['hde_filho_obs'] ?? '' );

    if ( ! $nome ) {
        wp_safe_redirect( add_query_arg( 'filho', 'erro', $destino ) . '#lista' );
        exit;
    }
    $filhos   = hde_get_filhos( $uid );
    $filhos[] = array(
        'id'     => uniqid( 'f' ),
        'nome'   => $nome,
        'idade'  => ( $idade > 0 && $idade < 19 ) ? $idade : '',
        'email'  => $email,
        'serie'  => $serie,
        'escola' => $escola,
        'obs'    => $obs,
    );
    update_user_meta( $uid, 'hde_filhos', $filhos );
    wp_safe_redirect( add_query_arg( 'filho', 'ok', $destino ) . '#lista' );
    exit;
}
add_action( 'admin_post_hde_add_filho', 'hde_add_filho' );

/** Remove um perfil de filho. */
function hde_del_filho() {
    if ( ! is_user_logged_in() ||
         ! isset( $_GET['fid'], $_GET['_wpnonce'] ) ||
         ! wp_verify_nonce( $_GET['_wpnonce'], 'hde_del_filho' ) ) {
        wp_safe_redirect( epa_url_pagina( 'meus-filhos' ) );
        exit;
    }
    $uid    = get_current_user_id();
    $fid    = sanitize_text_field( $_GET['fid'] );
    $filhos = array_values( array_filter( hde_get_filhos( $uid ), function ( $f ) use ( $fid ) {
        return ! isset( $f['id'] ) || $f['id'] !== $fid;
    } ) );
    update_user_meta( $uid, 'hde_filhos', $filhos );
    wp_safe_redirect( add_query_arg( 'filho', 'removido', epa_url_pagina( 'meus-filhos' ) ) . '#lista' );
    exit;
}
add_action( 'admin_post_hde_del_filho', 'hde_del_filho' );

/* =======================================================================
 * PERFIL ATIVO — "Quem está usando?" (os filhos usam o site SEM login;
 * quem loga é o responsável, que depois escolhe qual perfil está em uso).
 * ==================================================================== */

/** Retorna o perfil ativo (responsável ou um filho), lido do cookie. */
function hde_perfil_ativo() {
    if ( ! is_user_logged_in() ) return null;
    $uid = get_current_user_id();
    $fid = isset( $_COOKIE['hde_perfil_ativo'] ) ? sanitize_text_field( $_COOKIE['hde_perfil_ativo'] ) : '';
    if ( $fid && $fid !== 'responsavel' ) {
        $f = hde_get_filho( $uid, $fid );
        if ( $f ) {
            return array( 'tipo' => 'filho', 'id' => $fid, 'nome' => $f['nome'], 'idade' => $f['idade'] );
        }
    }
    $u = wp_get_current_user();
    return array( 'tipo' => 'responsavel', 'id' => 'responsavel', 'nome' => $u->display_name, 'idade' => '' );
}

/** Define o perfil ativo (grava um cookie) e volta para a home. */
function hde_set_perfil() {
    if ( ! is_user_logged_in() ) { wp_safe_redirect( home_url( '/' ) ); exit; }
    $fid = isset( $_GET['fid'] ) ? sanitize_text_field( $_GET['fid'] ) : 'responsavel';
    if ( $fid !== 'responsavel' && ! hde_get_filho( get_current_user_id(), $fid ) ) {
        $fid = 'responsavel';
    }
    setcookie( 'hde_perfil_ativo', $fid, time() + 30 * DAY_IN_SECONDS, '/' );
    $_COOKIE['hde_perfil_ativo'] = $fid; // reflete já nesta requisição
    wp_safe_redirect( home_url( '/' ) );
    exit;
}
add_action( 'admin_post_hde_set_perfil', 'hde_set_perfil' );

/* =======================================================================
 * FOTO DO FILHO + AVATAR DO PERFIL ATIVO + EDIÇÃO PELO PRÓPRIO FILHO
 * ==================================================================== */

/** URL da foto de um filho (anexo enviado) ou o avatar padrão (pinguim). */
function hde_filho_foto_url( $f ) {
    if ( is_array( $f ) && ! empty( $f['foto'] ) ) {
        $img = wp_get_attachment_image_url( (int) $f['foto'], 'thumbnail' );
        if ( $img ) return $img;
    }
    return EPA_URI . '/assets/img/penguin.png';
}

/** Avatar do perfil ATIVO: foto do filho em uso, ou a do responsável. */
function hde_perfil_avatar_url() {
    if ( ! is_user_logged_in() ) return EPA_URI . '/assets/img/penguin.png';
    $perfil = hde_perfil_ativo();
    if ( $perfil && $perfil['tipo'] === 'filho' ) {
        $f = hde_get_filho( get_current_user_id(), $perfil['id'] );
        if ( $f ) return hde_filho_foto_url( $f );
    }
    return function_exists( 'epa_avatar_url' ) ? epa_avatar_url( get_current_user_id() ) : ( EPA_URI . '/assets/img/penguin.png' );
}

/** O filho (perfil ativo) edita o próprio perfil: nome, idade, série, escola e foto. */
function hde_editar_filho() {
    if ( ! is_user_logged_in() ||
         ! isset( $_POST['hde_edit_filho_nonce'] ) ||
         ! wp_verify_nonce( $_POST['hde_edit_filho_nonce'], 'hde_edit_filho' ) ) {
        wp_safe_redirect( epa_url_pagina( 'meu-perfil' ) ); exit;
    }
    $uid    = get_current_user_id();
    $fid    = sanitize_text_field( $_POST['fid'] ?? '' );
    $filhos = hde_get_filhos( $uid );
    $achou  = false;
    foreach ( $filhos as &$f ) {
        if ( isset( $f['id'] ) && $f['id'] === $fid ) {
            $achou = true;
            $nome  = sanitize_text_field( $_POST['nome'] ?? '' );
            if ( $nome ) $f['nome'] = $nome;
            $idade = intval( $_POST['idade'] ?? 0 );
            $f['idade']  = ( $idade > 0 && $idade < 19 ) ? $idade : '';
            $f['serie']  = sanitize_text_field( $_POST['serie'] ?? '' );
            $f['escola'] = sanitize_text_field( $_POST['escola'] ?? '' );
            if ( ! empty( $_FILES['foto']['name'] ) ) {
                require_once ABSPATH . 'wp-admin/includes/file.php';
                require_once ABSPATH . 'wp-admin/includes/media.php';
                require_once ABSPATH . 'wp-admin/includes/image.php';
                $tipos = array( 'jpg|jpeg' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif', 'webp' => 'image/webp' );
                $att = media_handle_upload( 'foto', 0, array(), array( 'test_form' => false, 'mimes' => $tipos ) );
                if ( ! is_wp_error( $att ) ) $f['foto'] = $att;
            }
            break;
        }
    }
    unset( $f );
    if ( $achou ) update_user_meta( $uid, 'hde_filhos', $filhos );
    wp_safe_redirect( add_query_arg( 'perfil', $achou ? 'ok' : 'erro', epa_url_pagina( 'meu-perfil' ) ) );
    exit;
}
add_action( 'admin_post_hde_editar_filho', 'hde_editar_filho' );

/** Após o login, se o responsável tiver filhos, leva à tela "Quem está usando?". */
function hde_redirect_pos_login( $redirect_to, $requested, $user ) {
    if ( is_a( $user, 'WP_User' ) && ! user_can( $user, 'manage_options' ) ) {
        if ( count( hde_get_filhos( $user->ID ) ) > 0 ) {
            return epa_url_pagina( 'quem-esta-usando' );
        }
    }
    return $redirect_to;
}
add_filter( 'login_redirect', 'hde_redirect_pos_login', 10, 3 );

/**
 * Força a escolha de perfil após QUALQUER login (inclusive o Google): se o
 * responsável tem filhos e ainda não escolheu um perfil nesta sessão, é
 * levado à tela "Quem está usando?".
 */
function hde_forcar_selecao_perfil() {
    if ( is_admin() || ! is_user_logged_in() ) return;
    $uid = get_current_user_id();
    if ( user_can( $uid, 'manage_options' ) ) return;      // admin navega livre
    if ( count( hde_get_filhos( $uid ) ) === 0 ) return;    // sem filhos, sem seleção
    if ( ! empty( $_COOKIE['hde_perfil_ativo'] ) ) return;  // já escolheu
    if ( is_page( 'quem-esta-usando' ) ) return;            // já está na tela
    wp_safe_redirect( epa_url_pagina( 'quem-esta-usando' ) );
    exit;
}
add_action( 'template_redirect', 'hde_forcar_selecao_perfil' );

/* =======================================================================
 * HISTÓRICO por filho (o que cada filho visualizou).
 * ==================================================================== */

/** Retorna o histórico de um filho (lista de {post, data}, mais recente primeiro). */
function hde_get_historico( $user_id, $fid ) {
    $h = get_user_meta( $user_id, 'hde_historico', true );
    return ( is_array( $h ) && isset( $h[ $fid ] ) ) ? $h[ $fid ] : array();
}

/** Registra a visita de um filho a um conteúdo (guarda os últimos 50). */
function hde_add_historico( $user_id, $fid, $post_id ) {
    $h = get_user_meta( $user_id, 'hde_historico', true );
    if ( ! is_array( $h ) ) $h = array();
    if ( ! isset( $h[ $fid ] ) ) $h[ $fid ] = array();
    $h[ $fid ] = array_values( array_filter( $h[ $fid ], function ( $it ) use ( $post_id ) {
        return (int) $it['post'] !== (int) $post_id;
    } ) );
    array_unshift( $h[ $fid ], array( 'post' => (int) $post_id, 'data' => current_time( 'Y-m-d H:i' ) ) );
    $h[ $fid ] = array_slice( $h[ $fid ], 0, 50 );
    update_user_meta( $user_id, 'hde_historico', $h );
}

/** Registra automaticamente quando um filho (perfil ativo) abre um vídeo/artigo. */
function hde_registrar_visita() {
    if ( is_admin() || ! is_user_logged_in() ) return;
    if ( ! is_singular( array( 'epa_video', 'epa_artigo' ) ) ) return;
    $perfil = hde_perfil_ativo();
    if ( ! $perfil || $perfil['tipo'] !== 'filho' ) return;
    $post = get_queried_object();
    if ( $post && isset( $post->ID ) ) {
        hde_add_historico( get_current_user_id(), $perfil['id'], $post->ID );
    }
}
add_action( 'wp', 'hde_registrar_visita' );
