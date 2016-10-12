(function($) {

    $(function() {

        $('a[href^="#"]').on('click', function () {

            var target = this.hash;
            var $target = $(target);

            if ($target.length) {

                $('html, body').stop().animate({
                    'scrollTop': $target.offset().top - $('.navbar-fixed-top').parent('header').outerHeight() - $('#wpadminbar').outerHeight()
                }, 900, 'swing', function () {
                    window.location.hash = target;
                });
            }

            return false;
        });

    });

})(jQuery);
