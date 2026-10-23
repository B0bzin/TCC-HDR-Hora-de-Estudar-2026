<?php
/**
 * Personalização da página de login do WordPress (wp-login.php)
 * com a identidade visual do Hora de Estudar (Hora de Estudar).
 *
 * @package Hora de Estudar
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Injeta o CSS da tela de login com a paleta do Hora de Estudar.
 */
function epa_login_estilos() {
    $logo = epa_logo_url();
    ?>
    <style type="text/css">
        :root {
            --epa-azul: #1A77D2;
            --epa-azul-escuro: #0D4E93;
            --epa-verde: #2E7D32;
            --epa-amarelo: #F5A623;
            --epa-fundo: #F0F7FF;
        }
        body.login,
        body.login *:not([class*="dashicons"]):not(.fa):not([class*="fa-"]) {
            font-family: Arial, Helvetica, sans-serif !important;
        }
        /* Mantém as fontes de ícones (dashicons do WP e Font Awesome) funcionando.
           Sem isto, o override de Arial acima transformaria os ícones em quadradinhos. */
        body.login [class*="dashicons"],
        body.login [class*="dashicons"]::before {
            font-family: dashicons !important;
        }
        body.login .fa,
        body.login .fa::before,
        body.login [class*="fa-"]::before {
            font-family: "Font Awesome 5 Free" !important;
            font-weight: 900 !important;
        }
        body.login {
            background: linear-gradient(160deg, #F0F7FF 0%, #E8F5E9 100%);
        }
        /* Logo do Hora de Estudar no topo (sobrescreve a logo padrão do WordPress) */
        .login h1 a {
            background-image: url('<?php echo esc_url( $logo ); ?>') !important;
            background-size: contain !important;
            background-position: center center !important;
            background-repeat: no-repeat !important;
            width: 100% !important;
            height: 90px !important;
            margin-bottom: 8px;
        }
        /* Título abaixo do logo */
        .login #login::before {
            content: "Hora de Estudar";
            display: block;
            text-align: center;
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--epa-azul);
            margin-bottom: 18px;
        }
        /* Caixa do formulário */
        .login form {
            border: none;
            border-top: 4px solid var(--epa-amarelo);
            border-radius: 14px;
            box-shadow: 0 6px 24px rgba(21,101,192,0.15);
            padding: 26px 24px;
        }
        .login label { color: var(--epa-azul-escuro); font-weight: 600; }
        /* Campos */
        .login input[type=text],
        .login input[type=password] {
            border: 2px solid #B3C9E8;
            border-radius: 8px;
            padding: 8px 10px;
        }
        .login input[type=text]:focus,
        .login input[type=password]:focus {
            border-color: var(--epa-azul);
            box-shadow: 0 0 0 3px rgba(21,101,192,0.15);
        }
        .login .button-primary,
        .wp-core-ui .button-primary {
            background: var(--epa-azul) !important;
            border-color: var(--epa-azul) !important;
            border-radius: 30px !important;
            font-weight: 700;
            text-shadow: none;
            box-shadow: none;
            transition: background .2s;
        }
        .login .button-primary:hover,
        .wp-core-ui .button-primary:hover {
            background: var(--epa-azul-escuro) !important;
            border-color: var(--epa-azul-escuro) !important;
        }
        /* Empilha e separa os itens finais do login, que antes ficavam colados.
           Lembrar-me em uma linha, o botão Acessar em largura total abaixo,
           e o Login com Google mais abaixo com um espaço claro. */
        .login .forgetmenot { float: none !important; display: block !important; margin: 10px 0 16px !important; }
        .login p.submit { margin: 0 0 6px !important; }
        .login p.submit .button-primary,
        .login #wp-submit { width: 100% !important; float: none !important; }
        .login .wp_google_login { margin-top: 18px !important; }
        .login .wp_google_login__button { display: flex !important; }
        /* Um pequeno espaço entre o texto Lembrar-me e o ícone de ajuda */
        .login .forgetmenot .wp-tooltip { margin-left: 8px; }
        /* Links */
        .login #nav a, .login #backtoblog a {
            color: var(--epa-azul) !important;
            font-weight: 600;
        }
        .login #nav a:hover, .login #backtoblog a:hover {
            color: var(--epa-verde) !important;
        }
        /* Link "Esqueceu a senha?" dentro do formulário */
        .login .epa-esqueceu { text-align: right; margin: -4px 0 14px; }
        .login .epa-esqueceu a {
            color: var(--epa-azul);
            font-weight: 600;
            font-size: 13px;
            text-decoration: none;
        }
        .login .epa-esqueceu a:hover { color: var(--epa-verde); text-decoration: underline; }
        /* Esconde o link padrão duplicado do WordPress ("Perdeu a senha?") no rodapé do form */
        .login #nav a[href*="action=lostpassword"] { display: none; }
        /* Link "Não possui cadastro?" abaixo da caixa de login */
        .login .epa-cadastro-link {
            text-align: center;
            margin: 16px auto 0;
            max-width: 320px;
            font-size: 14px;
            color: #546E7A;
        }
        .login .epa-cadastro-link a { color: var(--epa-azul); font-weight: 700; text-decoration: none; }
        .login .epa-cadastro-link a:hover { color: var(--epa-verde); text-decoration: underline; }
        /* Mensagens */
        .login .message, .login #login_error {
            border-left-color: var(--epa-amarelo);
            border-radius: 8px;
        }
    </style>
    <?php
}
// Prioridade alta em login_head garante que o estilo venha DEPOIS do CSS padrão do WP
add_action( 'login_head', 'epa_login_estilos', 99 );

