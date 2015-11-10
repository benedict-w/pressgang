jQuery(function($) {

    // init focus point
    $('.focuspoint').focusPoint();

    $('.carousel').on('slid.bs.carousel', function (e) {
        $(this).find('.focuspoint').focusPoint('adjustFocus');
    });

});