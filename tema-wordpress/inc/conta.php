<?php
/**
 * Área "Minha Conta": perfil personalizável, calendário pessoal,
 * notificações e integração (link) com o Google Agenda.
 * @package Hora de Estudar
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/* =======================================================================
 * 1. TIPO DE CONTEÚDO: EVENTO (agenda pessoal do usuário)
 * ==================================================================== */
function epa_cpt_evento() {
    register_post_type( 'epa_evento', array(
        'labels' => array(
            'name'          => 'Eventos',
            'singular_name' => 'Evento',
            'menu_name'     => 'Eventos (agenda)',
            'all_items'     => 'Todos os eventos',
        ),
        'public'       => false,
        'show_ui'      => true,
        'menu_icon'    => 'dashicons-calendar-alt',
        'menu_position'=> 8,
        'supports'     => array( 'title', 'editor', 'author' ),
    ) );
}
add_action( 'init', 'epa_cpt_evento' );

/* =======================================================================
 * 2. PÁGINAS DA CONTA (criadas uma vez)
 * ==================================================================== */
function epa_criar_paginas_conta() {
    if ( get_option( 'epa_paginas_conta' ) ) return;
    $paginas = array(
        'meu-perfil'     => array( 'Meu Perfil',     'template-perfil.php' ),
        'meu-calendario' => array( 'Meu Calendário', 'template-calendario.php' ),
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
    update_option( 'epa_paginas_conta', 1 );
}
add_action( 'init', 'epa_criar_paginas_conta', 30 );

/* =======================================================================
 * 3. AVATAR PERSONALIZADO (foto de perfil enviada pelo usuário)
 * ==================================================================== */
/** Retorna a URL do avatar do usuário (foto enviada ou o padrão do tema). */
function epa_avatar_url( $user_id ) {
    $att = get_user_meta( $user_id, 'epa_avatar', true );
    if ( $att ) {
        $img = wp_get_attachment_image_url( $att, 'thumbnail' );
        if ( $img ) return $img;
    }
    return EPA_URI . '/assets/img/penguin.png';
}
/** Substitui o Gravatar pela foto local, quando existir. */
function epa_filtra_avatar( $args, $id_or_email ) {
    $user_id = 0;
    if ( is_numeric( $id_or_email ) ) $user_id = (int) $id_or_email;
    elseif ( is_object( $id_or_email ) && ! empty( $id_or_email->user_id ) ) $user_id = (int) $id_or_email->user_id;
    elseif ( is_string( $id_or_email ) && ( $u = get_user_by( 'email', $id_or_email ) ) ) $user_id = $u->ID;
    if ( $user_id ) {
        $att = get_user_meta( $user_id, 'epa_avatar', true );
        if ( $att ) {
            $img = wp_get_attachment_image_url( $att, 'thumbnail' );
            if ( $img ) $args['url'] = $img;
        }
    }
    return $args;
}
add_filter( 'get_avatar_data', 'epa_filtra_avatar', 10, 2 );

/* =======================================================================
 * 4. SALVAR PERFIL (foto, apelido, nome, bio)
 * ==================================================================== */
function epa_processar_perfil() {
    if ( ! is_user_logged_in() ||
         ! isset( $_POST['epa_perfil_nonce'] ) ||
         ! wp_verify_nonce( $_POST['epa_perfil_nonce'], 'epa_perfil' ) ) {
        wp_safe_redirect( home_url( '/meu-perfil/?perfil=erro' ) );
        exit;
    }
    $user_id = get_current_user_id();
    $destino = epa_url_pagina( 'meu-perfil' );

    $apelido = sanitize_text_field( $_POST['epa_apelido'] ?? '' );
    $nome    = sanitize_text_field( $_POST['epa_nome'] ?? '' );
    $bio     = sanitize_textarea_field( $_POST['epa_bio'] ?? '' );

    $dados = array( 'ID' => $user_id );
    if ( $nome )    $dados['first_name']   = $nome;
    if ( $apelido ) { $dados['nickname'] = $apelido; $dados['display_name'] = $apelido; }
    $dados['description'] = $bio;
    wp_update_user( $dados );

    // Foto de perfil (upload)
    if ( ! empty( $_FILES['epa_foto']['name'] ) ) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';
        $tipos = array( 'jpg|jpeg' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif', 'webp' => 'image/webp' );
        $att_id = media_handle_upload( 'epa_foto', 0, array(), array( 'test_form' => false, 'mimes' => $tipos ) );
        if ( ! is_wp_error( $att_id ) ) {
            update_user_meta( $user_id, 'epa_avatar', $att_id );
        }
    }
    wp_safe_redirect( add_query_arg( 'perfil', 'ok', $destino ) );
    exit;
}
add_action( 'admin_post_epa_perfil', 'epa_processar_perfil' );

/* =======================================================================
 * 5. EVENTOS DO CALENDÁRIO (adicionar / excluir)
 * ==================================================================== */
function epa_processar_evento() {
    if ( ! is_user_logged_in() ||
         ! isset( $_POST['epa_evento_nonce'] ) ||
         ! wp_verify_nonce( $_POST['epa_evento_nonce'], 'epa_evento' ) ) {
        wp_safe_redirect( home_url( '/meu-calendario/?evento=erro' ) );
        exit;
    }
    $destino = epa_url_pagina( 'meu-calendario' );
    $titulo  = sanitize_text_field( $_POST['epa_ev_titulo'] ?? '' );
    $data    = sanitize_text_field( $_POST['epa_ev_data'] ?? '' );
    $hora    = sanitize_text_field( $_POST['epa_ev_hora'] ?? '' );
    $desc    = sanitize_textarea_field( $_POST['epa_ev_desc'] ?? '' );

    if ( ! $titulo || ! preg_match( '/^\d{4}-\d{2}-\d{2}$/', $data ) ) {
        wp_safe_redirect( add_query_arg( 'evento', 'erro', $destino ) );
        exit;
    }
    $id = wp_insert_post( array(
        'post_type'   => 'epa_evento',
        'post_status' => 'publish',
        'post_title'  => $titulo,
        'post_content'=> $desc,
        'post_author' => get_current_user_id(),
    ) );
    if ( $id && ! is_wp_error( $id ) ) {
        update_post_meta( $id, '_epa_data', $data );
        if ( $hora ) update_post_meta( $id, '_epa_hora', $hora );
        // Filho a que o evento se refere (opcional)
        $filho = sanitize_text_field( $_POST['epa_ev_filho'] ?? '' );
        if ( $filho && function_exists( 'hde_get_filho' ) && hde_get_filho( get_current_user_id(), $filho ) ) {
            update_post_meta( $id, '_hde_filho', $filho );
        }
    }
    // volta para o mês do evento
    $ym = substr( $data, 0, 7 );
    wp_safe_redirect( add_query_arg( array( 'evento' => 'ok', 'mes' => $ym ), $destino ) . '#lista' );
    exit;
}
add_action( 'admin_post_epa_evento', 'epa_processar_evento' );

function epa_excluir_evento() {
    if ( ! is_user_logged_in() ||
         ! isset( $_GET['id'], $_GET['_wpnonce'] ) ||
         ! wp_verify_nonce( $_GET['_wpnonce'], 'epa_del_evento' ) ) {
        wp_safe_redirect( epa_url_pagina( 'meu-calendario' ) );
        exit;
    }
    $id = intval( $_GET['id'] );
    $ev = get_post( $id );
    if ( $ev && $ev->post_type === 'epa_evento' && (int) $ev->post_author === get_current_user_id() ) {
        wp_delete_post( $id, true );
    }
    wp_safe_redirect( add_query_arg( 'evento', 'removido', epa_url_pagina( 'meu-calendario' ) ) . '#lista' );
    exit;
}
add_action( 'admin_post_epa_del_evento', 'epa_excluir_evento' );

/** Busca os eventos do usuário (opcionalmente de um mês YYYY-MM). */
function epa_eventos_usuario( $user_id, $mes = '' ) {
    $args = array(
        'post_type'      => 'epa_evento',
        'author'         => $user_id,
        'posts_per_page' => -1,
        'meta_key'       => '_epa_data',
        'orderby'        => 'meta_value',
        'order'          => 'ASC',
        'meta_type'      => 'DATE',
    );
    if ( $mes ) {
        $args['meta_query'] = array( array(
            'key' => '_epa_data', 'value' => $mes, 'compare' => 'LIKE',
        ) );
    }
    return get_posts( $args );
}

/** Gera o link "Adicionar ao Google Agenda" de um evento. */
function epa_link_google_agenda( $titulo, $data, $hora, $desc ) {
    $d = str_replace( '-', '', $data ); // YYYYMMDD
    if ( $hora && preg_match( '/^\d{2}:\d{2}$/', $hora ) ) {
        $ini = $d . 'T' . str_replace( ':', '', $hora ) . '00';
        // +1 hora de duração
        $h2  = sprintf( '%02d', ( (int) substr( $hora, 0, 2 ) + 1 ) % 24 );
        $fim = $d . 'T' . $h2 . substr( $hora, 3, 2 ) . '00';
        $dates = $ini . '/' . $fim;
    } else {
        $fim = date( 'Ymd', strtotime( $data . ' +1 day' ) );
        $dates = $d . '/' . $fim; // dia inteiro
    }
    return add_query_arg( array(
        'action' => 'TEMPLATE',
        'text'   => rawurlencode( $titulo ),
        'dates'  => $dates,
        'details'=> rawurlencode( $desc ),
    ), 'https://calendar.google.com/calendar/render' );
}

/* =======================================================================
 * 6. NOTIFICAÇÕES (eventos nos próximos 7 dias)
 * ==================================================================== */
function epa_notificacoes( $user_id ) {
    $hoje  = date( 'Y-m-d' );
    $limite= date( 'Y-m-d', strtotime( '+7 days' ) );
    $eventos = get_posts( array(
        'post_type'      => 'epa_evento',
        'author'         => $user_id,
        'posts_per_page' => -1,
        'meta_key'       => '_epa_data',
        'orderby'        => 'meta_value',
        'order'          => 'ASC',
        'meta_type'      => 'DATE',
        'meta_query'     => array( array(
            'key' => '_epa_data', 'value' => array( $hoje, $limite ),
            'compare' => 'BETWEEN', 'type' => 'DATE',
        ) ),
    ) );
    return $eventos;
}

/* =======================================================================
 * 7. ASSETS
 * ==================================================================== */
function epa_conta_assets() {
    wp_enqueue_script( 'epa-conta', EPA_URI . '/assets/js/conta.js', array(), EPA_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'epa_conta_assets' );
