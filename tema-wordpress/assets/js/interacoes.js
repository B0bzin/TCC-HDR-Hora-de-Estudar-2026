/**
 * Hora de Estudar — Curtir / Não curtir dos vídeos.
 * Um voto por navegador (guardado no localStorage).
 */
(function () {
    "use strict";

    var caixa = document.querySelector('.epa-votos');
    if ( ! caixa || typeof epaVote === 'undefined' ) return;

    var postId  = caixa.getAttribute('data-post-id');
    var chave   = 'epa_voto_' + postId;
    var btnLike = caixa.querySelector('.epa-voto-like');
    var btnDis  = caixa.querySelector('.epa-voto-dislike');
    var obrig   = caixa.querySelector('.epa-votos-obrigado');

    // Se já votou antes, marca a escolha e bloqueia
    var jaVotou = localStorage.getItem(chave);
    if ( jaVotou ) marcarVotado( jaVotou );

    function marcarVotado( tipo ) {
        btnLike.disabled = true;
        btnDis.disabled  = true;
        caixa.classList.add('votado');
        if ( tipo === 'like' )    btnLike.classList.add('escolhido');
        if ( tipo === 'dislike' ) btnDis.classList.add('escolhido');
        if ( obrig ) obrig.hidden = false;
    }

    function votar( tipo ) {
        if ( localStorage.getItem(chave) ) return; // já votou

        var dados = new URLSearchParams();
        dados.append('action', 'epa_vote');
        dados.append('nonce', epaVote.nonce);
        dados.append('post_id', postId);
        dados.append('vote', tipo);

        fetch( epaVote.url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: dados.toString()
        })
        .then(function(r){ return r.json(); })
        .then(function(res){
            if ( res && res.success ) {
                caixa.querySelector('.epa-like-num').textContent    = res.data.likes;
                caixa.querySelector('.epa-dislike-num').textContent = res.data.dislikes;
                localStorage.setItem(chave, tipo);
                marcarVotado( tipo );
            }
        })
        .catch(function(){ /* silencioso */ });
    }

    btnLike.addEventListener('click', function(){ votar('like'); });
    btnDis.addEventListener('click',  function(){ votar('dislike'); });
})();
