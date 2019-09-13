/**
 * Add Hover functionality to Bootstrap 4 dropdown
 *
 * See - https://stackoverflow.com/a/42183824/664125
 */
;(function($) {

    $(function() {

        var toggleDropdown = function(e) {
            var _d = $(e.target).closest('.dropdown'),
                _m = $('.dropdown-menu', _d);

            if (window.outerWidth > 991) {
                setTimeout(function () {
                    const shouldOpen = e.type !== 'click' && _d.is(':hover');
                    _m.toggleClass('show', shouldOpen);
                    _d.toggleClass('show', shouldOpen);
                    _d.parents('.menu').hide().show(0); // fix safari redraw bug - https://stackoverflow.com/questions/31587890/safari-on-mac-not-updating-redrawing-after-hover-effect
                    $('[data-toggle="dropdown"]', _d).attr('aria-expanded', shouldOpen);
                },e.type === 'mouseleave' ? 200 : 0);
            }
        };

        $('body')
            .on('mouseenter mouseleave','.dropdown',toggleDropdown)
            .on('click', '.dropdown-menu a', toggleDropdown);


        $('.dropdown-toggle').on('click', function (e) {
            if (window.outerWidth > 991) {
                var href = $(this).prop('href');
                if (href !== '#') {
                    window.location.href = href;
                }
            }
        })

    });

})(jQuery);