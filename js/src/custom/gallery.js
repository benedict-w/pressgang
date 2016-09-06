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

    // set magnific on images that are the only children of a an anchor (e.g. in posts)
    $('a>img:only-child').each(function() {
        $img = $(this);
        if ($img.attr('src') === $img.parent('a').attr('href')) { // TODO also match absolute and relative?
            $(this).magnificPopup({
                type: 'image'
            });
        }
    });

})(jQuery);