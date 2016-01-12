jQuery(function($) {

    // init focus point
    $('.focuspoint').focusPoint();

    $('.carousel').on('slid.bs.carousel', function (e) {
        $(this).find('.focuspoint').focusPoint('adjustFocus');
    });

    // magnific
    $('.magnific-popup').magnificPopup({
        delegate: 'a',
        type: 'image',
        gallery: {
            enabled: true
        }
    });

});