/**
 * O logo da tela de login aponta para a home do site (não para wordpress.org).
 */
function epa_login_logo_url() {
    return home_url( '/' );
}
add_filter( 'login_headerurl', 'epa_login_logo_url' );

/**
 * Texto alternativo do logo da tela de login.
 */
function epa_login_logo_texto() {
    return get_bloginfo( 'name' );
}
add_filter( 'login_headertext', 'epa_login_logo_texto' );

/**
 * Carrega a fonte Nunito na tela de login.
 */
function epa_login_fonte() {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">';
    echo '<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">';
}
add_action( 'login_head', 'epa_login_fonte' );

/**
 * Botão demonstrativo "Entrar com o Google" na tela de login do WordPress.
 */
function epa_login_google_btn() {
    $g = "alert('Login com Google: demonstração.\\n\\nEsta é uma demonstração visual para o TCC. A integração real com o Google exige credenciais do Google Cloud.'); return false;";
    ?>
    <div style="margin:0 0 14px;">
        <button type="button" onclick="<?php echo esc_attr( $g ); ?>"
            style="width:100%;display:flex;align-items:center;justify-content:center;gap:10px;background:#fff;color:#3c4043;border:1px solid #DADCE0;border-radius:8px;padding:10px 16px;font-weight:600;font-size:14px;cursor:pointer;">
            <svg width="18" height="18" viewBox="0 0 48 48"><path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/><path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/><path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/><path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/></svg>
            Entrar com o Google
        </button>
        <div style="text-align:center;color:#90A4AE;font-size:12px;margin:12px 0 0;position:relative;">ou entre com sua conta</div>
    </div>
    <?php
}
// Botão demonstrativo desativado — agora o login usa o botão REAL do plugin "Login with Google".
// add_action( 'login_form', 'epa_login_google_btn' );

/**
 * Link "Esqueceu a senha?" dentro do formulário de login,
 * logo abaixo do campo de senha. Aponta para o fluxo nativo
 * de recuperação de senha do WordPress (wp-login.php?action=lostpassword).
 * Prioridade 5 = renderiza antes do botão do Google (prioridade 10).
 */
function epa_login_esqueceu_senha() {
    ?>
    <p class="epa-esqueceu">
        <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>">Esqueceu a senha?</a>
    </p>
    <?php
}
add_action( 'login_form', 'epa_login_esqueceu_senha', 5 );

/**
 * Link "Não possui cadastro? Clique Aqui!" logo abaixo da caixa de login,
 * apontando para a página de cadastro (Registrar). É posicionado por JS
 * imediatamente após o formulário de login.
 */
function epa_login_link_cadastro() {
    $reg = get_permalink( get_page_by_path( 'registrar' ) );
    if ( ! $reg ) $reg = home_url( '/registrar/' );
    ?>
    <div class="epa-cadastro-link">Não possui cadastro? <a href="<?php echo esc_url( $reg ); ?>">Clique Aqui!</a></div>
    <script>
    (function(){
        var box = document.querySelector('.epa-cadastro-link');
        var form = document.getElementById('loginform');
        if ( box && form && form.parentNode ) { form.parentNode.insertBefore( box, form.nextSibling ); }
    })();
    </script>
    <?php
}
add_action( 'login_footer', 'epa_login_link_cadastro' );

