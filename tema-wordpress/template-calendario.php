<?php
/**
 * Template Name: Hora de Estudar - Meu Calendário
 * Calendário pessoal do usuário, com link para o Google Agenda.
 * @package Hora de Estudar
 */
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();
?>

<section class="slider_section">
    <div class="container py-5">
        <div class="col-md-9"><div class="detail-box">
            <h1>Meu Calendário</h1>
            <p>Organize seus estudos: adicione datas importantes e leve-as para o Google Agenda.</p>
        </div></div>
    </div>
</section>

<div class="container-xxl py-5">
    <div class="container">
    <?php if ( ! is_user_logged_in() ) : ?>
        <div class="col-lg-8 mx-auto">
            <div class="register-section text-center">
                <i class="fa fa-calendar-alt fa-3x mb-3" style="color:#1A77D2;"></i>
                <h2 class="mb-3">Faça login para usar seu calendário</h2>
                <a class="btn btnDefault py-2 px-4" href="<?php echo esc_url( wp_login_url( epa_url_pagina( 'meu-calendario' ) ) ); ?>">Entrar</a>
            </div>
        </div>
    <?php else :
        $user_id = get_current_user_id();
        // Perfil ativo (para filtrar o calendário por filho)
        $hde_filhos_cal  = function_exists( 'hde_get_filhos' ) ? hde_get_filhos( $user_id ) : array();
        $hde_perfil_cal  = function_exists( 'hde_perfil_ativo' ) ? hde_perfil_ativo() : null;
        $hde_filho_ativo = ( $hde_perfil_cal && $hde_perfil_cal['tipo'] === 'filho' ) ? $hde_perfil_cal['id'] : '';
        // Mês exibido
        $mes = ( isset( $_GET['mes'] ) && preg_match( '/^\d{4}-\d{2}$/', $_GET['mes'] ) ) ? $_GET['mes'] : date( 'Y-m' );
        $primeiro   = strtotime( $mes . '-01' );
        $dias_mes   = (int) date( 't', $primeiro );
        $inicio_sem = (int) date( 'w', $primeiro );
        $mes_ant    = date( 'Y-m', strtotime( $mes . '-01 -1 month' ) );
        $mes_prox   = date( 'Y-m', strtotime( $mes . '-01 +1 month' ) );
        $meses_pt = array( 1=>'Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro' );
        $nome_mes = $meses_pt[ (int) date( 'n', $primeiro ) ] . ' de ' . date( 'Y', $primeiro );

        // Eventos do mês, agrupados por dia
        $eventos = epa_eventos_usuario( $user_id, $mes );
        // Filtra pelo filho ativo (mantém também os eventos gerais)
        if ( $hde_filho_ativo ) {
            $eventos = array_values( array_filter( $eventos, function ( $ev ) use ( $hde_filho_ativo ) {
                $ef = get_post_meta( $ev->ID, '_hde_filho', true );
                return ( $ef === '' || $ef === $hde_filho_ativo );
            } ) );
        }
        $por_dia = array();
        foreach ( $eventos as $ev ) {
            $d = get_post_meta( $ev->ID, '_epa_data', true );
            $por_dia[ (int) substr( $d, 8, 2 ) ][] = $ev;
        }
        $hoje = date( 'Y-m-d' );
        $url_cal = epa_url_pagina( 'meu-calendario' );

        if ( isset( $_GET['evento'] ) ) {
            if ( $_GET['evento'] === 'ok' )        echo '<div class="epa-notice epa-notice-success"><i class="fa fa-check-circle me-2"></i>Evento adicionado!</div>';
            elseif ( $_GET['evento'] === 'removido' ) echo '<div class="epa-notice epa-notice-success"><i class="fa fa-trash me-2"></i>Evento removido.</div>';
            else echo '<div class="epa-notice epa-notice-error"><i class="fa fa-exclamation-circle me-2"></i>Informe um título e uma data válidos.</div>';
        }
        ?>
        <?php if ( $hde_filho_ativo && $hde_perfil_cal ) : ?>
            <div class="epa-notice" style="background:#E3F2FD;border-left:4px solid #1A77D2;"><i class="fa fa-child me-2"></i>Exibindo o calendário de <strong><?php echo esc_html( $hde_perfil_cal['nome'] ); ?></strong> (mais os eventos gerais).</div>
        <?php endif; ?>
        <div class="row g-5">
            <!-- Calendário -->
            <div class="col-lg-8">
                <div class="epa-cal">
                    <div class="epa-cal-topo">
                        <a class="epa-cal-nav" href="<?php echo esc_url( add_query_arg( 'mes', $mes_ant, $url_cal ) ); ?>" aria-label="Mês anterior"><i class="fa fa-chevron-left"></i></a>
                        <strong class="epa-cal-mes"><?php echo esc_html( $nome_mes ); ?></strong>
                        <a class="epa-cal-nav" href="<?php echo esc_url( add_query_arg( 'mes', $mes_prox, $url_cal ) ); ?>" aria-label="Próximo mês"><i class="fa fa-chevron-right"></i></a>
                    </div>
                    <table class="epa-cal-grade">
                        <thead><tr>
                            <?php foreach ( array('Dom','Seg','Ter','Qua','Qui','Sex','Sáb') as $ds ) echo '<th>' . $ds . '</th>'; ?>
                        </tr></thead>
                        <tbody><tr>
                        <?php
                        for ( $i = 0; $i < $inicio_sem; $i++ ) echo '<td class="vazio"></td>';
                        $col = $inicio_sem;
                        for ( $dia = 1; $dia <= $dias_mes; $dia++ ) {
                            $data_dia = sprintf( '%s-%02d', $mes, $dia );
                            $classe = 'epa-dia';
                            if ( $data_dia === $hoje )         $classe .= ' hoje';
                            if ( isset( $por_dia[ $dia ] ) )   $classe .= ' tem-evento';
                            echo '<td class="' . $classe . '">';
                            echo '<span class="epa-num">' . $dia . '</span>';
                            if ( isset( $por_dia[ $dia ] ) ) {
                                echo '<span class="epa-marca" title="' . count( $por_dia[ $dia ] ) . ' evento(s)"></span>';
                            }
                            echo '</td>';
                            $col++;
                            if ( $col % 7 === 0 && $dia < $dias_mes ) echo '</tr><tr>';
                        }
                        while ( $col % 7 !== 0 ) { echo '<td class="vazio"></td>'; $col++; }
                        ?>
                        </tr></tbody>
                    </table>
                    <p class="epa-cal-legenda"><span class="epa-marca"></span> dia com evento &nbsp;·&nbsp; <span class="epa-hoje-leg"></span> hoje</p>
                </div>

                <!-- Lista de eventos do mês -->
                <div id="lista" class="mt-4">
                    <h3 class="mb-3">Eventos de <?php echo esc_html( $nome_mes ); ?></h3>
                    <?php if ( $eventos ) : foreach ( $eventos as $ev ) :
                        $d = get_post_meta( $ev->ID, '_epa_data', true );
                        $h = get_post_meta( $ev->ID, '_epa_hora', true );
                        $link_g = epa_link_google_agenda( $ev->post_title, $d, $h, $ev->post_content );
                        $del = wp_nonce_url( admin_url( 'admin-post.php?action=epa_del_evento&id=' . $ev->ID ), 'epa_del_evento' );
                    ?>
                        <div class="epa-evento-item">
                            <div class="epa-evento-data">
                                <span class="epa-evento-dia"><?php echo esc_html( date( 'd', strtotime( $d ) ) ); ?></span>
                                <span class="epa-evento-mes"><?php echo esc_html( strtoupper( substr( $meses_pt[ (int) date( 'n', strtotime( $d ) ) ], 0, 3 ) ) ); ?></span>
                            </div>
                            <div class="epa-evento-info">
                                <strong><?php echo esc_html( $ev->post_title ); ?></strong>
                                <?php if ( $h ) echo '<span class="epa-evento-hora"><i class="fa fa-clock me-1"></i>' . esc_html( $h ) . '</span>'; ?>
                                <?php $ev_filho = get_post_meta( $ev->ID, '_hde_filho', true );
                                if ( $ev_filho && function_exists( 'hde_get_filho' ) && ( $cf = hde_get_filho( $user_id, $ev_filho ) ) ) {
                                    echo '<span class="epa-evento-hora" style="background:#FFF3CD;color:#8A6D1A;"><i class="fa fa-child me-1"></i>' . esc_html( $cf['nome'] ) . '</span>';
                                } ?>
                                <?php if ( $ev->post_content ) echo '<p>' . esc_html( $ev->post_content ) . '</p>'; ?>
                                <div class="epa-evento-acoes">
                                    <a class="epa-btn-google" href="<?php echo esc_url( $link_g ); ?>" target="_blank" rel="noopener">
                                        <i class="fab fa-google me-1"></i>Adicionar ao Google Agenda
                                    </a>
                                    <a class="epa-btn-del" href="<?php echo esc_url( $del ); ?>" onclick="return confirm('Remover este evento?');"><i class="fa fa-trash"></i> Remover</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; else : ?>
                        <p style="color:#566F7B;">Nenhum evento neste mês. Adicione um ao lado.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Formulário novo evento -->
            <div class="col-lg-4">
                <div class="register-section">
                    <h3 class="mb-3">Novo evento</h3>
                    <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
                        <input type="hidden" name="action" value="epa_evento">
                        <?php wp_nonce_field( 'epa_evento', 'epa_evento_nonce' ); ?>
                        <div class="mb-3">
                            <label class="epa-label-coment" for="ev_titulo">Título *</label>
                            <input type="text" id="ev_titulo" name="epa_ev_titulo" class="form-control formDefault" required placeholder="Ex: Prova de matemática">
                        </div>
                        <div class="mb-3">
                            <label class="epa-label-coment" for="ev_data">Data *</label>
                            <input type="date" id="ev_data" name="epa_ev_data" class="form-control formDefault" required value="<?php echo esc_attr( date( 'Y-m-d' ) ); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="epa-label-coment" for="ev_hora">Horário (opcional)</label>
                            <input type="time" id="ev_hora" name="epa_ev_hora" class="form-control formDefault">
                        </div>
                        <div class="mb-3">
                            <label class="epa-label-coment" for="ev_desc">Descrição (opcional)</label>
                            <textarea id="ev_desc" name="epa_ev_desc" class="form-control formDefault" rows="2" placeholder="Detalhes..."></textarea>
                        </div>
                        <?php if ( ! empty( $hde_filhos_cal ) ) : ?>
                        <div class="mb-3">
                            <label class="epa-label-coment" for="ev_filho">Para qual filho?</label>
                            <select id="ev_filho" name="epa_ev_filho" class="form-control formDefault">
                                <option value="">Todos / geral</option>
                                <?php foreach ( $hde_filhos_cal as $cf ) : ?>
                                    <option value="<?php echo esc_attr( $cf['id'] ); ?>" <?php selected( $hde_filho_ativo, $cf['id'] ); ?>><?php echo esc_html( $cf['nome'] ); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php endif; ?>
                        <button type="submit" class="btn btnDefault w-100 py-2"><i class="fa fa-plus me-2"></i>Adicionar evento</button>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>
