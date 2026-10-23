<?php
/**
 * Interações dos vídeos: Curtir / Não curtir (AJAX) + suporte a comentários.
 * @package Hora de Estudar
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/* =======================================================================
 * CURTIR / NÃO CURTIR
 * ==================================================================== */

/**
 * Recebe o voto via AJAX e atualiza os contadores (meta do post).
 */
function epa_vote_handler() {
    check_ajax_referer( 'epa_vote', 'nonce' );

    $post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
    $vote    = ( isset( $_POST['vote'] ) && $_POST['vote'] === 'dislike' ) ? 'dislike' : 'like';

    if ( ! $post_id || get_post_type( $post_id ) !== 'epa_video' ) {
        wp_send_json_error( 'post inválido' );
    }

    $chave = ( $vote === 'like' ) ? '_epa_likes' : '_epa_dislikes';
    $atual = (int) get_post_meta( $post_id, $chave, true );
    update_post_meta( $post_id, $chave, $atual + 1 );

    wp_send_json_success( array(
        'likes'    => (int) get_post_meta( $post_id, '_epa_likes', true ),
        'dislikes' => (int) get_post_meta( $post_id, '_epa_dislikes', true ),
    ) );
}
add_action( 'wp_ajax_epa_vote', 'epa_vote_handler' );
add_action( 'wp_ajax_nopriv_epa_vote', 'epa_vote_handler' );

/**
 * Enfileira o JS das interações (só na página de um vídeo).
 */
function epa_interacoes_assets() {
    if ( ! is_singular( 'epa_video' ) ) return;
    wp_enqueue_script( 'epa-interacoes', EPA_URI . '/assets/js/interacoes.js', array(), EPA_VERSION, true );
    wp_localize_script( 'epa-interacoes', 'epaVote', array(
        'url'   => admin_url( 'admin-ajax.php' ),
        'nonce' => wp_create_nonce( 'epa_vote' ),
    ) );
}
add_action( 'wp_enqueue_scripts', 'epa_interacoes_assets' );

/**
 * Imprime a barra de Curtir / Não curtir de um vídeo.
 */
function epa_render_votos( $post_id ) {
    $likes    = (int) get_post_meta( $post_id, '_epa_likes', true );
    $dislikes = (int) get_post_meta( $post_id, '_epa_dislikes', true );
    ?>
    <div class="epa-votos" data-post-id="<?php echo esc_attr( $post_id ); ?>">
        <span class="epa-votos-label">Este vídeo foi útil para você?</span>
        <div class="epa-votos-botoes">
            <button type="button" class="epa-voto epa-voto-like" data-vote="like" aria-label="Curtir este vídeo">
                <i class="fa fa-thumbs-up" aria-hidden="true"></i>
                <span class="epa-voto-num epa-like-num"><?php echo esc_html( $likes ); ?></span>
            </button>
            <button type="button" class="epa-voto epa-voto-dislike" data-vote="dislike" aria-label="Não curtir este vídeo">
                <i class="fa fa-thumbs-down" aria-hidden="true"></i>
                <span class="epa-voto-num epa-dislike-num"><?php echo esc_html( $dislikes ); ?></span>
            </button>
        </div>
        <span class="epa-votos-obrigado" hidden>Obrigado pelo seu voto!</span>
    </div>
    <?php
}

/* =======================================================================
 * COMENTÁRIOS
 * ==================================================================== */

/**
 * Garante que os comentários fiquem abertos nos vídeos já cadastrados
 * (roda uma única vez).
 */
function epa_abrir_comentarios_videos() {
    if ( get_option( 'epa_coment_videos' ) ) return;
    $videos = get_posts( array( 'post_type' => 'epa_video', 'numberposts' => -1, 'post_status' => 'any' ) );
    foreach ( $videos as $v ) {
        if ( $v->comment_status !== 'open' ) {
            wp_update_post( array( 'ID' => $v->ID, 'comment_status' => 'open' ) );
        }
    }
    update_option( 'epa_coment_videos', 1 );
}
add_action( 'init', 'epa_abrir_comentarios_videos', 20 );
