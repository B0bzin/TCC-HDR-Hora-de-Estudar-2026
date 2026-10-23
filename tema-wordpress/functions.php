<?php
/**
 * Tema Hora de Estudar - Hora de Estudar
 * Funções principais do tema.
 *
 * @package Hora de Estudar
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Segurança: bloqueia acesso direto

define( 'EPA_VERSION', '3.1' );
define( 'EPA_DIR', get_template_directory() );
define( 'EPA_URI', get_template_directory_uri() );

/* -------------------------------------------------------------------------
 * 1. Suportes do tema
 * ---------------------------------------------------------------------- */
function epa_theme_support() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'gallery', 'caption', 'style', 'script' ) );
    add_theme_support( 'custom-logo', array( 'height' => 90, 'width' => 90, 'flex-height' => true, 'flex-width' => true ) );

    register_nav_menus( array(
        'principal' => __( 'Menu Principal', 'epa' ),
        'rodape'    => __( 'Links do Rodapé', 'epa' ),
    ) );

    // Tamanho de imagem para os cards
    add_image_size( 'epa-card', 600, 400, true );
}
add_action( 'after_setup_theme', 'epa_theme_support' );

/* -------------------------------------------------------------------------
 * 2. Enfileirar estilos e scripts
 * ---------------------------------------------------------------------- */
function epa_assets() {
    // --- Fontes e ícones (CDN) ---
    wp_enqueue_style( 'epa-google-fonts', 'https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&family=Inter:wght@400;600&display=swap', array(), null );
    wp_enqueue_style( 'epa-fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css', array(), '5.10.0' );
    wp_enqueue_style( 'epa-bootstrap-icons', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css', array(), '1.4.1' );

    // --- CSS local (ordem importa: bootstrap -> style -> animate -> cores) ---
    wp_enqueue_style( 'epa-bootstrap', EPA_URI . '/assets/css/bootstrap.css', array(), EPA_VERSION );
    wp_enqueue_style( 'epa-animate', EPA_URI . '/assets/lib/animate/animate.min.css', array(), EPA_VERSION );
    wp_enqueue_style( 'epa-style', EPA_URI . '/assets/css/style.css', array( 'epa-bootstrap' ), EPA_VERSION );
    wp_enqueue_style( 'epa-cores', EPA_URI . '/assets/css/epa-cores.css', array( 'epa-style' ), EPA_VERSION );
    // style.css do tema (cabeçalho + ajustes WP)
    wp_enqueue_style( 'epa-theme', get_stylesheet_uri(), array( 'epa-cores' ), EPA_VERSION );

    // --- JavaScript ---
    // Usa o jQuery embutido do WordPress
    wp_enqueue_script( 'epa-bootstrap-bundle', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js', array(), '5.0.0', true );
    wp_enqueue_script( 'epa-wow', EPA_URI . '/assets/lib/wow/wow.min.js', array(), EPA_VERSION, true );
    wp_enqueue_script( 'epa-easing', EPA_URI . '/assets/lib/easing/easing.min.js', array( 'jquery' ), EPA_VERSION, true );
    wp_enqueue_script( 'epa-waypoints', EPA_URI . '/assets/lib/waypoints/waypoints.min.js', array( 'jquery' ), EPA_VERSION, true );
    wp_enqueue_script( 'epa-main', EPA_URI . '/assets/js/main.js', array( 'jquery', 'epa-wow', 'epa-easing' ), EPA_VERSION, true );
    // Acessibilidade — controle de tamanho de fonte (sem dependências)
    wp_enqueue_script( 'epa-acessibilidade', EPA_URI . '/assets/js/acessibilidade.js', array(), EPA_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'epa_assets' );

/* -------------------------------------------------------------------------
 * 3. Carrega módulos do tema
 * ---------------------------------------------------------------------- */
require_once EPA_DIR . '/inc/tipos-conteudo.php';  // CPTs de Vídeos e Artigos
require_once EPA_DIR . '/inc/formularios.php';      // Contato + Cadastro
require_once EPA_DIR . '/inc/setup-inicial.php';    // Cria páginas/menu/conteúdo na ativação
require_once EPA_DIR . '/inc/helpers.php';          // Funções auxiliares (menu fallback, etc.)
require_once EPA_DIR . '/inc/login.php';            // Personalização da tela de login
require_once EPA_DIR . '/inc/interacoes.php';       // Curtir/Não curtir + comentários dos vídeos
require_once EPA_DIR . '/inc/conta.php';            // Perfil, calendário, notificações
require_once EPA_DIR . '/inc/filhos.php';           // Perfis de filhos geridos pelo responsável
require_once EPA_DIR . '/inc/acesso.php';           // Papel assinante, esconder barra e bloquear wp-admin
require_once EPA_DIR . '/inc/sync-tabelas.php';     // Sincroniza tabelas hde_videos/artigos/contas
require_once EPA_DIR . '/inc/admin-usuarios.php';   // Painel de gestão de usuários e comentários (só admin)

/* -------------------------------------------------------------------------
 * 4. Ajustes gerais
 * ---------------------------------------------------------------------- */
// Adiciona classe no body para o WP
function epa_body_classes( $classes ) {
    $classes[] = 'epa-site';
    return $classes;
}
add_filter( 'body_class', 'epa_body_classes' );

// Comprimento do resumo (excerpt)
function epa_excerpt_length( $length ) { return 22; }
add_filter( 'excerpt_length', 'epa_excerpt_length' );

function epa_excerpt_more( $more ) { return '…'; }
add_filter( 'excerpt_more', 'epa_excerpt_more' );

// Faz a busca do site incluir Vídeos, Artigos e Páginas
function epa_busca_inclui_cpts( $query ) {
    if ( ! is_admin() && $query->is_main_query() && $query->is_search() ) {
        $query->set( 'post_type', array( 'epa_video', 'epa_artigo', 'page', 'post' ) );
    }
}
add_action( 'pre_get_posts', 'epa_busca_inclui_cpts' );
