(function ($) {
    "use strict";

    //Loading
    // jQuery(window).on("load", function () {
    //     jQuery(".loader").fadeOut(2000);
    // });

    // Initiate the wowjs
    new WOW().init();

    // Sticky Navbar
    $(window).scroll(function () {
        let menu = $('.sticky-top');
        if ($(this).scrollTop() > 150) {
            menu.css('top', '0px');
            menu.css('background-color', 'rgba(3, 156, 79, 0.9)');
            $('.navbar-light .navbar-nav .nav-link.active').css('border-bottom', '5px solid var(--secondaire)');
        } else {
            menu.css('top', '-100px');
            if (document.body.clientWidth <= 992) {
                menu.css('background-color', 'rgba(3, 156, 79, 0.9)');
            $('.navbar-light .navbar-nav .nav-link.active').css('border-bottom', '5px solid var(--secondaire)');
            } else {
                menu.css('background-color', 'rgba(3, 156, 79, 0.2)');
            $('.navbar-light .navbar-nav .nav-link.active').css('border-bottom', '5px solid var(--primaire)');
            }
        }
    });

    // Back to top button
    $(window).scroll(function () {
        if ($(this).scrollTop() > 300) {
            $('.back-to-top').fadeIn('slow');
        } else {
            $('.back-to-top').fadeOut('slow');
        }
    });
    /*$('.back-to-top').click(function () {
        $('html, body').animate({scrollTop: 0}, 150, 'easeInOutExpo');
        return false;
    });*/

    // loader
    /* $('.compteur').counterUp({
         // delay: 0,
         sstime: 2000
     });*/

    var url = $(location).attr("href");
    var fr = url.indexOf("/fr/");
    var lang = $('#changelang');
    if (fr !== -1) {
        lang.html('EN');

    } else {
        lang.html('FR');
    }
    lang.click(function () {
        if (fr !== -1) {
            url = url.replace("/fr/", "/en/");
        } else {
            url = url.replace("/en/", "/fr/");
        }
        document.location.href = url;
    });

})(jQuery);

