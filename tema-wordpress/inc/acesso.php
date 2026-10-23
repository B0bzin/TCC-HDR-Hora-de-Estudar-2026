<?php
/**
 * Controle de acesso do Hora de Estudar.
 * Novos usuários são assinantes (não administradores), não veem a barra
 * de administração do WordPress e não acessam o painel (wp-admin).
 *
 * @package Hora de Estudar
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Esconde a barra de administração (toolbar preta do topo) para quem
 * NÃO é administrador. O administrador continua vendo normalmente.
 */
add_filter( 'show_admin_bar', function ( $show ) {
	return current_user_can( 'manage_options' ) ? $show : false;
} );

/**
 * Impede que usuários sem permissão de administrador acessem o painel
 * (wp-admin), redirecionando-os para a página inicial. Requisições AJAX
 * continuam funcionando (curtidas, formulários, etc.).
 */
add_action( 'admin_init', function () {
	// Deixa passar chamadas AJAX (curtidas, etc.) e os formulários do tema
	// que usam admin-post.php (cadastro, contato, perfil, eventos).
	if ( wp_doing_ajax() ) {
		return;
	}
	if ( isset( $GLOBALS['pagenow'] ) && 'admin-post.php' === $GLOBALS['pagenow'] ) {
		return;
	}
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_safe_redirect( home_url( '/' ) );
		exit;
	}
} );

/**
 * Garante que o papel padrão de novos cadastros seja "assinante"
 * (subscriber), para que ninguém seja criado como administrador.
 */
add_action( 'after_setup_theme', function () {
	if ( get_option( 'default_role' ) !== 'subscriber' ) {
		update_option( 'default_role', 'subscriber' );
	}
} );
