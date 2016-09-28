(function($) {

    /**
     * Setup magnific-popup on the gallery
     *
     * See - http://dimsemenov.com/plugins/magnific-popup/
     *     - https://github.com/dimsemenov/Magnific-Popup/
     */
    $('.magnific-popup').magnificPopup({
        delegate: 'a',
        type: 'image',
        gallery: {
            enabled: true
        }
    });

})(jQuery);