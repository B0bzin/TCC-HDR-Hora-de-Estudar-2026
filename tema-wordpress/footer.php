<?php
/**
 * Rodapé do tema — footer, botão voltar ao topo e scripts.
 * @package Hora de Estudar
 */
if ( ! defined( 'ABSPATH' ) ) exit;
?>
    </main><!-- #conteudo -->

    <!-- Footer -->
    <footer class="container-fluid background_footer footer pt-5 mt-5">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-4 col-md-12 text-center">
                    <h3 class="mb-3"><?php bloginfo( 'name' ); ?></h3>
                    <img class="img_logo_footer" src="<?php echo esc_url( epa_logo_url() ); ?>" alt="Logo Hora de Estudar">
                </div>
                <div class="col-lg-4 col-md-12 text-center">
                    <h3 class="mb-4">Fale com a gente</h3>
                    <p class="mb-2"><i class="fa fa-envelope me-3"></i><?php echo esc_html( get_option( 'admin_email' ) ); ?></p>
                </div>
                <div class="col-lg-4 col-md-12 text-center">
                    <h3 class="mb-4">Links rápidos</h3>
                    <?php epa_links_rodape(); ?>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="copyright text-center">
                <?php bloginfo( 'name' ); ?>. Instituto Federal do Paraná, Campus Pinhais. <?php echo esc_html( date( 'Y' ) ); ?>.<br>
                <span style="font-size:0.75rem;">Projeto de TCC do curso Técnico em Informática</span>
            </div>
        </div>
    </footer>

    <a href="#" class="btn btn-lg back-to-top" aria-label="Voltar ao topo da página" title="Voltar ao topo"><i class="bi bi-arrow-up" aria-hidden="true"></i></a>

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
</div>

<?php wp_footer(); ?>
</body>
</html>
