(function($) {
    $(function () {

        var $grid = $('.grid');

        $grid.masonry();

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