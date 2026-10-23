<?php
/**
 * Tipos de conteúdo personalizados (Custom Post Types) do Hora de Estudar:
 *  - Vídeos  (epa_video)   com taxonomia "Categoria de vídeo"
 *  - Artigos (epa_artigo)  com taxonomia "Assunto"
 *  - Mensagens de contato (epa_mensagem) — recebe o formulário "Fale com a gente"
 *
 * @package Hora de Estudar
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/* ============================ VÍDEOS ============================ */
function epa_cpt_videos() {
    $labels = array(
        'name'               => 'Vídeos',
        'singular_name'      => 'Vídeo',
        'menu_name'          => 'Vídeos',
        'add_new'            => 'Adicionar novo',
        'add_new_item'       => 'Adicionar novo vídeo',
        'edit_item'          => 'Editar vídeo',
        'new_item'           => 'Novo vídeo',
        'view_item'          => 'Ver vídeo',
        'search_items'       => 'Buscar vídeos',
        'not_found'          => 'Nenhum vídeo encontrado',
        'all_items'          => 'Todos os vídeos',
    );
    register_post_type( 'epa_video', array(
        'labels'       => $labels,
        'public'       => true,
        'has_archive'  => true,
        'menu_icon'    => 'dashicons-video-alt3',
        'menu_position'=> 5,
        'rewrite'      => array( 'slug' => 'videos' ),
        'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'comments' ),
        'show_in_rest' => true,
    ) );
}
add_action( 'init', 'epa_cpt_videos' );

/* ============================ ARTIGOS ============================ */
function epa_cpt_artigos() {
    $labels = array(
        'name'               => 'Artigos',
        'singular_name'      => 'Artigo',
        'menu_name'          => 'Artigos',
        'add_new'            => 'Adicionar novo',
        'add_new_item'       => 'Adicionar novo artigo',
        'edit_item'          => 'Editar artigo',
        'new_item'           => 'Novo artigo',
        'view_item'          => 'Ver artigo',
        'search_items'       => 'Buscar artigos',
        'not_found'          => 'Nenhum artigo encontrado',
        'all_items'          => 'Todos os artigos',
    );
    register_post_type( 'epa_artigo', array(
        'labels'       => $labels,
        'public'       => true,
        'has_archive'  => true,
        'menu_icon'    => 'dashicons-book-alt',
        'menu_position'=> 6,
        'rewrite'      => array( 'slug' => 'artigos' ),
        'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'author' ),
        'show_in_rest' => true,
    ) );
}
add_action( 'init', 'epa_cpt_artigos' );

/* ============ TAXONOMIA COMPARTILHADA: Área ============ */
/* Uma única taxonomia aplicada a Vídeos E Artigos. A página de cada área
   (ex: /area/educacao-formal/) mostra os vídeos e os artigos daquela área. */
function epa_taxonomia_area() {
    register_taxonomy( 'epa_area', array( 'epa_video', 'epa_artigo' ), array(
        'labels' => array(
            'name'          => 'Áreas',
            'singular_name' => 'Área',
            'add_new_item'  => 'Adicionar área',
            'edit_item'     => 'Editar área',
            'menu_name'     => 'Áreas',
            'all_items'     => 'Todas as áreas',
        ),
        'hierarchical' => true,
        'public'       => true,
        'show_admin_column' => true,
        'rewrite'      => array( 'slug' => 'area' ),
        'show_in_rest' => true,
    ) );
}
add_action( 'init', 'epa_taxonomia_area' );

/* ==================== MENSAGENS DE CONTATO ==================== */
function epa_cpt_mensagens() {
    register_post_type( 'epa_mensagem', array(
        'labels' => array(
            'name'          => 'Mensagens',
            'singular_name' => 'Mensagem',
            'menu_name'     => 'Mensagens de contato',
            'all_items'     => 'Todas as mensagens',
        ),
        'public'       => false,
        'show_ui'      => true,
        'menu_icon'    => 'dashicons-email',
        'menu_position'=> 7,
        'capability_type' => 'post',
        'capabilities' => array( 'create_posts' => 'do_not_allow' ), // só o site cria
        'map_meta_cap' => true,
        'supports'     => array( 'title', 'editor' ),
    ) );
}
add_action( 'init', 'epa_cpt_mensagens' );

