<?php
/**
 * Listagem de Artigos (arquivo do CPT epa_artigo).
 * @package Hora de Estudar
 */
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();
?>

<!-- Hero -->
<section class="slider_section">
    <div class="container py-5">
        <div class="col-md-8">
            <div class="detail-box">
                <h1>Artigos e Leituras</h1>
                <p>Textos escritos e revisados por educadores sobre temas que importam para a família que quer aprender junto.</p>
            </div>
        </div>
    </div>
</section>

<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center mx-auto mb-5" style="max-width:700px;">
            <h2 class="mb-3 h1">Artigos em destaque</h2>
            <p>Leituras rápidas e práticas. Cada artigo foi pensado para ser útil no dia a dia.</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-8 mx-auto">
                <?php if ( have_posts() ) : $d = 0.1; ?>
                    <?php while ( have_posts() ) : the_post();
                        $cats = get_the_terms( get_the_ID(), 'epa_area' );
                        $tag  = ( $cats && ! is_wp_error( $cats ) ) ? $cats[0]->name : 'Artigo';
                    ?>
                    <div class="article-card wow fadeInUp" data-wow-delay="<?php echo esc_attr( $d ); ?>s">
                        <span class="article-tag"><?php echo esc_html( $tag ); ?></span>
                        <h4 style="color:#0D4E93;margin-top:8px;"><a href="<?php the_permalink(); ?>" style="color:#0D4E93;text-decoration:none;"><?php the_title(); ?></a></h4>
                        <p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 40 ) ); ?></p>
                        <a href="<?php the_permalink(); ?>" class="btn btnDefault px-4 py-2">Ler artigo <i class="fa fa-arrow-right ms-2"></i></a>
                    </div>
                    <?php $d += 0.1; endwhile; ?>
                <?php else : ?>
                    <p class="text-center">Ainda não há artigos publicados. Adicione em <strong>Artigos → Adicionar novo</strong>.</p>
                <?php endif; ?>

                <div class="mt-4 text-center epa-paginacao">
                    <?php the_posts_pagination( array( 'mid_size' => 2, 'prev_text' => '‹', 'next_text' => '›' ) ); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
