<?php
/**
 * Página de uma Área (taxonomia epa_area) — mostra os Vídeos e os Artigos
 * daquela área juntos, em seções separadas.
 * @package Hora de Estudar
 */
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();

$termo = get_queried_object();

// Vídeos da área
$videos = new WP_Query( array(
    'post_type'      => 'epa_video',
    'posts_per_page' => -1,
    'tax_query'      => array( array( 'taxonomy' => 'epa_area', 'field' => 'term_id', 'terms' => $termo->term_id ) ),
) );
// Artigos da área
$artigos = new WP_Query( array(
    'post_type'      => 'epa_artigo',
    'posts_per_page' => -1,
    'tax_query'      => array( array( 'taxonomy' => 'epa_area', 'field' => 'term_id', 'terms' => $termo->term_id ) ),
) );

$fundos = array(
    array( 'bg' => '#E3F2FD', 'cor' => '#1A77D2' ),
    array( 'bg' => '#E8F5E9', 'cor' => '#2E7D32' ),
    array( 'bg' => '#FFF3D6', 'cor' => '#F5A623' ),
);
?>

<!-- Hero da área -->
<section class="slider_section">
    <div class="container py-5">
        <div class="col-md-9">
            <div class="detail-box">
                <h1><?php echo esc_html( $termo->name ); ?></h1>
                <?php if ( $termo->description ) : ?>
                    <p><?php echo esc_html( $termo->description ); ?></p>
                <?php else : ?>
                    <p>Vídeos e artigos selecionados sobre <?php echo esc_html( $termo->name ); ?>.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- VÍDEOS da área -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center mx-auto mb-5" style="max-width:700px;">
            <h2 class="mb-3 h1"><i class="fa fa-play-circle me-2" style="color:#F5A623;"></i>Vídeos</h2>
            <p>Conteúdos em vídeo sobre <?php echo esc_html( $termo->name ); ?>.</p>
        </div>

        <?php if ( $videos->have_posts() ) : ?>
            <div class="row g-4">
                <?php $i = 0; while ( $videos->have_posts() ) : $videos->the_post();
                    $f = $fundos[ $i % 3 ]; ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="video-card h-100">
                            <?php $cover = epa_video_thumb_url( get_the_ID() ); ?>
                            <a href="<?php the_permalink(); ?>" class="d-block epa-video-thumb<?php echo $cover ? ' tem-capa' : ''; ?>"<?php echo $cover ? '' : ' style="background:' . esc_attr( $f['bg'] ) . ';"'; ?>>
                                <?php if ( $cover ) : ?>
                                    <img src="<?php echo esc_url( $cover ); ?>" alt="Capa do vídeo <?php the_title_attribute(); ?>" loading="lazy">
                                    <span class="epa-play-overlay"><i class="fa fa-play" aria-hidden="true"></i></span>
                                <?php else : ?><i class="fa fa-play-circle fa-4x" style="color:<?php echo esc_attr( $f['cor'] ); ?>;opacity:0.6;"></i><?php endif; ?>
                            </a>
                            <div class="p-4">
                                <h5 class="mt-2 mb-2" style="color:#0D4E93;"><?php the_title(); ?></h5>
                                <p style="font-size:0.9rem;"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22 ) ); ?></p>
                                <a href="<?php the_permalink(); ?>" class="btn btnDefault px-3 py-2 mt-2"><i class="fa fa-play me-2"></i>Assistir</a>
                            </div>
                        </div>
                    </div>
                <?php $i++; endwhile; wp_reset_postdata(); ?>
            </div>
        <?php else : ?>
            <p class="text-center">Ainda não há vídeos nesta área.</p>
        <?php endif; ?>
    </div>
</div>

<!-- ARTIGOS da área -->
<div class="container-xxl py-5 background_secondary">
    <div class="container">
        <div class="text-center mx-auto mb-5" style="max-width:700px;">
            <h2 class="mb-3 h1"><i class="fa fa-book-open me-2" style="color:#2E7D32;"></i>Artigos</h2>
            <p>Leituras sobre <?php echo esc_html( $termo->name ); ?>.</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-8 mx-auto">
                <?php if ( $artigos->have_posts() ) : ?>
                    <?php while ( $artigos->have_posts() ) : $artigos->the_post(); ?>
                        <div class="article-card">
                            <h4 style="color:#0D4E93;margin-top:8px;"><a href="<?php the_permalink(); ?>" style="color:#0D4E93;text-decoration:none;"><?php the_title(); ?></a></h4>
                            <p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 38 ) ); ?></p>
                            <a href="<?php the_permalink(); ?>" class="btn btnDefault px-4 py-2">Ler artigo <i class="fa fa-arrow-right ms-2"></i></a>
                        </div>
                    <?php endwhile; wp_reset_postdata(); ?>
                <?php else : ?>
                    <p class="text-center">Ainda não há artigos nesta área.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="container pb-5 text-center">
    <a class="btn btnDefault py-2 px-4 me-2" href="<?php echo esc_url( get_post_type_archive_link( 'epa_video' ) ); ?>">Todos os vídeos</a>
    <a class="btn btnDefault py-2 px-4" href="<?php echo esc_url( get_post_type_archive_link( 'epa_artigo' ) ); ?>">Todos os artigos</a>
</div>

<?php get_footer(); ?>
