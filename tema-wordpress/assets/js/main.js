(function ($) {
    "use strict";

    // Inicia as animações WOW.js
    if (typeof WOW !== 'undefined') {
        new WOW().init();
    }

    // Spinner de carregamento
    var spinner = function () {
        setTimeout(function () {
            if ($('#spinner').length > 0) {
                $('#spinner').removeClass('show');
            }
        }, 1);
    };
    spinner();

    // Navbar fixa com sombra ao rolar
    $(window).scroll(function () {
        if ($(this).scrollTop() > 300) {
            $('.sticky-top').addClass('shadow-sm');
        } else {
            $('.sticky-top').removeClass('shadow-sm');
        }
    });

    // Botão "voltar ao topo"
    $(window).scroll(function () {
        if ($(this).scrollTop() > 300) {
            $('.back-to-top').fadeIn('slow');
        } else {
            $('.back-to-top').fadeOut('slow');
        }
    });
    $('.back-to-top').click(function () {
        var temEasing = $.easing && $.easing.easeInOutExpo;
        $('html, body').animate({ scrollTop: 0 }, 1500, temEasing ? 'easeInOutExpo' : 'swing');
        return false;
    });

    // Botão de pausar/reproduzir o carrossel (acessibilidade — WCAG 2.2.2)
    var btnPause = document.querySelector('.epa-carousel-pause');
    var carouselEl = document.getElementById('customCarousel1');
    if (btnPause && carouselEl && window.bootstrap) {
        // getInstance + new: compatível com Bootstrap 5.0 (getOrCreateInstance só existe no 5.1+)
        var carousel = bootstrap.Carousel.getInstance(carouselEl) || new bootstrap.Carousel(carouselEl);
        var rodando = true;
        btnPause.addEventListener('click', function () {
            if (rodando) {
                carousel.pause();
                btnPause.innerHTML = '<i class="fa fa-play" aria-hidden="true"></i>';
                btnPause.setAttribute('aria-label', 'Reproduzir troca automática de slides');
            } else {
                carousel.cycle();
                btnPause.innerHTML = '<i class="fa fa-pause" aria-hidden="true"></i>';
                btnPause.setAttribute('aria-label', 'Pausar troca automática de slides');
            }
            rodando = !rodando;
        });
    }

})(jQuery);
