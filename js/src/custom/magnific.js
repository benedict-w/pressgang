(function($) {

    $(function() {

        /**
         * Setup image popups
         *
         */
        $('a.image-popup').magnificPopup({
            type: 'image'
        });

        /**
         * Create gallery from magnific items
         *
         */
        $('.magnific-item').magnificPopup({
            type: 'image',
            gallery:{
                enabled: true
            }
        });
    });

})(jQuery);