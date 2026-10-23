<?php
/**
 * Template padrão (fallback) — usado quando não há template mais específico.
 * @package Hora de Estudar
 */
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();
?>
<section class="slider_section">
    <div class="container py-5">
        <div class="col-md-8">
            <div class="detail-box">
                <h1><?php echo esc_html( wp_get_document_title() ); ?></h1>
            </div>
        </div>
    </div>
</section>

<div class="container-xxl py-5">
    <div class="container">
        <div class="col-lg-8 mx-auto">
            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                <div class="article-card">
                    <h4 style="color:#0D4E93;"><a href="<?php the_permalink(); ?>" style="color:#0D4E93;text-decoration:none;"><?php the_title(); ?></a></h4>
                    <p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 40 ) ); ?></p>
                    <a href="<?php the_permalink(); ?>" class="btn btnDefault px-4 py-2">Ler mais <i class="fa fa-arrow-right ms-2"></i></a>
                </div>
            <?php endwhile;
                the_posts_pagination( array( 'mid_size' => 2, 'prev_text' => '‹', 'next_text' => '›' ) );
            else : ?>
                <p class="text-center">Nenhum conteúdo encontrado.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php get_footer(); ?>
