<?php
/**
 * Listagem de Vídeos (arquivo do CPT epa_video).
 * Com barra de pesquisa + filtro por área (multisseleção) no topo.
 * @package Hora de Estudar
 */
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();

// Cores de fundo alternadas para os placeholders (vídeos sem capa)
$fundos = array(
    array( 'bg' => '#E3F2FD', 'cor' => '#1A77D2' ),
    array( 'bg' => '#E8F5E9', 'cor' => '#2E7D32' ),
    array( 'bg' => '#FFF9C4', 'cor' => '#F5A623' ),
);

// Cor de cada área (categoria). A ordem define a ordem dos botões.
$areas_cores = array(
    'educacao-formal'            => '#1565C0',
    'educacao-social'            => '#2E7D32',
    'educacao-digital'           => '#E65100',
    'praticas-de-estudo'         => '#6A1B9A',
    'atividades-complementares'  => '#AD1457',
    'autocontrole-e-resiliencia' => '#00695C',
);

// Matérias de Educação Formal (filtro por assunto). Cada item: [rótulo, cor].
$materias_cores = array(
    'matematica' => array( 'Matemática', '#3949AB' ),
    'fisica'     => array( 'Física',     '#00897B' ),
    'quimica'    => array( 'Química',    '#8E24AA' ),
    'biologia'   => array( 'Biologia',   '#43A047' ),
);
?>

