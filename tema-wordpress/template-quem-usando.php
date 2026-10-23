<?php
/**
 * Template Name: Hora de Estudar - Quem está usando
 * Seletor de perfil (responsável ou filho). As crianças usam o site
 * sem precisar fazer login: quem loga é o responsável.
 * @package Hora de Estudar
 */
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();
?>

<div class="container-xxl py-5">
    <div class="container">
        <div class="col-lg-9 mx-auto text-center">
        <?php if ( ! is_user_logged_in() ) : ?>
            <div class="register-section">
                <i class="fa fa-user-lock fa-3x mb-3" style="color:#1A77D2;"></i>
                <h2 class="mb-3">Faça login para escolher o perfil</h2>
                <a class="btn btnDefault py-2 px-4" href="<?php echo esc_url( wp_login_url( epa_url_pagina( 'quem-esta-usando' ) ) ); ?>">Entrar</a>
            </div>
        <?php else :
            $uid    = get_current_user_id();
            $u      = wp_get_current_user();
            $filhos = hde_get_filhos( $uid );
            $set    = function ( $fid ) { return add_query_arg( array( 'action' => 'hde_set_perfil', 'fid' => $fid ), admin_url( 'admin-post.php' ) ); };
        ?>
            <h1 class="mb-2">Quem vai estudar?</h1>
            <p class="mb-5" style="color:#566F7B;">Escolha o perfil. As crianças não precisam fazer login. Quem entra é o responsável.</p>

            <div class="d-flex flex-wrap justify-content-center" style="gap:26px;">
                <!-- Responsável -->
                <a href="<?php echo esc_url( $set( 'responsavel' ) ); ?>" style="text-decoration:none;display:block;">
                    <img src="<?php echo esc_url( epa_avatar_url( $uid ) ); ?>" alt="" style="width:120px;height:120px;border-radius:16px;object-fit:cover;border:3px solid #1A77D2;background:#fff;">
                    <div style="margin-top:10px;color:#0D4E93;font-weight:700;"><?php echo esc_html( $u->display_name ); ?></div>
                    <small style="color:#566F7B;">Responsável</small>
                </a>

                <!-- Filhos -->
                <?php foreach ( $filhos as $f ) :
                    $f_foto = hde_filho_foto_url( $f ); ?>
                <a href="<?php echo esc_url( $set( $f['id'] ) ); ?>" style="text-decoration:none;display:block;">
                    <?php if ( ! empty( $f['foto'] ) ) : ?>
                        <img src="<?php echo esc_url( $f_foto ); ?>" alt="" style="width:120px;height:120px;border-radius:16px;object-fit:cover;border:3px solid #F5A623;background:#fff;">
                    <?php else : ?>
                        <span style="width:120px;height:120px;border-radius:16px;background:#E3F2FD;display:flex;align-items:center;justify-content:center;border:3px solid #F5A623;">
                            <i class="fa fa-child" style="font-size:56px;color:#F5A623;"></i>
                        </span>
                    <?php endif; ?>
                    <div style="margin-top:10px;color:#0D4E93;font-weight:700;"><?php echo esc_html( $f['nome'] ); ?></div>
                    <?php if ( ! empty( $f['idade'] ) ) : ?><small style="color:#566F7B;"><?php echo esc_html( $f['idade'] ); ?> anos</small><?php endif; ?>
                </a>
                <?php endforeach; ?>

                <!-- Adicionar filho -->
                <a href="<?php echo esc_url( epa_url_pagina( 'meus-filhos' ) ); ?>" style="text-decoration:none;display:block;">
                    <span style="width:120px;height:120px;border-radius:16px;background:#F3F4F6;display:flex;align-items:center;justify-content:center;border:3px dashed #B9C2CC;">
                        <i class="fa fa-plus" style="font-size:44px;color:#8A939E;"></i>
                    </span>
                    <div style="margin-top:10px;color:#566F7B;font-weight:600;">Adicionar filho</div>
                </a>
            </div>
        <?php endif; ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>
