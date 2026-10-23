<?php
/**
 * Template Name: Hora de Estudar - Gestão de Usuários
 * Painel visível apenas para administradores.
 * @package Hora de Estudar
 */
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();

$url = hde_url_gestao();
?>

<style>
.hde-gestao .register-section { margin-bottom: 24px; max-width: none; width: 100%; }
.hde-tab { display:inline-block; border-radius:20px; padding:3px 12px; font-size:.78rem; font-weight:700; }
.hde-tab-ok { background:#E8F5E9; color:#2E7D32; }
.hde-tab-no { background:#FFF3CD; color:#8A6D1A; }
.hde-tabela { width:100%; border-collapse:collapse; }
.hde-tabela th, .hde-tabela td { padding:10px 12px; border-bottom:1px solid #E1E8F0; text-align:left; vertical-align:middle; font-size:.92rem; }
.hde-tabela th { color:#0D4E93; font-size:.8rem; text-transform:uppercase; letter-spacing:.4px; }
.hde-tabela tr:hover td { background:#F5F9FF; }
.hde-av { width:40px; height:40px; border-radius:50%; object-fit:cover; vertical-align:middle; margin-right:8px; border:2px solid #E3F2FD; }
.hde-acoes a { display:inline-flex; align-items:center; gap:4px; font-size:.82rem; font-weight:700; text-decoration:none; padding:5px 10px; border-radius:8px; margin:2px 2px; }
.hde-ver { color:#1A77D2; background:#E3F2FD; }
.hde-edit { color:#8A6D1A; background:#FFF8E1; }
.hde-del { color:#C62828; background:#FFEBEE; }
.hde-acoes a:hover { filter:brightness(.95); }
.hde-busca-wrap { position:relative; max-width:520px; }
.hde-busca-wrap i { position:absolute; left:16px; top:50%; transform:translateY(-50%); color:#1A77D2; }
.hde-busca-wrap input { width:100%; padding:12px 16px 12px 42px; border:2px solid #CFE0F5; border-radius:30px; font-size:1rem; }
.hde-table-scroll { overflow-x:auto; }
.hde-filho-box { border:1px solid #E1E8F0; border-radius:10px; padding:10px 14px; margin-bottom:10px; }
.hde-tabela td { word-break: break-word; }
/* Em telas médias/estreitas a tabela vira cartões empilhados (sem rolagem horizontal) */
@media (max-width: 991px) {
    .hde-table-scroll { overflow-x: visible; }
    .hde-tabela thead { display: none; }
    .hde-tabela, .hde-tabela tbody, .hde-tabela tr, .hde-tabela td { display: block; width: 100%; }
    .hde-tabela tr { border: 1px solid #E1E8F0; border-radius: 12px; margin-bottom: 14px; padding: 6px 2px; }
    .hde-tabela tr:hover td { background: transparent; }
    .hde-tabela td { border: none; padding: 7px 14px; word-break: break-word; }
    .hde-tabela td::before { content: attr(data-label); display: block; font-size: .68rem; text-transform: uppercase; letter-spacing: .4px; color: #8A939E; font-weight: 700; margin-bottom: 2px; }
    .hde-tabela td.hde-acoes { white-space: normal !important; }
}
</style>

<section class="slider_section">
    <div class="container py-5">
        <div class="col-md-9"><div class="detail-box">
            <h1><i class="fa fa-users-cog me-2" aria-hidden="true"></i>Gestão de Usuários</h1>
            <p>Área restrita aos administradores para gerenciar contas e moderar os comentários dos vídeos.</p>
        </div></div>
    </div>
</section>

<div class="container-xxl py-5 hde-gestao">
    <div class="container">
    <?php if ( ! hde_admin_pode() ) : ?>
        <div class="col-lg-8 mx-auto">
            <div class="register-section text-center">
                <i class="fa fa-lock fa-3x mb-3" style="color:#C62828;"></i>
                <h2 class="mb-3">Acesso restrito</h2>
                <p>Esta página é exclusiva para administradores do site.</p>
                <a class="btn btnDefault py-2 px-4 mt-2" href="<?php echo esc_url( home_url( '/' ) ); ?>">Voltar para a página inicial</a>
            </div>
        </div>
    <?php else :

        // Mensagens de retorno
        $msg = isset( $_GET['msg'] ) ? sanitize_text_field( $_GET['msg'] ) : '';
        $avisos = array(
            'salvo'           => array( 'ok',  'Usuário atualizado com sucesso.' ),
            'excluido'        => array( 'ok',  'Usuário excluído.' ),
            'coment_salvo'    => array( 'ok',  'Comentário atualizado.' ),
            'coment_excluido' => array( 'ok',  'Comentário excluído.' ),
            'erro'            => array( 'err', 'Não foi possível concluir a ação (talvez não seja permitido para essa conta).' ),
        );
        if ( isset( $avisos[ $msg ] ) ) {
            $tipo = $avisos[ $msg ][0] === 'ok' ? 'epa-notice-success' : 'epa-notice-error';
            $ico  = $avisos[ $msg ][0] === 'ok' ? 'fa-check-circle' : 'fa-exclamation-circle';
            echo '<div class="epa-notice ' . $tipo . '"><i class="fa ' . $ico . ' me-2"></i>' . esc_html( $avisos[ $msg ][1] ) . '</div>';
        }

        /* ============================================================
         * MODO 1: EDITAR USUÁRIO
         * ========================================================= */
        if ( isset( $_GET['editar'] ) ) :
            $uid = intval( $_GET['editar'] );
            $u   = get_userdata( $uid );
            if ( ! $u ) : echo '<div class="epa-notice epa-notice-error">Usuário não encontrado.</div>';
            else :
                $filhos = function_exists( 'hde_get_filhos' ) ? hde_get_filhos( $uid ) : array();
            ?>
            <div class="register-section col-lg-9 mx-auto">
                <h2 class="mb-1">Editar usuário #<?php echo (int) $uid; ?></h2>
                <p style="color:#546E7A;">Login <strong><?php echo esc_html( $u->user_login ); ?></strong></p>
                <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
                    <input type="hidden" name="action" value="hde_admin_user">
                    <input type="hidden" name="uid" value="<?php echo (int) $uid; ?>">
                    <?php wp_nonce_field( 'hde_admin_user', 'hde_admin_nonce' ); ?>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="epa-label-coment">Nome</label>
                            <input type="text" name="nome" class="form-control formDefault" value="<?php echo esc_attr( $u->first_name ); ?>">
                        </div>
                        <div class="col-sm-6">
                            <label class="epa-label-coment">Apelido (aparece no site)</label>
                            <input type="text" name="apelido" class="form-control formDefault" value="<?php echo esc_attr( $u->display_name ); ?>">
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="epa-label-coment">E-mail</label>
                        <input type="email" name="email" class="form-control formDefault" value="<?php echo esc_attr( $u->user_email ); ?>">
                    </div>
                    <div class="mt-3">
                        <label class="epa-label-coment">Descrição do usuário (sobre você)</label>
                        <textarea name="bio" class="form-control formDefault" rows="3"><?php echo esc_textarea( $u->description ); ?></textarea>
                    </div>

                    <?php if ( $filhos ) : ?>
                        <hr class="my-4">
                        <h3 class="mb-3" style="font-size:1.1rem;color:#0D4E93;">Observações dos filhos</h3>
                        <?php foreach ( $filhos as $f ) : ?>
                            <div class="hde-filho-box">
                                <strong style="color:#0D4E93;"><i class="fa fa-child me-1"></i><?php echo esc_html( $f['nome'] ); ?></strong>
                                <?php
                                $meta = array();
                                if ( ! empty( $f['idade'] ) )  $meta[] = esc_html( $f['idade'] ) . ' anos';
                                if ( ! empty( $f['serie'] ) )  $meta[] = esc_html( $f['serie'] );
                                if ( ! empty( $f['escola'] ) ) $meta[] = esc_html( $f['escola'] );
                                if ( $meta ) echo ' <small style="color:#8A939E;">(' . implode( ', ', $meta ) . ')</small>';
                                ?>
                                <textarea name="obs[<?php echo esc_attr( $f['id'] ); ?>]" class="form-control formDefault mt-2" rows="2" placeholder="Observações..."><?php echo esc_textarea( $f['obs'] ?? '' ); ?></textarea>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <div class="mt-4">
                        <button type="submit" class="btn btnDefault py-2 px-4"><i class="fa fa-save me-2"></i>Salvar alterações</button>
                        <a class="btn py-2 px-4" style="background:#ECEFF1;color:#546E7A;" href="<?php echo esc_url( add_query_arg( 'ver', $uid, $url ) ); ?>">Cancelar</a>
                    </div>
                </form>
            </div>
            <?php
            endif;

        /* ============================================================
         * MODO 2: VISUALIZAR USUÁRIO
         * ========================================================= */
        elseif ( isset( $_GET['ver'] ) ) :
            $uid = intval( $_GET['ver'] );
            $u   = get_userdata( $uid );
            if ( ! $u ) : echo '<div class="epa-notice epa-notice-error">Usuário não encontrado.</div>';
            else :
                $filhos = function_exists( 'hde_get_filhos' ) ? hde_get_filhos( $uid ) : array();
                $avatar = function_exists( 'epa_avatar_url' ) ? epa_avatar_url( $uid ) : '';
            ?>
            <div class="register-section col-lg-9 mx-auto">
                <div class="d-flex align-items-center mb-3" style="gap:14px;">
                    <?php if ( $avatar ) : ?><img src="<?php echo esc_url( $avatar ); ?>" alt="" class="hde-av" style="width:64px;height:64px;"><?php endif; ?>
                    <div>
                        <h2 class="mb-0" style="color:#0D4E93;"><?php echo esc_html( $u->display_name ); ?></h2>
                        <small style="color:#546E7A;">ID #<?php echo (int) $uid; ?> &middot; login <?php echo esc_html( $u->user_login ); ?></small>
                    </div>
                </div>
                <table class="hde-tabela" style="margin-bottom:16px;">
                    <tr><th style="width:170px;">Nome</th><td><?php echo esc_html( $u->first_name ?: '(não informado)' ); ?></td></tr>
                    <tr><th>E-mail</th><td><?php echo esc_html( $u->user_email ); ?></td></tr>
                    <tr><th>Papel</th><td><?php echo esc_html( implode( ', ', $u->roles ) ); ?></td></tr>
                    <tr><th>Cadastrado em</th><td><?php echo esc_html( date_i18n( 'd/m/Y H:i', strtotime( $u->user_registered ) ) ); ?></td></tr>
                    <tr><th>Descrição</th><td><?php echo $u->description ? nl2br( esc_html( $u->description ) ) : '<em style="color:#8A939E;">(sem descrição)</em>'; ?></td></tr>
                </table>

                <h3 style="font-size:1.1rem;color:#0D4E93;"><i class="fa fa-child me-1"></i>Filhos (<?php echo count( $filhos ); ?>)</h3>
                <?php if ( ! $filhos ) : ?>
                    <p style="color:#8A939E;">Este usuário não cadastrou filhos.</p>
                <?php else : foreach ( $filhos as $f ) : ?>
                    <div class="hde-filho-box">
                        <strong style="color:#0D4E93;"><?php echo esc_html( $f['nome'] ); ?></strong>
                        <?php
                        $meta = array();
                        if ( ! empty( $f['idade'] ) )  $meta[] = esc_html( $f['idade'] ) . ' anos';
                        if ( ! empty( $f['serie'] ) )  $meta[] = esc_html( $f['serie'] );
                        if ( ! empty( $f['escola'] ) ) $meta[] = esc_html( $f['escola'] );
                        if ( ! empty( $f['email'] ) )  $meta[] = esc_html( $f['email'] );
                        if ( $meta ) echo '<br><small style="color:#566F7B;">' . implode( ' · ', $meta ) . '</small>';
                        ?>
                        <div class="mt-1" style="font-size:.9rem;">
                            <strong style="color:#8A6D1A;">Observações:</strong>
                            <?php echo ! empty( $f['obs'] ) ? esc_html( $f['obs'] ) : '<em style="color:#8A939E;">nenhuma</em>'; ?>
                        </div>
                    </div>
                <?php endforeach; endif; ?>

                <div class="mt-4">
                    <a class="btn btnDefault py-2 px-4" href="<?php echo esc_url( add_query_arg( 'editar', $uid, $url ) ); ?>"><i class="fa fa-edit me-2"></i>Editar</a>
                    <a class="btn py-2 px-4" style="background:#ECEFF1;color:#546E7A;" href="<?php echo esc_url( $url ); ?>"><i class="fa fa-arrow-left me-2"></i>Voltar à lista</a>
                </div>
            </div>
            <?php
            endif;

        /* ============================================================
         * MODO 3: EDITAR COMENTÁRIO
         * ========================================================= */
        elseif ( isset( $_GET['editar_coment'] ) ) :
            $cid = intval( $_GET['editar_coment'] );
            $c   = get_comment( $cid );
            if ( ! $c ) : echo '<div class="epa-notice epa-notice-error">Comentário não encontrado.</div>';
            else : ?>
            <div class="register-section col-lg-9 mx-auto">
                <h2 class="mb-1">Editar comentário #<?php echo (int) $cid; ?></h2>
                <p style="color:#546E7A;">Por <strong><?php echo esc_html( $c->comment_author ); ?></strong> no vídeo
                    <a href="<?php echo esc_url( get_permalink( $c->comment_post_ID ) ); ?>" target="_blank"><?php echo esc_html( get_the_title( $c->comment_post_ID ) ); ?></a>
                </p>
                <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
                    <input type="hidden" name="action" value="hde_admin_coment">
                    <input type="hidden" name="cid" value="<?php echo (int) $cid; ?>">
                    <?php wp_nonce_field( 'hde_admin_coment', 'hde_coment_nonce' ); ?>
                    <label class="epa-label-coment">Texto do comentário</label>
                    <textarea name="conteudo" class="form-control formDefault" rows="5"><?php echo esc_textarea( $c->comment_content ); ?></textarea>
                    <div class="mt-3">
                        <button type="submit" class="btn btnDefault py-2 px-4"><i class="fa fa-save me-2"></i>Salvar</button>
                        <a class="btn py-2 px-4" style="background:#ECEFF1;color:#546E7A;" href="<?php echo esc_url( add_query_arg( 'aba', 'comentarios', $url ) ); ?>#comentarios">Cancelar</a>
                    </div>
                </form>
            </div>
            <?php endif;

        /* ============================================================
         * MODO PADRÃO: LISTA + BUSCA + COMENTÁRIOS
         * ========================================================= */
        else :
            $busca = isset( $_GET['busca'] ) ? sanitize_text_field( wp_unslash( $_GET['busca'] ) ) : '';
            if ( $busca !== '' && ctype_digit( $busca ) ) {
                $ud = get_userdata( intval( $busca ) );
                $usuarios = $ud ? array( $ud ) : array();
            } else {
                $args = array( 'number' => 300, 'orderby' => 'ID', 'order' => 'ASC' );
                if ( $busca !== '' ) {
                    $args['search'] = '*' . $busca . '*';
                    $args['search_columns'] = array( 'user_login', 'user_email', 'user_nicename', 'display_name' );
                }
                $usuarios = get_users( $args );
            }
            ?>
            <!-- USUÁRIOS -->
            <div class="register-section">
                <h2 class="mb-3"><i class="fa fa-users me-2"></i>Usuários (<?php echo count( $usuarios ); ?>)</h2>
                <form method="get" action="<?php echo esc_url( $url ); ?>" class="mb-4">
                    <div class="hde-busca-wrap">
                        <i class="fa fa-search" aria-hidden="true"></i>
                        <input type="text" name="busca" value="<?php echo esc_attr( $busca ); ?>" placeholder="Buscar por ID, nome, apelido ou e-mail...">
                    </div>
                </form>

                <?php if ( ! $usuarios ) : ?>
                    <p style="color:#8A939E;">Nenhum usuário encontrado<?php echo $busca ? ' para "' . esc_html( $busca ) . '"' : ''; ?>.</p>
                <?php else : ?>
                <div class="hde-table-scroll">
                <table class="hde-tabela">
                    <thead><tr>
                        <th>ID</th><th>Usuário</th><th>E-mail</th><th>Filhos</th><th>Ações</th>
                    </tr></thead>
                    <tbody>
                    <?php foreach ( $usuarios as $u ) :
                        $uid    = $u->ID;
                        $av     = function_exists( 'epa_avatar_url' ) ? epa_avatar_url( $uid ) : '';
                        $nf     = function_exists( 'hde_get_filhos' ) ? count( hde_get_filhos( $uid ) ) : 0;
                        $eh_admin = user_can( $uid, 'manage_options' );
                        $sou_eu   = ( $uid === get_current_user_id() );
                        $del    = wp_nonce_url( admin_url( 'admin-post.php?action=hde_del_user&uid=' . $uid ), 'hde_del_user' );
                    ?>
                        <tr>
                            <td data-label="ID"><strong>#<?php echo (int) $uid; ?></strong></td>
                            <td data-label="Usuário">
                                <?php if ( $av ) : ?><img src="<?php echo esc_url( $av ); ?>" alt="" class="hde-av"><?php endif; ?>
                                <?php echo esc_html( $u->display_name ); ?>
                                <?php if ( $eh_admin ) echo ' <span class="hde-tab hde-tab-no">admin</span>'; ?>
                                <br><small style="color:#8A939E;"><?php echo esc_html( $u->user_login ); ?></small>
                            </td>
                            <td data-label="E-mail"><?php echo esc_html( $u->user_email ); ?></td>
                            <td data-label="Filhos"><?php echo $nf ? '<span class="hde-tab hde-tab-ok">' . (int) $nf . '</span>' : '<span style="color:#B0BEC5;">0</span>'; ?></td>
                            <td class="hde-acoes" data-label="Ações" style="white-space:nowrap;">
                                <a class="hde-ver" href="<?php echo esc_url( add_query_arg( 'ver', $uid, $url ) ); ?>"><i class="fa fa-eye"></i>Ver</a>
                                <a class="hde-edit" href="<?php echo esc_url( add_query_arg( 'editar', $uid, $url ) ); ?>"><i class="fa fa-edit"></i>Editar</a>
                                <?php if ( ! $sou_eu && ! $eh_admin ) : ?>
                                    <a class="hde-del" href="<?php echo esc_url( $del ); ?>" onclick="return confirm('Excluir definitivamente o usuário <?php echo esc_js( $u->display_name ); ?>? Esta ação não pode ser desfeita.');"><i class="fa fa-trash"></i>Excluir</a>
                                <?php else : ?>
                                    <span style="color:#B0BEC5;font-size:.8rem;" title="Não é possível excluir administradores nem você mesmo"><i class="fa fa-lock"></i></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                </div>
                <?php endif; ?>
            </div>

            <!-- COMENTÁRIOS DOS VÍDEOS -->
            <div class="register-section" id="comentarios">
                <h2 class="mb-3"><i class="fa fa-comments me-2"></i>Comentários dos vídeos</h2>
                <?php
                $comentarios = get_comments( array(
                    'post_type' => 'epa_video',
                    'status'    => 'all',
                    'number'    => 200,
                    'orderby'   => 'comment_date',
                    'order'     => 'DESC',
                ) );
                if ( ! $comentarios ) : ?>
                    <p style="color:#8A939E;">Ainda não há comentários nos vídeos.</p>
                <?php else : ?>
                <div class="hde-table-scroll">
                <table class="hde-tabela">
                    <thead><tr><th>Autor</th><th>Vídeo</th><th>Comentário</th><th>Status</th><th>Ações</th></tr></thead>
                    <tbody>
                    <?php foreach ( $comentarios as $c ) :
                        $cid = $c->comment_ID;
                        $del = wp_nonce_url( admin_url( 'admin-post.php?action=hde_del_coment&cid=' . $cid ), 'hde_del_coment' );
                        $aprovado = ( (string) $c->comment_approved === '1' );
                    ?>
                        <tr>
                            <td data-label="Autor"><strong><?php echo esc_html( $c->comment_author ?: 'Anônimo' ); ?></strong><br><small style="color:#8A939E;"><?php echo esc_html( date_i18n( 'd/m/Y H:i', strtotime( $c->comment_date ) ) ); ?></small></td>
                            <td data-label="Vídeo"><a href="<?php echo esc_url( get_permalink( $c->comment_post_ID ) ); ?>" target="_blank" style="color:#0D4E93;"><?php echo esc_html( wp_trim_words( get_the_title( $c->comment_post_ID ), 6 ) ); ?></a></td>
                            <td data-label="Comentário" style="max-width:360px;"><?php echo esc_html( wp_trim_words( $c->comment_content, 24 ) ); ?></td>
                            <td data-label="Status"><?php echo $aprovado ? '<span class="hde-tab hde-tab-ok">aprovado</span>' : '<span class="hde-tab hde-tab-no">pendente</span>'; ?></td>
                            <td class="hde-acoes" data-label="Ações" style="white-space:nowrap;">
                                <a class="hde-edit" href="<?php echo esc_url( add_query_arg( 'editar_coment', $cid, $url ) ); ?>"><i class="fa fa-edit"></i>Editar</a>
                                <a class="hde-del" href="<?php echo esc_url( $del ); ?>" onclick="return confirm('Excluir este comentário definitivamente?');"><i class="fa fa-trash"></i>Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                </div>
                <?php endif; ?>
            </div>

        <?php endif; // modos ?>
    <?php endif; // admin ?>
    </div>
</div>

<?php get_footer(); ?>
