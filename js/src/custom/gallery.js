(function($) {
    // init focus point
    //  $('.focuspoint').focusPoint();

    $('.carousel').on('slid.bs.carousel', function (e) {
        // $(this).find('.focuspoint').focusPoint('adjustFocus');
    });

    // magnific
    $('.magnific-popup').magnificPopup({
        delegate: 'a',
        type: 'image',
        gallery: {
            enabled: true
        }
    });

    $('a>img:only-child').magnificPopup({
        type: 'image'
    });

})(jQuery);(function($) {

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