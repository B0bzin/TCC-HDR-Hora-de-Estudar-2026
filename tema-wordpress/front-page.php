<?php
/**
 * Página inicial do Hora de Estudar.
 * @package Hora de Estudar
 */
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();

$url_sobre     = epa_url_pagina( 'sobre' );
$url_videos    = get_post_type_archive_link( 'epa_video' );
$url_artigos   = get_post_type_archive_link( 'epa_artigo' );
$url_registrar = epa_url_pagina( 'registrar' );
?>

<!-- Hero / Slider (Bootstrap 5) -->
<section class="slider_section">
    <div id="customCarousel1" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div class="container">
                    <div class="row align-items-center py-4">
                        <div class="col-md-7 col-lg-6">
                            <div class="detail-box">
                                <h1>Aprenda a ensinar estratégias de estudo que encaixe com o perfil do seu filho.</h1>
                                <p>Descubra métodos pedagógicos modernos para tornar o aprendizado dos seus filhos mais divertido, 
                                    eficaz e cheio de significado.</p>
                                <div class="btn-box mt-4">
                                    <a href="<?php echo esc_url( $url_sobre ); ?>" class="btn1">Comece agora</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 col-lg-6 text-center d-none d-md-block">
                            <img class="epa-hero-img" src="<?php echo esc_url( EPA_URI ); ?>/assets/img/hde-penguin.png" alt="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="container">
                    <div class="row align-items-center py-4">
                        <div class="col-md-7 col-lg-6">
                            <div class="detail-box">
                                <h2>Vídeos e artigos para pais e educadores</h2>
                                <p>Acesse conteúdos selecionados por especialistas em educação infantil, pedagogia e desenvolvimento cognitivo.</p>
                                <div class="btn-box mt-4">
                                    <a href="<?php echo esc_url( $url_videos ); ?>" class="btn1">Ver vídeos</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 col-lg-6 text-center d-none d-md-block">
                            <img class="epa-hero-img" src="<?php echo esc_url( EPA_URI ); ?>/assets/img/hde-penguin.png" alt="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="container">
                    <div class="row align-items-center py-4">
                        <div class="col-md-7 col-lg-6">
                            <div class="detail-box">
                                <h2>Registre-se e acompanhe o progresso</h2>
                                <p>Crie sua conta gratuita e tenha acesso a trilhas de aprendizado personalizadas para cada fase da criança.</p>
                                <div class="btn-box mt-4">
                                    <a href="<?php echo esc_url( $url_registrar ); ?>" class="btn1">Criar conta grátis</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 col-lg-6 text-center d-none d-md-block">
                            <img class="epa-hero-img" src="<?php echo esc_url( EPA_URI ); ?>/assets/img/hde-penguin.png" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container position-relative">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#customCarousel1" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Ir para o slide 1"></button>
                <button type="button" data-bs-target="#customCarousel1" data-bs-slide-to="1" aria-label="Ir para o slide 2"></button>
                <button type="button" data-bs-target="#customCarousel1" data-bs-slide-to="2" aria-label="Ir para o slide 3"></button>
            </div>
            <button type="button" class="epa-carousel-pause" data-bs-target="#customCarousel1" aria-label="Pausar troca automática de slides">
                <i class="fa fa-pause" aria-hidden="true"></i>
            </button>
        </div>
    </div>
</section>

<?php // Atalho de administração (visível apenas para administradores)
if ( current_user_can( 'manage_options' ) ) :
    $url_gestao = function_exists( 'hde_url_gestao' ) ? hde_url_gestao() : home_url( '/gestao-usuarios/' ); ?>