/**
 * Remove a seta padrão do WordPress no link "Ir para Hora de Estudar"
 * (o texto nativo vem com "← "). Sem depender de tradução/versão.
 */
function epa_login_sem_seta() {
    ?>
    <script>
    (function(){
        var el = document.querySelector('#backtoblog a');
        if (el) { el.textContent = el.textContent.replace(/^[\s ←‹<]+/, ''); }
    })();
    </script>
    <?php
}
add_action( 'login_footer', 'epa_login_sem_seta' );

/* =========================================================
 * ACESSIBILIDADE (tamanho da fonte) e VLIBRAS na tela de login
 * ========================================================= */

/** Carrega os recursos necessários na tela de login. */
function epa_login_acessibilidade_assets() {
    // Font Awesome (ícone do botão de acessibilidade)
    wp_enqueue_style( 'epa-fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css', array(), '5.10.0' );
    // JS do widget de tamanho de fonte (usa DOMContentLoaded)
    wp_enqueue_script( 'epa-acessibilidade', EPA_URI . '/assets/js/acessibilidade.js', array(), EPA_VERSION, true );
    // VLibras (carregado no <head> para já estar pronto no rodapé)
    wp_enqueue_script( 'vlibrasjs', 'https://vlibras.gov.br/app/vlibras-plugin.js', array(), '1.0' );
    wp_add_inline_script( 'vlibrasjs', 'try{vlibrasjs.load({ async: true });}catch(e){}' );
}
add_action( 'login_enqueue_scripts', 'epa_login_acessibilidade_assets' );

