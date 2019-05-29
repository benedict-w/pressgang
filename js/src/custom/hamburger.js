(function($) {

    $(function () {

        // set hamburger classes
        $('#header .navbar-collapse')
            .on('shown.bs.collapse', function () {
                $('.hamburger').addClass('is-active');
                $('body').addClass('hamburger-open');
            })
            .on('hide.bs.collapse', function () {
                $('.hamburger').removeClass('is-active');
                $('body').removeClass('hamburger-open');
            });

    });
})(jQuery);