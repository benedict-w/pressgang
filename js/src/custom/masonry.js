(function($) {
    $(function () {

        // layout masonry after each image loads
        $('.grid').imagesLoaded().progress( function() {
            $('.grid').masonry('layout');
        });

    });
})(jQuery);