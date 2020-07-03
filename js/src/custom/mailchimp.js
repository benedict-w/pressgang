(function ($) {

    $(function () {

        /**
         * Mailchimp Signup Form Submit
         */
        $('form.mailchimp-form').on('submit', function () {
            $self = $(this);
            $self.find('.alert').hide();
            $.post('/wp-admin/admin-ajax.php?action=mailchimp_signup', $self.serialize(), function (data) {
                data = jQuery.parseJSON(data);
                if (data.success) {
                    $self.find('.alert-success').show().delay(5000).fadeOut(500);
                    $self[0].reset();
                } else {
                    $self.find('.alert-danger').text(data.message).show().delay(5000).fadeOut(500);
                }
            });
            return false;
        });

        /**
         * Hide mailchimp alerts on dismiss
         */
        $('form.mailchimp-form [data-dismiss="alert"]').on('click', function (e) {
            $(this).parent('.alert').hide();
            return false;
        });

    });
})(jQuery);
