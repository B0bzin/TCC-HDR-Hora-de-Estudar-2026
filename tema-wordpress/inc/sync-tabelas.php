<?php
/**
 * Sincronização automática das tabelas relacionais do TCC
 * (epa_videos, epa_artigos, epa_contas) com os dados do WordPress.
 * Sempre que um vídeo/artigo/usuário é criado, editado ou removido,
 * a tabela correspondente é atualizada.
 *
 * @package Hora de Estudar
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Cria as tabelas caso não existam (executado na ativação do tema).
 */
function epa_sync_criar_tabelas() {
	global $wpdb;
	$cc  = $wpdb->get_charset_collate();
	$pfx = $wpdb->prefix;

	$wpdb->query( "CREATE TABLE IF NOT EXISTS {$pfx}videos (
		id BIGINT UNSIGNED NOT NULL PRIMARY KEY,
		titulo VARCHAR(255) NOT NULL,
		area VARCHAR(120) DEFAULT NULL,
		descricao TEXT,
		link_youtube VARCHAR(255) DEFAULT NULL,
		curtidas INT DEFAULT 0,
		descurtidas INT DEFAULT 0,
		data_publicacao DATETIME DEFAULT NULL
	) $cc" );

	$wpdb->query( "CREATE TABLE IF NOT EXISTS {$pfx}artigos (
		id BIGINT UNSIGNED NOT NULL PRIMARY KEY,
		titulo VARCHAR(255) NOT NULL,
		area VARCHAR(120) DEFAULT NULL,
		resumo TEXT,
		conteudo LONGTEXT,
		data_publicacao DATETIME DEFAULT NULL
	) $cc" );

	$wpdb->query( "CREATE TABLE IF NOT EXISTS {$pfx}contas (
		id BIGINT UNSIGNED NOT NULL PRIMARY KEY,
		nome VARCHAR(190) DEFAULT NULL,
		login VARCHAR(120) DEFAULT NULL,
		email VARCHAR(190) DEFAULT NULL,
		apelido VARCHAR(120) DEFAULT NULL,
		papel VARCHAR(60) DEFAULT NULL,
		data_cadastro DATETIME DEFAULT NULL
	) $cc" );
}
add_action( 'after_switch_theme', 'epa_sync_criar_tabelas' );

/**
 * Grava/atualiza um vídeo na tabela epa_videos (prioridade 20 para rodar
 * depois que os metadados, como o link do YouTube, já foram salvos).
 */
function epa_sync_video( $post_id ) {
	if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) return;
	$p = get_post( $post_id );
	if ( ! $p || 'epa_video' !== $p->post_type ) return;
	global $wpdb;

	if ( 'publish' !== $p->post_status ) {
		$wpdb->delete( $wpdb->prefix . 'videos', array( 'id' => $post_id ) );
		return;
	}
	$areas = wp_get_post_terms( $post_id, 'epa_area', array( 'fields' => 'names' ) );
	$wpdb->replace( $wpdb->prefix . 'videos', array(
		'id'              => $post_id,
		'titulo'          => $p->post_title,
		'area'            => $areas ? implode( ', ', $areas ) : null,
		'descricao'       => $p->post_excerpt,
		'link_youtube'    => get_post_meta( $post_id, '_epa_video_url', true ),
		'curtidas'        => (int) get_post_meta( $post_id, '_epa_likes', true ),
		'descurtidas'     => (int) get_post_meta( $post_id, '_epa_dislikes', true ),
		'data_publicacao' => $p->post_date,
	) );
}
add_action( 'save_post_epa_video', 'epa_sync_video', 20 );

/**
 * Grava/atualiza um artigo na tabela epa_artigos.
 */
function epa_sync_artigo( $post_id ) {
	if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) return;
	$p = get_post( $post_id );
	if ( ! $p || 'epa_artigo' !== $p->post_type ) return;
	global $wpdb;

	if ( 'publish' !== $p->post_status ) {
		$wpdb->delete( $wpdb->prefix . 'artigos', array( 'id' => $post_id ) );
		return;
	}
	$areas = wp_get_post_terms( $post_id, 'epa_area', array( 'fields' => 'names' ) );
	$wpdb->replace( $wpdb->prefix . 'artigos', array(
		'id'              => $post_id,
		'titulo'          => $p->post_title,
		'area'            => $areas ? implode( ', ', $areas ) : null,
		'resumo'          => $p->post_excerpt,
		'conteudo'        => wp_strip_all_tags( $p->post_content ),
		'data_publicacao' => $p->post_date,
	) );
}
add_action( 'save_post_epa_artigo', 'epa_sync_artigo', 20 );

/**
 * Remove o registro das tabelas quando o vídeo/artigo é excluído ou vai para a lixeira.
 */
function epa_sync_apagar_post( $post_id ) {
	global $wpdb;
	$pt = get_post_type( $post_id );
	if ( 'epa_video' === $pt ) {
		$wpdb->delete( $wpdb->prefix . 'videos', array( 'id' => $post_id ) );
	} elseif ( 'epa_artigo' === $pt ) {
		$wpdb->delete( $wpdb->prefix . 'artigos', array( 'id' => $post_id ) );
	}
}
add_action( 'wp_trash_post', 'epa_sync_apagar_post' );
add_action( 'before_delete_post', 'epa_sync_apagar_post' );

/**
 * Grava/atualiza uma conta na tabela epa_contas (inclusive nos logins via Google).
 */
function epa_sync_conta( $user_id ) {
	$u = get_userdata( $user_id );
	if ( ! $u ) return;
	global $wpdb;
	$wpdb->replace( $wpdb->prefix . 'contas', array(
		'id'            => $u->ID,
		'nome'          => $u->display_name,
		'login'         => $u->user_login,
		'email'         => $u->user_email,
		'apelido'       => get_user_meta( $u->ID, 'nickname', true ),
		'papel'         => ( is_array( $u->roles ) && $u->roles ) ? $u->roles[0] : '',
		'data_cadastro' => $u->user_registered,
	) );
}
add_action( 'user_register', 'epa_sync_conta' );
add_action( 'profile_update', 'epa_sync_conta' );

/**
 * Remove a conta da tabela quando o usuário é excluído.
 */
function epa_sync_apagar_conta( $user_id ) {
	global $wpdb;
	$wpdb->delete( $wpdb->prefix . 'contas', array( 'id' => $user_id ) );
}
add_action( 'deleted_user', 'epa_sync_apagar_conta' );
