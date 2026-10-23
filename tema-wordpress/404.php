<?php
/**
 * Página de erro 404.
 * @package Hora de Estudar
 */
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();
?>
<div class="container-xxl py-5" style="min-height:50vh;">
    <div class="container py-5 text-center">
        <h1 style="font-size:5rem;color:#1A77D2;">404</h1>
        <h2 class="mb-3">Página não encontrada</h2>
        <p class="mb-4">A página que você procura não existe ou foi movida.</p>
        <a class="btn btnDefault py-3 px-5" href="<?php echo esc_url( home_url( '/' ) ); ?>">Voltar ao início</a>
    </div>
</div>
<?php get_footer(); ?>
