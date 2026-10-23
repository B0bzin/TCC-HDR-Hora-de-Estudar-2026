/**
 * Hora de Estudar — interações da conta.
 * - Abre/fecha o painel de notificações (sininho).
 * - Botão demonstrativo "Entrar com o Google".
 */
(function () {
    "use strict";

    // --- Notificações ---
    var btn = document.querySelector('.epa-notif-btn');
    var painel = document.querySelector('.epa-notif-painel');
    if ( btn && painel ) {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            var aberto = painel.hidden;
            painel.hidden = ! aberto;
            btn.setAttribute('aria-expanded', aberto ? 'true' : 'false');
        });
        document.addEventListener('click', function (e) {
            if ( ! e.target.closest('.epa-notif') ) {
                painel.hidden = true;
                btn.setAttribute('aria-expanded', 'false');
            }
        });
    }

    // --- Botão demonstrativo do Google ---
    document.querySelectorAll('.epa-google-btn').forEach(function (b) {
        b.addEventListener('click', function (e) {
            e.preventDefault();
            alert('Login com Google: demonstração.\n\nEsta é uma demonstração visual para o TCC. A integração real com o Google exige credenciais do Google Cloud.');
        });
    });
})();
