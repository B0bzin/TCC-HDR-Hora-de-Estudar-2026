<?php
/**
 * Template Name: Hora de Estudar - Meu Perfil
 * @package Hora de Estudar
 */
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();
?>

<section class="slider_section">
    <div class="container py-5">
        <div class="col-md-9"><div class="detail-box">
            <h1>Meu Perfil</h1>
            <p>Personalize sua foto, seu apelido e suas informações.</p>
        </div></div>
    </div>
</section>

<div class="container-xxl py-5">
    <div class="container">
        <div class="col-lg-8 mx-auto">
        <?php if ( ! is_user_logged_in() ) : ?>
            <div class="register-section text-center">
                <i class="fa fa-user-lock fa-3x mb-3" style="color:#1A77D2;"></i>
                <h2 class="mb-3">Faça login para acessar seu perfil</h2>
                <a class="btn btnDefault py-2 px-4" href="<?php echo esc_url( wp_login_url( epa_url_pagina( 'meu-perfil' ) ) ); ?>">Entrar</a>
                <a class="btn btnDefault py-2 px-4" href="<?php echo esc_url( epa_url_pagina( 'registrar' ) ); ?>" style="background:#2E7D32 !important;border-color:#2E7D32 !important;">Criar conta</a>
            </div>
        <?php else :
            $u        = wp_get_current_user();
            $perfil   = function_exists( 'hde_perfil_ativo' ) ? hde_perfil_ativo() : null;
            $eh_filho = ( $perfil && $perfil['tipo'] === 'filho' );
            $filho    = ( $eh_filho && function_exists( 'hde_get_filho' ) ) ? hde_get_filho( $u->ID, $perfil['id'] ) : null;
            if ( $eh_filho && ! $filho ) $eh_filho = false;

            if ( isset( $_GET['perfil'] ) && $_GET['perfil'] === 'ok' ) : ?>
                <div class="epa-notice epa-notice-success"><i class="fa fa-check-circle me-2"></i>Perfil atualizado com sucesso!</div>
            <?php elseif ( isset( $_GET['perfil'] ) ) : ?>
                <div class="epa-notice epa-notice-error"><i class="fa fa-exclamation-circle me-2"></i>Não foi possível salvar. Tente novamente.</div>
            <?php endif; ?>

            <?php if ( $eh_filho ) :
                $foto_filho = hde_filho_foto_url( $filho ); ?>
            <!-- PERFIL DE UMA CRIANÇA (perfil ativo) -->
            <div class="register-section">
                <div class="epa-perfil-topo">
                    <img class="epa-perfil-foto" src="<?php echo esc_url( $foto_filho ); ?>" alt="Foto do perfil">
                    <div>
                        <h2 class="mb-0"><?php echo esc_html( $filho['nome'] ); ?></h2>
                        <p style="color:#566F7B;margin:0;"><i class="fa fa-child me-2"></i>Perfil de criança, gerenciado por <?php echo esc_html( $u->display_name ); ?></p>
                    </div>
                </div>

                <hr>

                <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="hde_editar_filho">
                    <input type="hidden" name="fid" value="<?php echo esc_attr( $filho['id'] ); ?>">
                    <?php wp_nonce_field( 'hde_edit_filho', 'hde_edit_filho_nonce' ); ?>

                    <div class="mb-3">
                        <label class="epa-label-coment" for="foto">Foto do perfil</label>
                        <input type="file" id="foto" name="foto" accept="image/*" class="form-control formDefault">
                        <p class="epa-nota-coment">JPG, PNG, GIF ou WEBP. Deixe em branco para manter a atual.</p>
                    </div>
                    <div class="row g-3">
                        <div class="col-sm-8">
                            <label class="epa-label-coment" for="nome">Nome</label>
                            <input type="text" id="nome" name="nome" class="form-control formDefault" value="<?php echo esc_attr( $filho['nome'] ); ?>" required>
                        </div>
                        <div class="col-sm-4">
                            <label class="epa-label-coment" for="idade">Idade</label>
                            <input type="number" id="idade" name="idade" class="form-control formDefault" value="<?php echo esc_attr( $filho['idade'] ); ?>" min="1" max="18">
                        </div>
                    </div>
                    <div class="row g-3 mt-0">
                        <div class="col-sm-6">
                            <label class="epa-label-coment" for="serie">Série / ano escolar</label>
                            <input type="text" id="serie" name="serie" class="form-control formDefault" value="<?php echo esc_attr( $filho['serie'] ?? '' ); ?>" placeholder="Ex: 3º ano">
                        </div>
                        <div class="col-sm-6">
                            <label class="epa-label-coment" for="escola">Escola</label>
                            <input type="text" id="escola" name="escola" class="form-control formDefault" value="<?php echo esc_attr( $filho['escola'] ?? '' ); ?>" placeholder="Nome da escola">
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btnDefault py-2 px-4"><i class="fa fa-save me-2"></i>&nbsp;Salvar meu perfil</button>
                        <a href="<?php echo esc_url( epa_url_pagina( 'meu-calendario' ) ); ?>" class="btn btnDefault py-2 px-4" style="background:#2E7D32 !important;border-color:#2E7D32 !important;"><i class="fa fa-calendar me-2"></i>&nbsp;Meu Calendário</a>
                        <a href="<?php echo esc_url( epa_url_pagina( 'quem-esta-usando' ) ); ?>" class="btn btnDefault py-2 px-4" style="background:#F5A623 !important;border-color:#F5A623 !important;"><i class="fa fa-users me-2"></i>&nbsp;Trocar de perfil</a>
                    </div>
                </form>
            </div>

            <?php else : ?>
            <!-- PERFIL DO RESPONSÁVEL -->
            <div class="register-section">
                <div class="epa-perfil-topo">
                    <img class="epa-perfil-foto" src="<?php echo esc_url( epa_avatar_url( $u->ID ) ); ?>" alt="Foto de perfil">
                    <div>
                        <h2 class="mb-0"><?php echo esc_html( $u->display_name ); ?></h2>
                        <p style="color:#566F7B;margin:0;"><i class="fa fa-envelope me-2"></i><?php echo esc_html( $u->user_email ); ?></p>
                    </div>
                </div>

                <hr>

                <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="epa_perfil">
                    <?php wp_nonce_field( 'epa_perfil', 'epa_perfil_nonce' ); ?>

                    <div class="mb-3">
                        <label class="epa-label-coment" for="epa_foto">Foto de perfil</label>
                        <input type="file" id="epa_foto" name="epa_foto" accept="image/*" class="form-control formDefault">
                        <p class="epa-nota-coment">JPG, PNG, GIF ou WEBP. Deixe em branco para manter a atual.</p>
                    </div>
                    <div class="mb-3">
                        <label class="epa-label-coment" for="epa_apelido">Apelido (como você aparece no site)</label>
                        <input type="text" id="epa_apelido" name="epa_apelido" class="form-control formDefault" value="<?php echo esc_attr( $u->display_name ); ?>" placeholder="Ex: Pai do Lucas">
                    </div>
                    <div class="mb-3">
                        <label class="epa-label-coment" for="epa_nome">Nome</label>
                        <input type="text" id="epa_nome" name="epa_nome" class="form-control formDefault" value="<?php echo esc_attr( $u->first_name ); ?>" placeholder="Seu nome">
                    </div>
                    <div class="mb-4">
                        <label class="epa-label-coment" for="epa_bio">Sobre você</label>
                        <textarea id="epa_bio" name="epa_bio" class="form-control formDefault" rows="3" placeholder="Conte um pouco sobre você..."><?php echo esc_textarea( $u->description ); ?></textarea>
                    </div>
                    <button type="submit" class="btn btnDefault py-2 px-4">Salvar alterações</button>
                    <a href="<?php echo esc_url( epa_url_pagina( 'meu-calendario' ) ); ?>" class="btn btnDefault py-2 px-4" style="background:#2E7D32 !important;border-color:#2E7D32 !important;"><i class="fa fa-calendar me-2"></i>&nbsp;Meu Calendário</a>
                    <a href="<?php echo esc_url( epa_url_pagina( 'meus-filhos' ) ); ?>" class="btn btnDefault py-2 px-4" style="background:#F5A623 !important;border-color:#F5A623 !important;"><i class="fa fa-child me-2"></i>&nbsp;Meus Filhos</a>
                </form>
            </div>
            <?php endif; ?>
        <?php endif; ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>
