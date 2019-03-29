(function($) {

    $(function () {

        // set hamburger classes
        $('#main-menu')
            .on('shown.bs.collapse', function () {
                $('.hamburger').addClass('is-active');
                $('body').addClass('push-menu');
            })
            .on('hide.bs.collapse', function () {
                $('.hamburger').removeClass('is-active');
                $('body').removeClass('push-menu');
            });

    });
})(jQuery);