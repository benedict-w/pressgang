;(function($) {

    $(function() {

        var update_qty = function(increment) {
            var $this= $(this);
            var $qty = $this.parents('.quantity').find('.qty');

            var val = parseInt($qty.val());
            val = isNaN(val) ? 0 : val;

            val = (increment) ? val+1 : val-1;

            if (increment || val >= 0) {
                $qty.val(val);
                $this.parents('.cart_item').find('button[name=update_cart]').prop('disabled', false);
                $('.actions button[name=update_cart]').prop('disabled', false);
            }
        };

        $('.woocommerce')
            .on('click', '.btn-qty-increment', function() {
                update_qty.apply(this, [true]);
            })
            .on('click', '.btn-qty-decrement', function() {
                update_qty.apply(this, [false]);
            });

    });

})(jQuery);