<style>
.epa-filtro-barra { max-width: 1000px; margin: 0 auto 2.5rem; }
.epa-busca-wrap { position: relative; margin-bottom: 1.1rem; }
.epa-busca-wrap .epa-busca-icone { position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: #1A77D2; }
#epa-busca-videos { width: 100%; padding: 14px 18px 14px 46px; border: 2px solid #CFE0F5; border-radius: 30px; font-size: 1rem; font-family: inherit; outline: none; transition: border-color .2s, box-shadow .2s; }
#epa-busca-videos:focus { border-color: #1A77D2; box-shadow: 0 0 0 3px rgba(26,119,210,.15); }
.epa-filtro-botoes { display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; }
.epa-filtro-btn, .epa-filtro-todas { --c: #546E7A; cursor: pointer; border: 2px solid var(--c); color: var(--c); background: #fff; border-radius: 30px; padding: 8px 18px; font-size: .92rem; font-weight: 700; font-family: inherit; line-height: 1.2; transition: background .15s, color .15s, box-shadow .15s; }
.epa-filtro-btn:hover, .epa-filtro-todas:hover { background: var(--c); color: #fff; }
.epa-filtro-btn.ativo, .epa-filtro-todas.ativo { background: var(--c); color: #fff; box-shadow: 0 3px 8px rgba(0,0,0,.18); }
.epa-filtro-btn .epa-check { display: none; margin-right: 6px; }
.epa-filtro-btn.ativo .epa-check { display: inline; }
.epa-filtro-sub { text-align: center; margin: 1.3rem 0 .6rem; font-size: .8rem; font-weight: 700; color: #546E7A; text-transform: uppercase; letter-spacing: .5px; }
#epa-sem-resultado { display: none; }
.epa-video-item .article-tag { display: inline-block; border-radius: 20px; padding: 3px 12px; font-size: .78rem; font-weight: 700; }
</style>

<!-- Hero -->
<section class="slider_section">
    <div class="container py-5">
        <div class="col-md-8">
            <div class="detail-box">
                <h1>Biblioteca de Vídeos</h1>
                <p>Conteúdos educativos selecionados para pais e responsáveis: rápidos, práticos e baseados em pedagogia moderna.</p>
            </div>
        </div>
    </div>
</section>

<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center mx-auto mb-4" style="max-width:800px;">
            <h2 class="mb-3 h1">Aprenda com especialistas em educação</h2>
            <p>Nossa biblioteca reúne vídeos sobre alfabetização, matemática, desenvolvimento emocional, rotinas de estudo e muito mais. Todo o conteúdo é gratuito e pensado para ser assistido em família.</p>
        </div>

        <!-- Barra de pesquisa + filtros por área -->
        <div class="epa-filtro-barra">
            <div class="epa-busca-wrap">
                <i class="fa fa-search epa-busca-icone" aria-hidden="true"></i>
                <label for="epa-busca-videos" class="visually-hidden" style="position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0 0 0 0);">Buscar vídeos</label>
                <input type="text" id="epa-busca-videos" placeholder="Buscar um vídeo pelo nome ou assunto..." autocomplete="off">
            </div>
            <div class="epa-filtro-botoes" role="group" aria-label="Filtrar vídeos por área">
                <button type="button" id="epa-filtro-todas" class="epa-filtro-todas ativo">Todas as áreas</button>
                <?php foreach ( $areas_cores as $slug => $cor ) :
                    $termo = get_term_by( 'slug', $slug, 'epa_area' );
                    if ( ! $termo || is_wp_error( $termo ) ) continue; ?>
                    <button type="button" class="epa-filtro-btn" data-area="<?php echo esc_attr( $slug ); ?>" style="--c:<?php echo esc_attr( $cor ); ?>;" aria-pressed="false">
                        <i class="fa fa-check epa-check" aria-hidden="true"></i><?php echo esc_html( $termo->name ); ?>
                    </button>
                <?php endforeach; ?>
            </div>

            <div class="epa-filtro-sub">Filtrar por matéria (Educação Formal)</div>
            <div class="epa-filtro-botoes" role="group" aria-label="Filtrar vídeos por matéria">
                <?php foreach ( $materias_cores as $chave => $mc ) : ?>
                    <button type="button" class="epa-filtro-btn" data-area="mat-<?php echo esc_attr( $chave ); ?>" style="--c:<?php echo esc_attr( $mc[1] ); ?>;" aria-pressed="false">
                        <i class="fa fa-check epa-check" aria-hidden="true"></i><?php echo esc_html( $mc[0] ); ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>

        <?php
        $q = new WP_Query( array(
            'post_type'      => 'epa_video',
            'posts_per_page' => -1,
            'orderby'        => 'date',
            'order'          => 'DESC',
        ) );
        ?>

        <div class="row g-4" id="epa-lista-videos">
            <?php if ( $q->have_posts() ) : $i = 0; ?>
                <?php while ( $q->have_posts() ) : $q->the_post();
                    $f = $fundos[ $i % 3 ];
                    $termos = get_the_terms( get_the_ID(), 'epa_area' );
                    $slugs  = array();
                    $tag    = 'Vídeo';
                    $cor_tag = '#546E7A';
                    if ( $termos && ! is_wp_error( $termos ) ) {
                        foreach ( $termos as $t ) { $slugs[] = $t->slug; }
                        $tag     = $termos[0]->name;
                        $cor_tag = isset( $areas_cores[ $termos[0]->slug ] ) ? $areas_cores[ $termos[0]->slug ] : '#546E7A';
                    }
                    $materia = get_post_meta( get_the_ID(), '_epa_materia', true );
                    if ( $materia && isset( $materias_cores[ $materia ] ) ) {
                        $slugs[] = 'mat-' . $materia;
                        $tag     = $materias_cores[ $materia ][0];
                        $cor_tag = $materias_cores[ $materia ][1];
                    }
                    $data_areas = esc_attr( implode( ' ', $slugs ) );
                    $data_nome  = esc_attr( wp_strip_all_tags( get_the_title() . ' ' . get_the_excerpt() ) );
                ?>
                <div class="col-lg-4 col-md-6 epa-video-item" data-areas="<?php echo $data_areas; ?>" data-nome="<?php echo $data_nome; ?>">
                    <div class="video-card h-100">
                        <?php $cover = epa_video_thumb_url( get_the_ID() ); ?>
                        <a href="<?php the_permalink(); ?>" class="d-block epa-video-thumb<?php echo $cover ? ' tem-capa' : ''; ?>"<?php echo $cover ? '' : ' style="background:' . esc_attr( $f['bg'] ) . ';"'; ?>>
                            <?php if ( $cover ) : ?>
                                <img src="<?php echo esc_url( $cover ); ?>" alt="Capa do vídeo <?php the_title_attribute(); ?>" loading="lazy">
                                <span class="epa-play-overlay"><i class="fa fa-play" aria-hidden="true"></i></span>
                            <?php else : ?>
                                <i class="fa fa-play-circle fa-4x" style="color:<?php echo esc_attr( $f['cor'] ); ?>;opacity:0.6;"></i>
                            <?php endif; ?>
                        </a>
                        <div class="p-4">
                            <span class="article-tag" style="background:<?php echo esc_attr( $cor_tag ); ?>;color:#fff;"><?php echo esc_html( $tag ); ?></span>
                            <h5 class="mt-2 mb-2" style="color:#0D4E93;"><?php the_title(); ?></h5>
                            <p style="font-size:0.9rem;"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24 ) ); ?></p>
                            <a href="<?php the_permalink(); ?>" class="btn btnDefault px-3 py-2 mt-2"><i class="fa fa-play me-2"></i>Assistir</a>
                        </div>
                    </div>
                </div>
                <?php $i++; endwhile; wp_reset_postdata(); ?>
            <?php else : ?>
                <div class="col-12 text-center">
                    <p>Ainda não há vídeos publicados. Adicione vídeos pelo painel em <strong>Vídeos → Adicionar novo</strong>.</p>
                </div>
            <?php endif; ?>
        </div>

        <div id="epa-sem-resultado" class="text-center py-5">
            <i class="fa fa-video-slash fa-3x mb-3" style="color:#B0BEC5;"></i>
            <p style="color:#566F7B;">Nenhum vídeo encontrado com esses filtros. Tente outra palavra ou selecione outra área.</p>
        </div>
    </div>
</div>

<!-- CTA registro -->
<div class="container-xxl py-5 background_secondary">
    <div class="container text-center">
        <h2 class="mb-3">Quer receber novos vídeos toda semana?</h2>
        <p class="mb-4">Registre-se gratuitamente e receba notificações sobre novos conteúdos diretamente no seu e-mail.</p>
        <a class="btn btnDefault py-3 px-5" href="<?php echo esc_url( epa_url_pagina( 'registrar' ) ); ?>">Criar conta grátis</a>
    </div>
</div>

<script>
(function () {
    var busca    = document.getElementById('epa-busca-videos');
    var botoes   = Array.prototype.slice.call(document.querySelectorAll('.epa-filtro-btn'));
    var btnTodas = document.getElementById('epa-filtro-todas');
    var cards    = Array.prototype.slice.call(document.querySelectorAll('.epa-video-item'));
    var vazio    = document.getElementById('epa-sem-resultado');

    function normaliza(s) {
        return (s || '').toLowerCase().normalize('NFD').replace(/[̀-ͯ]/g, '');
    }
    function areasAtivas() {
        return botoes.filter(function (b) { return b.classList.contains('ativo'); })
                     .map(function (b) { return b.getAttribute('data-area'); });
    }
    function filtrar() {
        var termo = normaliza(busca ? busca.value.trim() : '');
        var sel   = areasAtivas();
        var visiveis = 0;
        cards.forEach(function (card) {
            var areas = (card.getAttribute('data-areas') || '').split(' ');
            var texto = normaliza(card.getAttribute('data-nome'));
            var okArea  = sel.length === 0 || sel.some(function (a) { return areas.indexOf(a) !== -1; });
            var okTexto = termo === '' || texto.indexOf(termo) !== -1;
            var mostrar = okArea && okTexto;
            card.style.display = mostrar ? '' : 'none';
            if (mostrar) visiveis++;
        });
        if (vazio) vazio.style.display = visiveis === 0 ? 'block' : 'none';
        if (btnTodas) btnTodas.classList.toggle('ativo', sel.length === 0);
    }
    botoes.forEach(function (b) {
        b.addEventListener('click', function () {
            b.classList.toggle('ativo');
            b.setAttribute('aria-pressed', b.classList.contains('ativo') ? 'true' : 'false');
            filtrar();
        });
    });
    if (btnTodas) {
        btnTodas.addEventListener('click', function () {
            botoes.forEach(function (b) { b.classList.remove('ativo'); b.setAttribute('aria-pressed', 'false'); });
            filtrar();
        });
    }
    if (busca) busca.addEventListener('input', filtrar);
    filtrar();
})();
</script>

<?php get_footer(); ?>
