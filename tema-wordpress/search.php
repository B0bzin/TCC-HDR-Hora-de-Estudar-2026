<?php
/**
 * Resultados de busca — pesquisa em Vídeos, Artigos e Páginas.
 * @package Hora de Estudar
 */
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();
global $wp_query;
$total = $wp_query->found_posts;
?>
<section class="slider_section">
    <div class="container py-5">
        <div class="col-md-9">
            <div class="detail-box">
                <h1>Resultados da busca</h1>
                <p><?php echo esc_html( $total ); ?> resultado(s) para <strong>&ldquo;<?php echo esc_html( get_search_query() ); ?>&rdquo;</strong></p>
            </div>
        </div>
    </div>
</section>

<div class="container-xxl py-5">
    <div class="container">
        <div class="col-lg-8 mx-auto">
            <?php if ( have_posts() ) : while ( have_posts() ) : the_post();
                $tipo = get_post_type();
                $rotulo = $tipo === 'epa_video' ? 'Vídeo' : ( $tipo === 'epa_artigo' ? 'Artigo' : 'Página' );
            ?>
                <?php $cover = ( $tipo === 'epa_video' && function_exists( 'epa_video_thumb_url' ) ) ? epa_video_thumb_url( get_the_ID() ) : ''; ?>
                <div class="article-card epa-busca-item<?php echo $cover ? ' com-capa' : ''; ?>">
                    <?php if ( $cover ) : ?>
                        <a href="<?php the_permalink(); ?>" class="epa-busca-capa">
                            <img src="<?php echo esc_url( $cover ); ?>" alt="Capa do vídeo" loading="lazy">
                            <span class="epa-play-overlay"><i class="fa fa-play" aria-hidden="true"></i></span>
                        </a>
                    <?php endif; ?>
                    <div class="epa-busca-corpo">
                        <span class="article-tag"><?php echo esc_html( $rotulo ); ?></span>
                        <h4 style="color:#0D4E93;margin-top:8px;"><a href="<?php the_permalink(); ?>" style="color:#0D4E93;text-decoration:none;"><?php the_title(); ?></a></h4>
                        <p><?php echo esc_html( wp_trim_words( get_the_excerpt() ? get_the_excerpt() : wp_strip_all_tags( get_the_content() ), 35 ) ); ?></p>
                        <a href="<?php the_permalink(); ?>" class="btn btnDefault px-4 py-2">Abrir <i class="fa fa-arrow-right ms-2"></i></a>
                    </div>
                </div>
            <?php endwhile;
                the_posts_pagination( array( 'mid_size' => 2, 'prev_text' => '‹', 'next_text' => '›' ) );
            else : ?>
                <div class="text-center">
                    <i class="fa fa-search fa-3x mb-3" style="color:#B3C9E8;"></i>
                    <p>Nenhum conteúdo encontrado para essa busca. Tente outras palavras.</p>
                    <a class="btn btnDefault py-2 px-4" href="<?php echo esc_url( home_url( '/' ) ); ?>">Voltar ao início</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php get_footer(); ?>