<div class="container-xxl pt-4">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-between" style="background:linear-gradient(90deg,#0D4E93,#1A77D2);color:#fff;border-radius:14px;padding:16px 22px;gap:12px;box-shadow:0 4px 16px rgba(13,78,147,.25);">
            <div style="display:flex;align-items:center;gap:12px;">
                <i class="fa fa-user-shield fa-2x" aria-hidden="true"></i>
                <div>
                    <strong style="font-size:1.05rem;">Painel do administrador</strong><br>
                    <small style="opacity:.9;">Gerencie as contas dos usuários e modere os comentários dos vídeos.</small>
                </div>
            </div>
            <a class="btn" style="background:#fff;color:#0D4E93;font-weight:700;border-radius:30px;padding:10px 
            22px;white-space:nowrap;" href="<?php echo esc_url( $url_gestao ); ?>">
                <i class="fa fa-users-cog me-2"></i>Abrir gestão de usuários
            </a>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Apresentação do Hora de Estudar -->
<div class="container-xxl py-4">
    <div class="container">
        <div class="text-center mx-auto mb-5 wow fadeInUp" style="max-width:860px;">
            <h2 class="mb-3 h1">Conheça o <?php bloginfo( "name" ); ?></h2>
            <p>O <strong>Hora de Estudar</strong> é um projeto do Instituto Federal do Paraná voltado a pais e responsáveis que desejam apoiar a educação dos filhos com mais segurança e criatividade. Reunimos conteúdos, vídeos e artigos baseados em métodos comprovados pela ciência e pela pedagogia moderna, tudo num único lugar, gratuito e acessível.</p>
            <a class="btn btnDefault py-3 px-5 mt-3" href="<?php echo esc_url( $url_sobre ); ?>">Saiba mais sobre o projeto</a>
        </div>
    </div>
</div>

<!-- Seção Sobre o projeto -->
<div class="container-xxl py-4 background_secondary">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6 wow fadeInUp">
                <h2 class="mb-4 h1">Por que o Hora de Estudar existe?</h2>
                <p>Muitos pais querem ajudar seus filhos a aprender, mas não sabem por onde começar. O Hora de Estudar nasceu para preencher essa lacuna: oferecemos orientações práticas, baseadas em evidências, para que a educação em casa seja um complemento poderoso à escola.</p>
                <p class="mb-4">Com o suporte do IFPR Campus Pinhais, desenvolvemos materiais acessíveis que transformam a rotina familiar em oportunidades de aprendizado. Porque todo pai e toda mãe pode ser um grande educador.</p>
                <a class="btn btnDefault py-3 px-5" href="<?php echo esc_url( $url_sobre ); ?>">Conheça</a>
            </div>
            <div class="col-lg-6 about-img wow fadeInUp" data-wow-delay="0.5s">
                <div class="row">
                    <div class="col-12 text-center">
                        <img class="img-fluid w-75 rounded-circle bg-light p-3" src="<?php echo esc_url( EPA_URI ); ?>/assets/img/about-1.jpg" alt="Crianças estudando">
                    </div>
                    <div class="col-6 text-start" style="margin-top:-150px;">
                        <img class="img-fluid w-100 rounded-circle bg-light p-3" src="<?php echo esc_url( EPA_URI ); ?>/assets/img/about-2.jpg" alt="Pai ensinando filho">
                    </div>
                    <div class="col-6 text-end" style="margin-top:-150px;">
                        <img class="img-fluid w-100 rounded-circle bg-light p-3" src="<?php echo esc_url( EPA_URI ); ?>/assets/img/about-3.jpg" alt="Criança lendo">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Destaque: Vídeos -->
<div class="container-xxl py-4">
    <div class="container">
        <div class="bg-light rounded">
            <div class="row g-0">
                <div class="col-lg-6 wow fadeIn" style="min-height:400px;">
                    <div class="position-relative h-100">
                        <img class="position-absolute w-100 h-100 rounded" src="<?php echo esc_url( EPA_URI ); ?>/assets/img/call-to-action.jpg" style="object-fit:cover;" alt="Vídeos educativos">
                    </div>
                </div>
                <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s">
                    <div class="h-100 d-flex flex-column justify-content-center p-5">
                        <h2 class="mb-4 h1">Vídeos educativos para toda a família</h2>
                        <p class="mb-4">Nossa biblioteca de vídeos traz conteúdos cuidadosamente selecionados sobre alfabetização, matemática, desenvolvimento emocional e muito mais, apresentados de forma clara e acessível para pais e crianças.</p>
                        <a class="btn btnDefault py-3 px-5" href="<?php echo esc_url( $url_videos ); ?>">Assistir vídeos <i class="fa fa-play ms-2"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Categorias de conteúdo -->
<div class="container-xxl py-4 background_secondary">
    <div class="container">
        <div class="text-center mx-auto mb-5 wow fadeInUp" style="max-width:600px;">
            <h2 class="mb-3 h1">O que você está procurando hoje?</h2>
            <p>Escolha uma das áreas de aprendizado e explore nossos conteúdos organizados especialmente para você e sua família.</p>
        </div>
        <div class="row g-4">
            <?php
            $categorias = array(
                array( 'img' => 'classes-1.jpg', 'slug' => 'educacao-formal',           'titulo' => 'Educação Formal',           'texto' => 'Entenda como funciona o currículo escolar e como os pais podem reforçar em casa o que é ensinado na escola de forma simples e eficaz.' ),
                array( 'img' => 'classes-2.jpg', 'slug' => 'educacao-social',           'titulo' => 'Educação Social',           'texto' => 'Descubra estratégias para desenvolver a inteligência emocional, empatia e habilidades sociais nas crianças desde cedo.' ),
                array( 'img' => 'classes-3.jpg', 'slug' => 'educacao-digital',          'titulo' => 'Educação Digital',          'texto' => 'Saiba como usar a tecnologia a favor do aprendizado, com segurança e equilíbrio, transformando o tempo de tela em momento educativo.' ),
                array( 'img' => 'classes-4.jpg', 'slug' => 'praticas-de-estudo',        'titulo' => 'Práticas de Estudo',        'texto' => 'Técnicas de estudo comprovadas para diferentes idades: organização, foco, mapas mentais e rotinas que realmente funcionam.' ),
                array( 'img' => 'classes-5.jpg', 'slug' => 'atividades-complementares', 'titulo' => 'Atividades Complementares', 'texto' => 'Arte, música, esporte e jogos cognitivos que estimulam criatividade, concentração e desenvolvimento integral das crianças.' ),
                array( 'img' => 'classes-6.jpg', 'slug' => 'autocontrole-e-resiliencia','titulo' => 'Autocontrole e Resiliência','texto' => 'Como ajudar as crianças a lidarem com frustrações, erros e dificuldades, desenvolvendo confiança e persistência para aprender.' ),
            );
            $delay = 0.1;
            foreach ( $categorias as $cat ) : $url_area = epa_area_url( $cat['slug'] ); ?>
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="<?php echo esc_attr( $delay ); ?>s">
                    <div class="classes-item">
                        <div class="card_categoria_img w-75 mx-auto p-3">
                            <img class="img-fluid" src="<?php echo esc_url( EPA_URI ); ?>/assets/img/<?php echo esc_attr( $cat['img'] ); ?>" alt="<?php echo esc_attr( $cat['titulo'] ); ?>">
                        </div>
                        <div class="card_categoria p-4 pt-5 mt-n5">
                            <a class="d-block text-center h3 mt-3 mb-3" href="<?php echo esc_url( $url_area ); ?>"><?php echo esc_html( $cat['titulo'] ); ?></a>
                            <p class="text-justify"><?php echo esc_html( $cat['texto'] ); ?></p>
                            <div class="text-center mt-3">
                                <a class="btn btnDefault px-4" href="<?php echo esc_url( $url_area ); ?>">Explorar</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php $delay += 0.2; endforeach; ?>
        </div>
    </div>
</div>

<!-- Formulário de contato (funcional) -->
<div class="container-xxl py-4" id="contato">
    <div class="container">
        <div class="bg-light rounded">
            <div class="row g-0">
                <div class="col-lg-6">
                    <div class="h-100 d-flex flex-column justify-content-center p-5">
                        <h2 class="mb-4 h1">Fale com a gente</h2>
                        <p class="mb-4">Tem dúvidas, sugestões ou quer saber mais sobre o projeto? Envie uma mensagem e nossa equipe responde em breve.</p>

                        <?php if ( isset( $_GET['contato'] ) && $_GET['contato'] === 'ok' ) : ?>
                            <div class="epa-notice epa-notice-success"><i class="fa fa-check-circle me-2"></i>Mensagem enviada com sucesso! Obrigado pelo contato.</div>
                        <?php elseif ( isset( $_GET['contato'] ) && $_GET['contato'] === 'erro' ) : ?>
                            <div class="epa-notice epa-notice-error"><i class="fa fa-exclamation-circle me-2"></i>Preencha nome, e-mail válido e a mensagem para enviar.</div>
                        <?php endif; ?>

                        <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
                            <input type="hidden" name="action" value="epa_contato">
                            <?php wp_nonce_field( 'epa_contato', 'epa_contato_nonce' ); ?>
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <input type="text" name="epa_nome" class="form-control formDefault" aria-label="Seu nome (responsável)" placeholder="Seu nome (responsável)" required>
                                </div>
                                <div class="col-sm-6">
                                    <input type="email" name="epa_email" class="form-control formDefault" aria-label="Seu e-mail" placeholder="Seu e-mail" required>
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" name="epa_crianca" class="form-control formDefault" aria-label="Nome da criança" placeholder="Nome da criança">
                                </div>
                                <div class="col-sm-6">
                                    <input type="number" name="epa_idade" class="form-control formDefault" aria-label="Idade da criança" placeholder="Idade da criança">
                                </div>
                                <div class="col-12">
                                    <textarea name="epa_mensagem" class="form-control formDefault" aria-label="Sua mensagem" placeholder="Sua mensagem..." style="height:100px;" required></textarea>
                                </div>
                                <div class="col-12">
                                    <button class="btn btnDefault w-100 py-3" type="submit">Enviar mensagem</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-6 wow fadeIn" style="min-height:400px;">
                    <div class="position-relative h-100">
                        <img class="position-absolute w-100 h-100 rounded" src="<?php echo esc_url( EPA_URI ); ?>/assets/img/appointment.jpg" style="object-fit:cover;" alt="Família estudando">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
