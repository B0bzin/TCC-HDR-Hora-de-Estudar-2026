<?php
/**
 * Funções auxiliares do tema Hora de Estudar.
 * Geram a navegação mantendo exatamente a aparência do site HTML original.
 *
 * @package Hora de Estudar
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Retorna os itens de navegação do site (rótulo => URL).
 */
function epa_itens_navegacao() {
    return array(
        'inicio'    => array( 'label' => 'Início',    'url' => home_url( '/' ) ),
        'sobre'     => array( 'label' => 'Sobre',     'url' => epa_url_pagina( 'sobre' ) ),
        'videos'    => array( 'label' => 'Vídeos',    'url' => get_post_type_archive_link( 'epa_video' ) ),
        'artigos'   => array( 'label' => 'Artigos',   'url' => get_post_type_archive_link( 'epa_artigo' ) ),
        'registrar' => array( 'label' => 'Registrar', 'url' => epa_url_pagina( 'registrar' ) ),
    );
}

/**
 * URL da página de uma Área (taxonomia epa_area) pelo slug, com fallback.
 */
function epa_area_url( $slug ) {
    $termo = get_term_by( 'slug', $slug, 'epa_area' );
    if ( $termo && ! is_wp_error( $termo ) ) {
        $link = get_term_link( $termo );
        if ( ! is_wp_error( $link ) ) return $link;
    }
    return home_url( "/area/$slug/" );
}

/**
 * URL de uma página pelo slug, com fallback.
 */
function epa_url_pagina( $slug ) {
    $pagina = get_page_by_path( $slug );
    return $pagina ? get_permalink( $pagina ) : home_url( "/$slug/" );
}

/**
 * Descobre qual item de navegação está ativo na tela atual.
 */
function epa_item_ativo() {
    if ( is_front_page() ) return 'inicio';
    if ( is_post_type_archive( 'epa_video' ) || is_singular( 'epa_video' ) || is_tax( 'epa_area' ) ) return 'videos';
    if ( is_post_type_archive( 'epa_artigo' ) || is_singular( 'epa_artigo' ) || is_tax( 'epa_area' ) ) return 'artigos';
    if ( is_page( 'sobre' ) )     return 'sobre';
    if ( is_page( 'registrar' ) ) return 'registrar';
    return '';
}

/**
 * Imprime os links do menu principal (dentro da navbar).
 */
function epa_menu_principal() {
    $itens = epa_itens_navegacao();
    $ativo = epa_item_ativo();
    foreach ( $itens as $chave => $item ) {
        $classe = 'nav-item nav-link' . ( $chave === $ativo ? ' active' : '' );
        printf(
            '<a href="%s" class="%s">%s</a>',
            esc_url( $item['url'] ),
            esc_attr( $classe ),
            esc_html( $item['label'] )
        );
    }
}

/**
 * Imprime os links rápidos do rodapé.
 */
function epa_links_rodape() {
    foreach ( epa_itens_navegacao() as $item ) {
        printf(
            '<a class="btn btnLink" href="%s">%s</a>',
            esc_url( $item['url'] ),
            esc_html( $item['label'] )
        );
    }
}

/**
 * Imprime o link de conta na navbar:
 *  - Visitante  → botão "Entrar"
 *  - Logado     → "Olá, Nome" + "Sair"
 */
function epa_link_conta() {
    if ( ! is_user_logged_in() ) {
        printf(
            '<a class="btn btn-outline-secondary epa-btn-conta" href="%s"><i class="fa fa-sign-in-alt me-1"></i>Entrar</a>',
            esc_url( wp_login_url( home_url( '/' ) ) )
        );
        return;
    }

    $usuario  = wp_get_current_user();
    $nome     = $usuario->display_name ? $usuario->display_name : $usuario->user_login;
    $primeiro = explode( ' ', trim( $nome ) )[0];
    $avatar   = function_exists( 'hde_perfil_avatar_url' ) ? hde_perfil_avatar_url() : ( function_exists( 'epa_avatar_url' ) ? epa_avatar_url( $usuario->ID ) : '' );
    $notifs   = function_exists( 'epa_notificacoes' ) ? epa_notificacoes( $usuario->ID ) : array();
    $n        = count( $notifs );

    echo '<div class="epa-conta d-flex align-items-center">';

    // Sininho de notificações
    echo '<div class="epa-notif">';
    echo '<button type="button" class="epa-notif-btn" aria-label="Notificações" aria-expanded="false">';
    echo '<i class="fa fa-bell" aria-hidden="true"></i>';
    if ( $n > 0 ) echo '<span class="epa-notif-badge">' . esc_html( $n ) . '</span>';
    echo '</button>';
    echo '<div class="epa-notif-painel" hidden>';
    echo '<span class="epa-notif-titulo">Notificações</span>';
    if ( $n > 0 ) {
        foreach ( $notifs as $ev ) {
            $d = get_post_meta( $ev->ID, '_epa_data', true );
            printf(
                '<a class="epa-notif-item" href="%s"><i class="fa fa-calendar-day me-2"></i><span><strong>%s</strong><small>%s</small></span></a>',
                esc_url( epa_url_pagina( 'meu-calendario' ) ),
                esc_html( $ev->post_title ),
                esc_html( date_i18n( 'd/m', strtotime( $d ) ) )
            );
        }
    } else {
        echo '<span class="epa-notif-vazio">Sem novidades por enquanto.</span>';
    }
    echo '</div></div>';

    // Perfil ativo (responsável ou filho) — crianças usam sem login
    $tem_filhos = function_exists( 'hde_get_filhos' ) ? ( count( hde_get_filhos( $usuario->ID ) ) > 0 ) : false;
    $perfil     = function_exists( 'hde_perfil_ativo' ) ? hde_perfil_ativo() : null;
    $nome_ativo = ( $perfil && $perfil['tipo'] === 'filho' ) ? $perfil['nome'] : $primeiro;

    // Avatar + nome (link para o perfil)
    printf(
        '<a class="epa-ola" href="%s"><img class="epa-nav-avatar" src="%s" alt="">Olá, %s</a>',
        esc_url( epa_url_pagina( 'meu-perfil' ) ),
        esc_url( $avatar ),
        esc_html( $nome_ativo )
    );

    // Trocar de perfil (só se houver filhos cadastrados)
    if ( $tem_filhos ) {
        printf(
            '<a class="btn btn-outline-secondary epa-btn-conta" href="%s" title="Trocar perfil"><i class="fa fa-users me-1"></i>Trocar</a>',
            esc_url( epa_url_pagina( 'quem-esta-usando' ) )
        );
    }

    printf(
        '<a class="btn btn-outline-secondary epa-btn-conta" href="%s"><i class="fa fa-sign-out-alt me-2"></i>&nbsp;Sair</a>',
        esc_url( wp_logout_url( home_url( '/' ) ) )
    );
    echo '</div>';
}

/**
 * URL do logo do tema (usa logo do WP se definido, senão a imagem padrão).
 */
function epa_logo_url() {
    if ( has_custom_logo() ) {
        $id  = get_theme_mod( 'custom_logo' );
        $img = wp_get_attachment_image_src( $id, 'full' );
        if ( $img ) return $img[0];
    }
    return EPA_URI . '/assets/img/hde-logo.png';
}
