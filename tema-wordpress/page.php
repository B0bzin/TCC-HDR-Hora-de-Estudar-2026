<?php
/**
 * Template padrão de páginas.
 * @package Hora de Estudar
 */
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();
?>
<section class="slider_section">
    <div class="container py-5">
        <div class="col-md-9">
            <div class="detail-box">
                <h1><?php the_title(); ?></h1>
            </div>
        </div>
    </div>
</section>

<div class="container-xxl py-5">
    <div class="container">
        <div class="col-lg-8 mx-auto epa-conteudo">
            <?php while ( have_posts() ) : the_post(); the_content(); endwhile; ?>
        </div>
    </div>
</div>
<?php get_footer(); ?>
