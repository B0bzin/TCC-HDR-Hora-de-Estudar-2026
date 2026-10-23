<?php
/**
 * Cabeçalho do tema — abertura do HTML, spinner e navbar.
 * @package Hora de Estudar
 */
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link rel="icon" type="image/png" href="<?php echo esc_url( EPA_URI . '/assets/img/favicon.png?v=' . EPA_VERSION ); ?>">
    <link rel="shortcut icon" type="image/png" href="<?php echo esc_url( EPA_URI . '/assets/img/favicon.png?v=' . EPA_VERSION ); ?>">
    <link rel="apple-touch-icon" href="<?php echo esc_url( EPA_URI . '/assets/img/apple-touch-icon.png?v=' . EPA_VERSION ); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- Sem JavaScript, o spinner não deve cobrir o conteúdo -->
    <noscript><style>#spinner{display:none !important;}</style></noscript>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- Link para pular a navegação (acessibilidade 2.4.1) -->
<a class="epa-skip-link" href="#conteudo">Pular para o conteúdo</a>

<div class="container-xxl p-0">

    <!-- Spinner -->
    <div id="spinner" class="show position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center" style="background:#fff; z-index:9999;" aria-hidden="true">
        <div class="spinner-border text-primary" style="width:3rem;height:3rem;" role="status"><span class="sr-only">Carregando...</span></div>
    </div>

    <!-- Cabeçalho / Navbar -->
    <header>
        <nav class="menu navbar navbar-expand-xl navbar-light sticky-top px-3 px-xl-4 py-xl-0" aria-label="Navegação principal">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="navbar-brand">
                <span class="m-0 mainTitle"><img class="img_logo" src="<?php echo esc_url( EPA_URI . '/assets/img/hde-penguin.png' ); ?>" alt="Logo Hora de Estudar"><?php bloginfo( 'name' ); ?></span>
            </a>
            <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Abrir menu de navegação">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav mx-auto">
                    <?php epa_menu_principal(); ?>
                </div>
                <div class="epa-navbar-right">
                    <form class="epa-busca" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                        <input id="epa-busca" type="search" name="s" placeholder="Buscar conteúdo no site" value="<?php echo esc_attr( get_search_query() ); ?>" aria-label="Buscar conteúdo no site">
                        <button type="submit" aria-label="Buscar"><i class="fa fa-search" aria-hidden="true"></i></button>
                    </form>
                    <?php epa_link_conta(); ?>
                </div>
            </div>
        </nav>
    </header>

    <main id="conteudo" tabindex="-1">
