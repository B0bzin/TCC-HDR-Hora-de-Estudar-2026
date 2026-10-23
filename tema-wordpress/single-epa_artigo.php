<?php
/**
 * Página individual de um Artigo.
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
                <?php while ( have_posts() ) : the_post(); ?>
                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="mb-4"><?php the_post_thumbnail( 'large', array( 'class' => 'img-fluid rounded w-100' ) ); ?></div>
                    <?php endif; ?>
                    <div class="article-card epa-conteudo">
                        <?php the_content(); ?>
                    </div>
                    <p class="mt-3" style="font-size:0.85rem;color:#546E7A;">
                        <i class="fa fa-calendar me-1"></i><?php echo esc_html( get_the_date() ); ?>
                    </p>
                <?php endwhile; ?>

                <div class="mt-4">
                    <a class="btn btnDefault py-2 px-4" href="<?php echo esc_url( get_post_type_archive_link( 'epa_artigo' ) ); ?>"><i class="fa fa-arrow-left me-2"></i>Voltar aos artigos</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
