<?php
/**
 * Configuração automática executada quando o tema Hora de Estudar é ativado.
 * Cria páginas, ajusta permalinks e popula conteúdo de exemplo
 * (os mesmos vídeos e artigos do site HTML original).
 *
 * @package Hora de Estudar
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Executa ao ativar o tema.
 */
function epa_ativar_tema() {
    epa_criar_paginas();
    epa_ajustar_permalinks();
    epa_seed_conteudo();

    // Recarrega as regras de reescrita (para /videos/, /artigos/, /sobre/ funcionarem)
    flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'epa_ativar_tema' );

/**
 * Cria as páginas Sobre e Registrar com seus templates.
 */
function epa_criar_paginas() {
    $paginas = array(
        'sobre'     => array( 'titulo' => 'Sobre',     'template' => 'template-sobre.php' ),
        'registrar' => array( 'titulo' => 'Registrar', 'template' => 'template-registrar.php' ),
    );

    foreach ( $paginas as $slug => $dados ) {
        $existente = get_page_by_path( $slug );
        if ( $existente ) {
            update_post_meta( $existente->ID, '_wp_page_template', $dados['template'] );
            continue;
        }
        $id = wp_insert_post( array(
            'post_title'   => $dados['titulo'],
            'post_name'    => $slug,
            'post_status'  => 'publish',
            'post_type'    => 'page',
            'post_content' => '',
        ) );
        if ( $id && ! is_wp_error( $id ) ) {
            update_post_meta( $id, '_wp_page_template', $dados['template'] );
        }
    }
}

/**
 * Define links permanentes amigáveis (/nome-do-post/).
 */
function epa_ajustar_permalinks() {
    if ( get_option( 'permalink_structure' ) !== '/%postname%/' ) {
        global $wp_rewrite;
        update_option( 'permalink_structure', '/%postname%/' );
        $wp_rewrite->set_permalink_structure( '/%postname%/' );
    }
}

/**
 * Popula vídeos e artigos de exemplo (só na primeira ativação).
 */
function epa_seed_conteudo() {
    if ( get_option( 'epa_conteudo_seed' ) ) return; // já rodou

    /* ---------------- VÍDEOS ---------------- */
    $videos = array(
        array( 'Como apoiar a alfabetização em casa', 'Alfabetização', 'Estratégias simples para tornar a leitura uma atividade prazerosa e cotidiana para crianças de 4 a 8 anos.' ),
        array( 'Matemática sem medo: jogos e brincadeiras', 'Matemática', 'Veja como transformar o aprendizado da matemática em momentos de diversão com atividades práticas.' ),
        array( 'Inteligência emocional na infância', 'Desenvolvimento Emocional', 'Entenda como ajudar seu filho a nomear e lidar com as emoções desde cedo, construindo resiliência.' ),
        array( 'Criando uma rotina de estudos que funciona', 'Rotina de Estudos', 'Dicas práticas para montar uma rotina saudável de estudos adaptada à idade e ao perfil da sua criança.' ),
        array( 'Tela a favor da educação', 'Tecnologia e Aprendizado', 'Como selecionar aplicativos, jogos e vídeos que realmente contribuem para o aprendizado das crianças.' ),
        array( 'Como conversar com os professores do seu filho', 'Família e Escola', 'A parceria entre família e escola é fundamental. Aprenda a construir essa relação de forma positiva.' ),
    );
    foreach ( $videos as $v ) {
        $id = wp_insert_post( array(
            'post_type'    => 'epa_video',
            'post_status'  => 'publish',
            'post_title'   => $v[0],
            'post_excerpt' => $v[2],
            'post_content' => $v[2] . "\n\nEm breve o vídeo completo estará disponível aqui.",
        ) );
        if ( $id && ! is_wp_error( $id ) ) {
            epa_atribuir_termo( $id, 'epa_area', $v[1] );
        }
    }

    /* ---------------- ARTIGOS ---------------- */
    $artigos = array(
        array( '5 formas de estimular o gosto pela leitura nas crianças', 'Alfabetização', 'Criar o hábito de leitura desde cedo é um dos melhores presentes que os pais podem dar aos filhos. Veja estratégias simples que funcionam para diferentes idades e perfis de criança.' ),
        array( 'Como lidar com as frustrações da criança sem minimizar seus sentimentos', 'Desenvolvimento Emocional', 'Aprender a lidar com o "não" e com os erros é uma habilidade fundamental. Este artigo traz orientações baseadas na psicologia do desenvolvimento para apoiar seu filho nesse processo.' ),
        array( 'Montando a rotina de estudos ideal: guia por faixa etária', 'Rotina de Estudos', 'Uma rotina bem estruturada faz toda a diferença no aprendizado. Descubra o tempo ideal de estudo, as melhores técnicas e como adaptar tudo à realidade da sua família.' ),
        array( 'Tela sim, mas com propósito: como escolher apps educativos', 'Tecnologia e Educação', 'Nem todo app infantil é educativo de verdade. Aprenda quais critérios usar para avaliar se um aplicativo ou jogo digital realmente contribui para o desenvolvimento da criança.' ),
        array( 'Parceria família-escola: como fortalecer esse vínculo', 'Família e Escola', 'A relação entre pais e professores é decisiva para o sucesso escolar das crianças. Veja como construir essa parceria de forma positiva e colaborativa.' ),
        array( 'Matemática no cotidiano: aprender sem perceber', 'Matemática', 'Cozinhar, ir ao mercado, medir objetos: o dia a dia está cheio de oportunidades para ensinar matemática de forma natural e divertida. Confira exemplos práticos.' ),
    );
    foreach ( $artigos as $a ) {
        $id = wp_insert_post( array(
            'post_type'    => 'epa_artigo',
            'post_status'  => 'publish',
            'post_title'   => $a[0],
            'post_excerpt' => $a[2],
            'post_content' => '<p>' . $a[2] . '</p><p>Este é um conteúdo de exemplo criado automaticamente. Edite ou substitua pelo texto real em <strong>Artigos → Todos os artigos</strong> no painel.</p>',
        ) );
        if ( $id && ! is_wp_error( $id ) ) {
            epa_atribuir_termo( $id, 'epa_area', $a[1] );
        }
    }

    update_option( 'epa_conteudo_seed', 1 );
}

/**
 * Cria (se preciso) e atribui um termo de taxonomia a um post.
 */
function epa_atribuir_termo( $post_id, $taxonomia, $nome_termo ) {
    $termo = term_exists( $nome_termo, $taxonomia );
    if ( ! $termo ) {
        $termo = wp_insert_term( $nome_termo, $taxonomia );
    }
    if ( ! is_wp_error( $termo ) ) {
        wp_set_object_terms( $post_id, (int) $termo['term_id'], $taxonomia );
    }
}
