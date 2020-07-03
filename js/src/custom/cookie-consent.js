(function ($) {

    $(function () {

        var $consent = $('.cookie-consent');

        if (Cookies.get('cookie-consent')) {
            $consent.hide();
        }

        $consent.find('.btn').on('click', function () {

            Cookies.set('cookie-consent', 1, {expires: 28});

            $consent.fadeOut();

            return false;
        });

    });

})(jQuery);