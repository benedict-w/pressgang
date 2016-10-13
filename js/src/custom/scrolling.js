(function($) {

    $(function() {

        var offset = $('.navbar-fixed-top').parent('header').outerHeight() + $('#wpadminbar').outerHeight();

        $('a[href^="#"]').on('click', function () {

            var target = this.hash;
            var $target = $(target);

            if ($target.length) {

                $('html, body').stop().animate({
                    'scrollTop': $target.offset().top - offset
                }, 900, 'swing', function () {
                    window.location.hash = target;
                });
            }

            return false;
        });

        $('body').scrollspy({ target: '.navbar', offset: offset });

    });

})(jQuery);
