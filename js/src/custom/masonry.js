(function($) {
    $(function () {

        var $grid = $('.grid');

        // layout masonry after each image loads
        $grid.imagesLoaded()
            .always( function() {
                $grid.masonry('layout');
            })
            .progress( function() {
                $grid.masonry('layout');
            });

    });
})(jQuery);