<?php
/**
 * Template Name: Hora de Estudar - Registrar
 * Página de cadastro de novos usuários (funcional).
 * @package Hora de Estudar
 */
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();

$erro_cadastro = isset( $_GET['cadastro'] ) && $_GET['cadastro'] !== 'ok' ? epa_mensagem_cadastro( sanitize_text_field( $_GET['cadastro'] ) ) : '';
?>

<!-- Hero -->
<section class="slider_section">
    <div class="container py-5">
        <div class="col-md-8">
            <div class="detail-box">
                <h1>Crie sua conta gratuitamente</h1>
                <p>Registre-se no Hora de Estudar e tenha acesso a trilhas de aprendizado, notificações de novos conteúdos e materiais exclusivos.</p>
            </div>
        </div>
    </div>
</section>

<div class="container-xxl py-5">
    <div class="container">
        <?php if ( is_user_logged_in() ) : ?>
            <div class="col-lg-8 mx-auto text-center">
                <div class="register-section">
                    <i class="fa fa-check-circle fa-3x mb-3" style="color:#2E7D32;"></i>
                    <h2 class="mb-3">Você já está conectado!</h2>
                    <p>Olá, <strong><?php echo esc_html( wp_get_current_user()->display_name ); ?></strong>. Sua conta já está ativa.</p>
                    <a class="btn btnDefault py-2 px-4 mt-2" href="<?php echo esc_url( home_url( '/' ) ); ?>">Ir para a página inicial</a>
                    <a class="btn btnDefault py-2 px-4 mt-2" href="<?php echo esc_url( wp_logout_url( home_url( '/' ) ) ); ?>" style="background:#546E7A !important;border-color:#546E7A !important;">Sair</a>
                </div>
            </div>
        <?php else : ?>
        <div style="max-width:920px;margin:0 auto;">
            <h2 class="mb-4 wow fadeInUp" style="color:#1A77D2;">Por que se registrar?</h2>
            <div class="row g-4">

            <!-- Coluna esquerda: os dois primeiros tópicos -->
            <div class="col-md-6 wow fadeInUp">

                <div class="d-flex align-items-start mb-4">
                    <div style="min-width:44px;height:44px;background:#E3F2FD;border-radius:50%;display:flex;align-items:center;justify-content:center;margin-right:16px;">
                        <i class="fa fa-play-circle" style="color:#1A77D2;font-size:20px;"></i>
                    </div>
                    <div>
                        <h5 style="color:#0D4E93;">Acesso a todos os vídeos</h5>
                        <p style="font-size:0.92rem;">Assista à nossa biblioteca completa de vídeos educativos, disponível a qualquer hora.</p>
                    </div>
                </div>

                <div class="d-flex align-items-start mb-4">
                    <div style="min-width:44px;height:44px;background:#E8F5E9;border-radius:50%;display:flex;align-items:center;justify-content:center;margin-right:16px;">
                        <i class="fa fa-book-open" style="color:#2E7D32;font-size:20px;"></i>
                    </div>
                    <div>
                        <h5 style="color:#0D4E93;">Artigos e materiais exclusivos</h5>
                        <p style="font-size:0.92rem;">Receba conteúdos escritos diretamente no seu e-mail.</p>
                    </div>
                </div>
            </div>

            <!-- Coluna direita: os dois últimos tópicos, alinhados com os primeiros -->
            <div class="col-md-6 wow fadeInUp" data-wow-delay="0.15s">

                <div class="d-flex align-items-start mb-4">
                    <div style="min-width:44px;height:44px;background:#FFF9C4;border-radius:50%;display:flex;align-items:center;justify-content:center;margin-right:16px;">
                        <i class="fa fa-child" style="color:#F5A623;font-size:20px;"></i>
                    </div>
                    <div>
                        <h5 style="color:#0D4E93;">Trilhas por faixa etária</h5>
                        <p style="font-size:0.92rem;">Conteúdo organizado pela idade da criança para facilitar sua navegação e aproveitamento.</p>
                    </div>
                </div>

                <div class="d-flex align-items-start mb-4">
                    <div style="min-width:44px;height:44px;background:#E3F2FD;border-radius:50%;display:flex;align-items:center;justify-content:center;margin-right:16px;">
                        <i class="fa fa-envelope" style="color:#1A77D2;font-size:20px;"></i>
                    </div>
                    <div>
                        <h5 style="color:#0D4E93;">Notificações de novos conteúdos</h5>
                        <p style="font-size:0.92rem;">Seja o primeiro a saber quando publicarmos novos vídeos e artigos.</p>
                    </div>
                </div>

            </div>
            </div>

            <p class="text-center mt-2" style="font-size:0.9rem;color:#546E7A;"><i class="fa fa-lock me-2"></i>Seus dados estão seguros. Não enviamos spam.</p>
        </div>

        <!-- Formulário (movido para baixo dos textos) -->
        <div class="row mt-4">
            <div class="col-lg-7 mx-auto wow fadeInUp" data-wow-delay="0.2s">
                <div class="register-section">
                    <h2 class="mb-1">Cadastro gratuito</h2>
                    <p style="font-size:0.9rem;color:#546E7A;margin-bottom:18px;">Preencha os dados abaixo para criar sua conta.</p>

                    <?php echo do_shortcode( '[google_login]' ); ?>
                    <div class="epa-ou"><span>ou preencha os dados</span></div>

                    <?php if ( $erro_cadastro ) : ?>
                        <div class="epa-notice epa-notice-error"><i class="fa fa-exclamation-circle me-2"></i><?php echo esc_html( $erro_cadastro ); ?></div>
                    <?php endif; ?>

                    <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
                        <input type="hidden" name="action" value="epa_cadastro">
                        <?php wp_nonce_field( 'epa_cadastro', 'epa_cadastro_nonce' ); ?>
                        <div class="row g-3 mb-3">
                            <div class="col-sm-6">
                                <label for="reg_nome" style="font-weight:600;color:#0D4E93;font-size:0.9rem;">Nome</label>
                                <input type="text" id="reg_nome" name="epa_nome" class="form-control formDefault mt-1" placeholder="Ex: Maria" required>
                            </div>
                            <div class="col-sm-6">
                                <label for="reg_sobrenome" style="font-weight:600;color:#0D4E93;font-size:0.9rem;">Sobrenome (completo)</label>
                                <input type="text" id="reg_sobrenome" name="epa_sobrenome" class="form-control formDefault mt-1" placeholder="Ex: Fernanda Souza" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="reg_telefone" style="font-weight:600;color:#0D4E93;font-size:0.9rem;">Número de telefone</label>
                            <input type="tel" id="reg_telefone" name="epa_telefone" class="form-control formDefault mt-1" placeholder="Ex: (41) 99999-9999" required>
                        </div>
                        <div class="mb-3">
                            <label for="reg_email" style="font-weight:600;color:#0D4E93;font-size:0.9rem;">E-mail</label>
                            <input type="email" id="reg_email" name="epa_email" class="form-control formDefault mt-1" placeholder="seu@email.com" required>
                        </div>
                        <div class="mb-3">
                            <label for="reg_email2" style="font-weight:600;color:#0D4E93;font-size:0.9rem;">Confirmar e-mail</label>
                            <input type="email" id="reg_email2" name="epa_email2" class="form-control formDefault mt-1" placeholder="Repita o e-mail" required>
                        </div>
                        <div class="mb-3">
                            <label for="reg_senha" style="font-weight:600;color:#0D4E93;font-size:0.9rem;">Senha</label>
                            <input type="password" id="reg_senha" name="epa_senha" class="form-control formDefault mt-1" placeholder="Mínimo 8 caracteres" required minlength="8">
                        </div>
                        <div class="mb-3">
                            <label for="reg_senha2" style="font-weight:600;color:#0D4E93;font-size:0.9rem;">Confirmar senha</label>
                            <input type="password" id="reg_senha2" name="epa_senha2" class="form-control formDefault mt-1" placeholder="Repita a senha" required>
                        </div>
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" name="epa_manter" id="manter" value="1" checked>
                            <label class="form-check-label" for="manter" style="font-size:0.9rem;color:#546E7A;">
                                Manter login neste site
                            </label>
                        </div>
                        <button class="btn btnDefault w-100 py-3" type="submit" style="font-size:1.05rem;">
                            Criar minha conta <i class="fa fa-arrow-right ms-2"></i>
                        </button>
                        <p class="text-center mt-3" style="font-size:0.88rem;color:#546E7A;">
                            Já tem conta? <a href="<?php echo esc_url( wp_login_url( home_url( '/' ) ) ); ?>" style="color:#1A77D2;">Fazer login</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>
