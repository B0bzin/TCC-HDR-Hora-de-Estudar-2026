<?php
/**
 * Página individual de um Vídeo.
 * @package Hora de Estudar
 */
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();
?>

<section class="slider_section">
    <div class="container py-5">
        <div class="col-md-9">
            <div class="detail-box">
                <?php $cats = get_the_terms( get_the_ID(), 'epa_area' ); if ( $cats && ! is_wp_error( $cats ) ) : ?>
                    <span class="article-tag" style="background:#FFF9C4;"><?php echo esc_html( $cats[0]->name ); ?></span>
                <?php endif; ?>
                <h1 class="mt-2"><?php the_title(); ?></h1>
            </div>
        </div>
    </div>
</section>

<div class="container-xxl py-5">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-8 mx-auto">
                <?php while ( have_posts() ) : the_post();
                    $embed = epa_youtube_embed_url( get_post_meta( get_the_ID(), '_epa_video_url', true ) );
                ?>
                    <?php if ( $embed ) : ?>
                        <div class="epa-video-embed mb-4 rounded" style="overflow:hidden;box-shadow:0 4px 18px rgba(26,119,210,0.18);">
                            <iframe src="<?php echo esc_url( $embed ); ?>" title="<?php the_title_attribute(); ?>" allowfullscreen loading="lazy"></iframe>
                        </div>
                    <?php elseif ( has_post_thumbnail() ) : ?>
                        <div class="mb-4"><?php the_post_thumbnail( 'large', array( 'class' => 'img-fluid rounded w-100' ) ); ?></div>
                    <?php endif; ?>

                    <div class="article-card">
                        <h2 class="h4 mb-3" style="color:#0D4E93;"><i class="fa fa-book-open me-2" aria-hidden="true"></i>Sobre este assunto</h2>
                        <p class="text-muted" style="font-size:.9rem;margin-top:-6px;"><i class="fa fa-universal-access me-1" aria-hidden="true"></i>Texto explicativo para quem prefere ler ou não consegue assistir ao vídeo.</p>
                        <?php the_content(); ?>
                    </div>

                    <?php // Curtir / Não curtir
                    if ( function_exists( 'epa_render_votos' ) ) epa_render_votos( get_the_ID() ); ?>

                    <?php // Comentários
                    comments_template(); ?>
                <?php endwhile; ?>

                <div class="mt-4">
                    <a class="btn btnDefault py-2 px-4" href="<?php echo esc_url( get_post_type_archive_link( 'epa_video' ) ); ?>"><i class="fa fa-arrow-left me-2"></i>Voltar aos vídeos</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>