(function($) {

    $(function() {

        var $consent = $('.cookie-consent');

        $consent.find('.btn').on('click', function() {

            // set cookie for 4 weeks
            var date = new Date();
            date.setDate(date.getDate() + 28);

            document.cookie = "cookie-consent=true; expires=" + date.toGMTString() + "; path=/";

            $consent.fadeOut();

            return false;

        })

    });

})(jQuery);