/** CSS dos dois widgets na tela de login. */
function epa_login_acessibilidade_css() {
    ?>
    <style type="text/css">
        .epa-acessibilidade { position: fixed; left: 20px; bottom: 20px; z-index: 9998; display: flex; align-items: flex-end; gap: 10px; font-size: 16px; }
        .epa-acess-toggle { width: 68px; height: 68px; border-radius: 50%; border: none; background: var(--epa-azul); color: #fff; font-size: 31px; cursor: pointer; box-shadow: 0 4px 14px rgba(21,101,192,0.35); transition: background .2s, transform .1s; display: flex; align-items: center; justify-content: center; }
        .epa-acess-toggle:hover { background: var(--epa-azul-escuro); transform: translateY(-2px); }
        .epa-acess-toggle:focus-visible { outline: 3px solid var(--epa-amarelo); outline-offset: 2px; }
        .epa-acess-painel { background: #fff; border-radius: 12px; border-bottom: 4px solid var(--epa-amarelo); box-shadow: 0 6px 22px rgba(21,101,192,0.22); padding: 12px 14px; min-width: 168px; display: none; }
        .epa-acess-painel.aberto { display: block; }
        .epa-acess-titulo { display: block; font-size: 0.8rem; font-weight: 700; color: var(--epa-azul-escuro); margin-bottom: 8px; text-align: center; }
        .epa-acess-controles { display: flex; gap: 6px; justify-content: center; }
        .epa-acess-controles button { flex: 1; border: 2px solid var(--epa-azul); background: #fff; color: var(--epa-azul); border-radius: 8px; font-weight: 700; padding: 6px 0; cursor: pointer; }
        .epa-acess-controles button:hover, .epa-acess-controles button:focus-visible { background: var(--epa-azul); color: #fff; outline: none; }
        .epa-acess-nivel { display: block; text-align: center; font-size: 0.75rem; color: #546E7A; margin-top: 8px; }
        /* Botão do VLibras: maior, canto inferior esquerdo, acima do de acessibilidade */
        div[vw] div[vw-access-button] { left: 18px !important; right: auto !important; bottom: 110px !important; top: auto !important; transform: scale(1.5) !important; transform-origin: left bottom !important; z-index: 99999 !important; }
    </style>
    <?php
}
add_action( 'login_head', 'epa_login_acessibilidade_css', 100 );

/** HTML dos dois widgets no rodapé da tela de login. */
function epa_login_acessibilidade_html() {
    ?>
    <!-- Widget de acessibilidade: tamanho da fonte -->
    <div class="epa-acessibilidade" role="region" aria-label="Acessibilidade">
        <button type="button" class="epa-acess-toggle" aria-expanded="false" aria-controls="epa-acess-painel" aria-label="Acessibilidade: ajustar tamanho da fonte" title="Acessibilidade: tamanho da fonte">
            <i class="fa fa-universal-access" aria-hidden="true"></i>
        </button>
        <div class="epa-acess-painel" id="epa-acess-painel">
            <span class="epa-acess-titulo">Tamanho da fonte</span>
            <div class="epa-acess-controles">
                <button type="button" class="epa-acess-menos" aria-label="Diminuir tamanho da fonte" title="Diminuir">A−</button>
                <button type="button" class="epa-acess-reset" aria-label="Tamanho padrão" title="Padrão">A</button>
                <button type="button" class="epa-acess-mais" aria-label="Aumentar tamanho da fonte" title="Aumentar">A+</button>
            </div>
            <span class="epa-acess-nivel">Tamanho padrão</span>
        </div>
    </div>

    <!-- VLibras -->
    <div vw class="enabled">
        <div vw-access-button class="active"></div>
        <div vw-plugin-wrapper>
            <div class="vw-plugin-top-wrapper"></div>
        </div>
    </div>
    <script>
    (function(){
        var t = 0, iv = setInterval(function(){
            if ( window.VLibras && window.VLibras.Widget ) {
                clearInterval(iv);
                try { new window.VLibras.Widget('https://vlibras.gov.br/app'); } catch(e) {}
            } else if ( ++t > 120 ) { clearInterval(iv); }
        }, 100);
        function force(){ var b=document.querySelector('[vw-access-button]'); if(!b) return;
            b.style.setProperty('position','fixed','important');
            b.style.setProperty('left','18px','important'); b.style.setProperty('right','auto','important');
            b.style.setProperty('bottom','110px','important'); b.style.setProperty('top','auto','important');
            b.style.setProperty('transform','scale(1.5)','important'); b.style.setProperty('transform-origin','left bottom','important');
            b.style.setProperty('z-index','99999','important'); }
        var n=0, fv=setInterval(function(){ force(); if(++n>80) clearInterval(fv); },250);
        window.addEventListener('load', force);
    })();
    </script>
    <?php
}
add_action( 'login_footer', 'epa_login_acessibilidade_html' );

/* =========================================================
 * ENVIO DE E-MAIL EM AMBIENTE LOCAL (localhost)
 * O WAMP não possui servidor de e-mail, então o WordPress não
 * consegue enviar mensagens (ex.: o link de "Esqueceu a senha?").
 * Para o protótipo/TCC, capturamos o e-mail em um arquivo HTML e
 * retornamos "sucesso", evitando o erro na tela. Só age em localhost;
 * em um servidor real (produção), o envio segue o fluxo normal.
 * ========================================================= */
function epa_ambiente_local() {
    $host = wp_parse_url( home_url(), PHP_URL_HOST );
    return in_array( $host, array( 'localhost', '127.0.0.1', '::1' ), true )
        || ( is_string( $host ) && substr( $host, -6 ) === '.local' );
}

function epa_captura_email_local( $return, $atts ) {
    if ( ! epa_ambiente_local() ) {
        return $return; // produção: deixa o WordPress enviar normalmente
    }
    $to      = isset( $atts['to'] ) ? $atts['to'] : '';
    $to      = is_array( $to ) ? implode( ', ', $to ) : $to;
    $subject = isset( $atts['subject'] ) ? $atts['subject'] : '';
    $message = isset( $atts['message'] ) ? $atts['message'] : '';

    $uploads = wp_upload_dir();
    $dir     = trailingslashit( $uploads['basedir'] ) . 'hde-emails';
    if ( ! file_exists( $dir ) ) {
        wp_mkdir_p( $dir );
    }
    $arquivo = trailingslashit( $dir ) . 'email-' . gmdate( 'Ymd-His' ) . '-' . wp_generate_password( 5, false ) . '.html';
    $html    = '<meta charset="utf-8">'
             . '<p><strong>Para:</strong> ' . esc_html( $to ) . '</p>'
             . '<p><strong>Assunto:</strong> ' . esc_html( $subject ) . '</p><hr>'
             . '<pre style="white-space:pre-wrap;font-family:Arial,Helvetica,sans-serif;">' . esc_html( $message ) . '</pre>';
    file_put_contents( $arquivo, $html );

    return true; // finge que enviou, para não exibir o erro de e-mail
}
add_filter( 'pre_wp_mail', 'epa_captura_email_local', 10, 2 );
