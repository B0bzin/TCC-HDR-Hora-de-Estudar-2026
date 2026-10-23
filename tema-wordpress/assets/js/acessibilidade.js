/**
 * EPA — Acessibilidade: controle de tamanho de fonte.
 * Ajusta o tamanho base do site e guarda a preferência do usuário.
 */
(function () {
    "use strict";

    var HTML  = document.documentElement;
    var CHAVE = 'epa_font_scale';
    var NIVEIS = [100, 112, 125, 138]; // percentuais do tamanho base

    // Recupera o nível salvo
    var nivel = parseInt(localStorage.getItem(CHAVE), 10);
    if (isNaN(nivel) || nivel < 0 || nivel >= NIVEIS.length) nivel = 0;

    // Aplica imediatamente (evita "piscar" com o tamanho errado)
    HTML.style.fontSize = NIVEIS[nivel] + '%';

    function aplicar() {
        HTML.style.fontSize = NIVEIS[nivel] + '%';
        localStorage.setItem(CHAVE, nivel);
        var ind = document.querySelector('.epa-acess-nivel');
        if (ind) {
            ind.textContent = (nivel === 0) ? 'Tamanho padrão' : '+' + (NIVEIS[nivel] - 100) + '%';
        }
    }

    function aumentar() { if (nivel < NIVEIS.length - 1) { nivel++; aplicar(); } }
    function diminuir() { if (nivel > 0)                 { nivel--; aplicar(); } }
    function resetar()  { nivel = 0; aplicar(); }

    document.addEventListener('DOMContentLoaded', function () {
        var toggle = document.querySelector('.epa-acess-toggle');
        var painel = document.querySelector('.epa-acess-painel');

        if (toggle && painel) {
            toggle.addEventListener('click', function () {
                var aberto = painel.classList.toggle('aberto');
                toggle.setAttribute('aria-expanded', aberto ? 'true' : 'false');
            });
            // Fecha ao clicar fora
            document.addEventListener('click', function (e) {
                if (!e.target.closest('.epa-acessibilidade')) {
                    painel.classList.remove('aberto');
                    toggle.setAttribute('aria-expanded', 'false');
                }
            });
        }

        var bMais  = document.querySelector('.epa-acess-mais');
        var bMenos = document.querySelector('.epa-acess-menos');
        var bReset = document.querySelector('.epa-acess-reset');
        if (bMais)  bMais.addEventListener('click', aumentar);
        if (bMenos) bMenos.addEventListener('click', diminuir);
        if (bReset) bReset.addEventListener('click', resetar);

        aplicar(); // atualiza o indicador de nível
    });
})();
