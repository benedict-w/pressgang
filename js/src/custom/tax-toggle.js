(function ($) {

    $(function () {

        // set tax toggle in case of caching
        $('[name="woocommerce_tax_display"]').prop('checked', Cookies.get('woocommerce_tax_display') === 'incl');

    });

})(jQuery);