/* ============ META BOX: URL do vídeo (YouTube) ============ */
function epa_video_metabox() {
    add_meta_box( 'epa_video_url', 'Link do vídeo (YouTube)', 'epa_video_metabox_html', 'epa_video', 'side', 'high' );
}
add_action( 'add_meta_boxes', 'epa_video_metabox' );

function epa_video_metabox_html( $post ) {
    $url = get_post_meta( $post->ID, '_epa_video_url', true );
    wp_nonce_field( 'epa_video_url_save', 'epa_video_url_nonce' );
    echo '<p><label for="epa_video_url_field">Cole o link do YouTube:</label></p>';
    echo '<input type="url" id="epa_video_url_field" name="epa_video_url_field" value="' . esc_attr( $url ) . '" placeholder="https://www.youtube.com/watch?v=..." style="width:100%;" />';
    echo '<p class="description">Opcional. Se preenchido, o vídeo será exibido incorporado na página.</p>';
}

function epa_video_metabox_save( $post_id ) {
    if ( ! isset( $_POST['epa_video_url_nonce'] ) || ! wp_verify_nonce( $_POST['epa_video_url_nonce'], 'epa_video_url_save' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;
    if ( isset( $_POST['epa_video_url_field'] ) ) {
        update_post_meta( $post_id, '_epa_video_url', esc_url_raw( $_POST['epa_video_url_field'] ) );
    }
}
add_action( 'save_post_epa_video', 'epa_video_metabox_save' );

/* ====== Coluna "Categoria" na lista de mensagens do admin ====== */
function epa_mensagem_columns( $cols ) {
    $cols['epa_email'] = 'E-mail';
    $cols['epa_data']  = 'Recebida em';
    return $cols;
}
add_filter( 'manage_epa_mensagem_posts_columns', 'epa_mensagem_columns' );

function epa_mensagem_column_content( $col, $post_id ) {
    if ( 'epa_email' === $col ) echo esc_html( get_post_meta( $post_id, '_epa_email', true ) );
    if ( 'epa_data' === $col )  echo esc_html( get_the_date( 'd/m/Y H:i', $post_id ) );
}
add_action( 'manage_epa_mensagem_posts_custom_column', 'epa_mensagem_column_content', 10, 2 );

/**
 * Extrai o ID do vídeo a partir de uma URL do YouTube.
 */
function epa_youtube_id( $url ) {
    if ( empty( $url ) ) return '';
    if ( preg_match( '/[?&]v=([^&]+)/', $url, $m ) )        return $m[1];
    if ( preg_match( '#youtu\.be/([^?&/]+)#', $url, $m ) )   return $m[1];
    if ( preg_match( '#embed/([^?&/]+)#', $url, $m ) )       return $m[1];
    return '';
}

/**
 * Converte uma URL do YouTube em URL de incorporação (embed).
 */
function epa_youtube_embed_url( $url ) {
    $id = epa_youtube_id( $url );
    return $id ? 'https://www.youtube.com/embed/' . $id : '';
}

/**
 * URL da capa (thumbnail) de um vídeo:
 *  - imagem destacada do post, se houver;
 *  - senão, a capa oficial do YouTube (a partir do link do vídeo).
 */
function epa_video_thumb_url( $post_id ) {
    if ( has_post_thumbnail( $post_id ) ) {
        $img = get_the_post_thumbnail_url( $post_id, 'epa-card' );
        if ( $img ) return $img;
    }
    $id = epa_youtube_id( get_post_meta( $post_id, '_epa_video_url', true ) );
    return $id ? 'https://img.youtube.com/vi/' . $id . '/hqdefault.jpg' : '';
}
