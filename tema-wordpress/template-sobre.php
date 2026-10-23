<?php
/**
 * Template Name: Hora de Estudar - Sobre
 * Página "Sobre o Projeto Hora de Estudar".
 * @package Hora de Estudar
 */
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();
?>

<!-- Hero da página Sobre -->
<section class="slider_section">
    <div class="container">
        <div class="row align-items-center py-5">
            <div class="col-md-8 col-lg-7">
                <div class="detail-box">
                    <h1>Sobre o Projeto Hora de Estudar</h1>
                    <p>Conheça nossa história, missão e a equipe do TCC com o IFPR que acredita que todo pai pode ser um parceiro poderoso na educação dos filhos.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- O que é o Hora de Estudar -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center mx-auto mb-5 wow fadeInUp" style="max-width:860px;">
            <h2 class="mb-3 h1">O que é o Hora de Estudar?</h2>
            <p>O <strong>Hora de Estudar (HDE)</strong> é um trabalho de conclusão de curso feito pelo aluno Eduardo do Instituto Federal do Paraná, Campus Pinhais, criado para apoiar pais, mães e responsáveis no processo de educação dos filhos. Entendemos que a família é a primeira e mais importante escola de qualquer criança, e queremos ajudar você a cumprir esse papel com mais segurança, conhecimento e afeto.</p>
            <a class="btn btnDefault py-3 px-5 mt-3" href="<?php echo esc_url( epa_url_pagina( 'registrar' ) ); ?>">Participar gratuitamente</a>
        </div>
    </div>
</div>

<!-- Missão, Visão, Valores -->
<div class="container-xxl py-5 background_secondary">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="h1">Nossa missão</h2>
        </div>
        <div class="row g-4">
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                <div class="card_categoria p-4 h-100 text-center">
                    <i class="fa fa-bullseye fa-2x mb-3" style="color:#1A77D2;"></i>
                    <h3>Missão</h3>
                    <p>Democratizar o acesso ao conhecimento pedagógico, capacitando famílias a participarem ativamente da jornada educacional de suas crianças.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                <div class="card_categoria p-4 h-100 text-center">
                    <i class="fa fa-eye fa-2x mb-3" style="color:#2E7D32;"></i>
                    <h3>Visão</h3>
                    <p>Ser referência nacional em conteúdo educativo para famílias, contribuindo para a formação de gerações mais confiantes, curiosas e preparadas.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                <div class="card_categoria p-4 h-100 text-center">
                    <i class="fa fa-heart fa-2x mb-3" style="color:#F5A623;"></i>
                    <h3>Valores</h3>
                    <p>Inclusão, acessibilidade, respeito ao ritmo de cada criança, colaboração entre família e escola, e compromisso com a ciência e a pedagogia.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quem somos -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6 wow fadeInUp">
                <h2 class="mb-4 h1">O projeto e o IFPR</h2>
                <p>O Hora de Estudar surgiu dentro do <strong>Instituto Federal do Paraná, Campus Pinhais</strong>, como uma resposta ao desejo de professores e alunos de levar conhecimento pedagógico além dos muros da instituição.</p>
                <p class="mb-4">Nossa equipe é formada por um estudante, professores e especialistas em educação que acreditam que compartilhar o saber é uma das formas mais poderosas de transformar vidas. Todo o conteúdo do Hora de Estudar é revisado e fundamentado em pesquisas educacionais atuais.</p>
                <a class="btn btnDefault py-3 px-5" href="<?php echo esc_url( epa_url_pagina( 'registrar' ) ); ?>">Fazer parte do Hora de Estudar</a>
            </div>
            <div class="col-lg-6 about-img wow fadeInUp" data-wow-delay="0.5s">
                <div class="row">
                    <div class="col-12 text-center">
                        <img class="img-fluid w-75 rounded-circle bg-light p-3" src="<?php echo esc_url( EPA_URI ); ?>/assets/img/about-1.jpg" alt="Equipe Hora de Estudar">
                    </div>
                    <div class="col-6 text-start" style="margin-top:-150px;">
                        <img class="img-fluid w-100 rounded-circle bg-light p-3" src="<?php echo esc_url( EPA_URI ); ?>/assets/img/about-2.jpg" alt="IFPR Campus Pinhais">
                    </div>
                    <div class="col-6 text-end" style="margin-top:-150px;">
                        <img class="img-fluid w-100 rounded-circle bg-light p-3" src="<?php echo esc_url( EPA_URI ); ?>/assets/img/about-3.jpg" alt="Projeto educativo">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Conteúdo editável opcional adicionado pelo painel para testes -->
<?php while ( have_posts() ) : the_post(); if ( trim( get_the_content() ) ) : ?>
<div class="container-xxl pb-5">
    <div class="container">
        <div class="col-lg-8 mx-auto epa-conteudo"><?php the_content(); ?></div>
    </div>
</div>
<?php endif; endwhile; ?>

<?php get_footer(); ?>
