(function($) {
    $(function () {

        var $ios_toggle = $('.ios-toggle');
        var $checkbox = $ios_toggle.find(':checkbox');

        $ios_toggle.toggleClass('checked', $checkbox.prop('checked'));
        $checkbox.on('change', function () {
            $(this).toggleClass('checked', $checkbox.prop('checked'));
        });

        $ios_toggle.on('click', function() {
            $ios_toggle.toggleClass('checked', !$checkbox.prop('checked'));
            $checkbox.prop('checked', !$checkbox.prop('checked'));
            $checkbox.trigger('onchange');
        })

    });
})(jQuery);