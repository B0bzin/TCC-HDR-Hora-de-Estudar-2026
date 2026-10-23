<?php
/**
 * Template Name: Hora de Estudar - Meus Filhos
 * Gerência dos perfis de filhos pelo responsável.
 * @package Hora de Estudar
 */
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();
?>

<section class="slider_section">
    <div class="container py-5">
        <div class="col-md-9"><div class="detail-box">
            <h1>Meus Filhos</h1>
            <p>Cadastre os perfis dos seus filhos para acompanhar os estudos de cada um.</p>
        </div></div>
    </div>
</section>

<div class="container-xxl py-5">
    <div class="container">
        <div class="col-lg-8 mx-auto">
        <?php if ( ! is_user_logged_in() ) : ?>
            <div class="register-section text-center">
                <i class="fa fa-user-lock fa-3x mb-3" style="color:#1A77D2;"></i>
                <h2 class="mb-3">Faça login para gerenciar os perfis dos seus filhos</h2>
                <a class="btn btnDefault py-2 px-4" href="<?php echo esc_url( wp_login_url( epa_url_pagina( 'meus-filhos' ) ) ); ?>">Entrar</a>
            </div>
        <?php else :
            $uid    = get_current_user_id();
            $filhos = hde_get_filhos( $uid );
            if ( isset( $_GET['filho'] ) && $_GET['filho'] === 'ok' ) : ?>
                <div class="epa-notice epa-notice-success"><i class="fa fa-check-circle me-2"></i>Perfil do filho adicionado!</div>
            <?php elseif ( isset( $_GET['filho'] ) && $_GET['filho'] === 'removido' ) : ?>
                <div class="epa-notice epa-notice-success"><i class="fa fa-check-circle me-2"></i>Perfil removido.</div>
            <?php elseif ( isset( $_GET['filho'] ) ) : ?>
                <div class="epa-notice epa-notice-error"><i class="fa fa-exclamation-circle me-2"></i>Informe ao menos o nome do filho.</div>
            <?php endif; ?>

            <!-- Adicionar filho -->
            <div class="register-section mb-4">
                <h2 class="mb-3">Adicionar filho</h2>
                <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
                    <input type="hidden" name="action" value="hde_add_filho">
                    <?php wp_nonce_field( 'hde_filho', 'hde_filho_nonce' ); ?>
                    <div class="row g-3">
                        <div class="col-sm-8">
                            <label class="epa-label-coment" for="hde_filho_nome">Nome do filho</label>
                            <input type="text" id="hde_filho_nome" name="hde_filho_nome" class="form-control formDefault" placeholder="Ex: Lucas" required>
                        </div>
                        <div class="col-sm-4">
                            <label class="epa-label-coment" for="hde_filho_idade">Idade</label>
                            <input type="number" id="hde_filho_idade" name="hde_filho_idade" class="form-control formDefault" placeholder="Ex: 8" min="1" max="18">
                        </div>
                    </div>
                    <div class="row g-3 mt-0">
                        <div class="col-sm-7">
                            <label class="epa-label-coment" for="hde_filho_email">E-mail do filho (opcional)</label>
                            <input type="email" id="hde_filho_email" name="hde_filho_email" class="form-control formDefault" placeholder="filho@email.com">
                        </div>
                        <div class="col-sm-5">
                            <label class="epa-label-coment" for="hde_filho_serie">Série / ano escolar</label>
                            <input type="text" id="hde_filho_serie" name="hde_filho_serie" class="form-control formDefault" placeholder="Ex: 3º ano">
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="epa-label-coment" for="hde_filho_escola">Escola / colégio</label>
                        <input type="text" id="hde_filho_escola" name="hde_filho_escola" class="form-control formDefault" placeholder="Nome da escola">
                    </div>
                    <div class="mt-3">
                        <label class="epa-label-coment" for="hde_filho_obs">Observações</label>
                        <textarea id="hde_filho_obs" name="hde_filho_obs" class="form-control formDefault" rows="2" placeholder="Ex: dificuldades, interesses, anotações..."></textarea>
                    </div>
                    <button type="submit" class="btn btnDefault py-2 px-4 mt-3"><i class="fa fa-plus me-2"></i>&nbsp;Adicionar</button>
                </form>
            </div>

            <!-- Lista de filhos -->
            <div class="register-section" id="lista">
                <h2 class="mb-3">Filhos cadastrados</h2>
                <?php if ( empty( $filhos ) ) : ?>
                    <p style="color:#566F7B;">Você ainda não cadastrou nenhum filho.</p>
                <?php else : ?>
                    <?php foreach ( $filhos as $f ) :
                        $del  = wp_nonce_url( add_query_arg( array( 'action' => 'hde_del_filho', 'fid' => $f['id'] ), admin_url( 'admin-post.php' ) ), 'hde_del_filho' );
                        $hist = hde_get_historico( $uid, $f['id'] ); ?>
                        <div style="border:1px solid #E1E8F0;border-radius:10px;padding:12px 16px;margin-bottom:10px;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center" style="gap:12px;">
                                    <span style="min-width:44px;height:44px;border-radius:50%;background:#E3F2FD;display:flex;align-items:center;justify-content:center;"><i class="fa fa-child" style="color:#1A77D2;font-size:20px;"></i></span>
                                    <div>
                                        <strong style="color:#0D4E93;"><?php echo esc_html( $f['nome'] ); ?></strong>
                                        <?php
                                        $meta = array();
                                        if ( ! empty( $f['idade'] ) )  $meta[] = esc_html( $f['idade'] ) . ' anos';
                                        if ( ! empty( $f['serie'] ) )  $meta[] = esc_html( $f['serie'] );
                                        if ( ! empty( $f['escola'] ) ) $meta[] = esc_html( $f['escola'] );
                                        if ( $meta ) echo '<br><small style="color:#566F7B;">' . implode( ' · ', $meta ) . '</small>';
                                        if ( ! empty( $f['email'] ) ) echo '<br><small style="color:#566F7B;"><i class="fa fa-envelope me-1"></i>' . esc_html( $f['email'] ) . '</small>';
                                        if ( ! empty( $f['obs'] ) )   echo '<br><small style="color:#8A939E;font-style:italic;">' . esc_html( $f['obs'] ) . '</small>';
                                        ?>
                                    </div>
                                </div>
                                <a href="<?php echo esc_url( $del ); ?>" class="btn btn-sm" style="color:#C62828;" onclick="return confirm('Remover o perfil de <?php echo esc_js( $f['nome'] ); ?>?');" aria-label="Remover filho"><i class="fa fa-trash"></i></a>
                            </div>
                            <details style="margin-top:10px;">
                                <summary style="cursor:pointer;color:#1A77D2;font-weight:600;font-size:0.9rem;"><i class="fa fa-history me-1"></i>Histórico (<?php echo count( $hist ); ?>)</summary>
                                <?php if ( empty( $hist ) ) : ?>
                                    <p style="color:#566F7B;font-size:0.88rem;margin:8px 0 0;">Nada assistido ainda.</p>
                                <?php else : ?>
                                    <ul style="margin:8px 0 0;padding-left:18px;">
                                    <?php foreach ( array_slice( $hist, 0, 10 ) as $it ) :
                                        $titulo = get_the_title( $it['post'] );
                                        if ( ! $titulo ) continue; ?>
                                        <li style="font-size:0.9rem;margin-bottom:4px;">
                                            <a href="<?php echo esc_url( get_permalink( $it['post'] ) ); ?>" style="color:#0D4E93;"><?php echo esc_html( $titulo ); ?></a>
                                            <small style="color:#8A939E;">(<?php echo esc_html( date_i18n( 'd/m H:i', strtotime( $it['data'] ) ) ); ?>)</small>
                                        </li>
                                    <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </details>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                <a href="<?php echo esc_url( epa_url_pagina( 'meu-perfil' ) ); ?>" class="btn btnDefault py-2 px-4 mt-2"><i class="fa fa-arrow-left me-2"></i>&nbsp;Voltar ao perfil</a>
            </div>
        <?php endif; ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>
