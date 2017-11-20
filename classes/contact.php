<?php

namespace PressGang;

/**
 * Class Vimeo
 *
 * @package PressGang
 */
class Contact {

    public $success = false;
    public $error = false;
    public $subject = '';
    public $to = '';

    /**
     * __construct
     *
     */
    public function __construct($to = null, $subject = null) {
        $this->to = $to ? $to : get_option('admin_email');
        $this->subject = $subject ? $subject : __("New Contact Message", THEMENAME);
    }

    /**
     * send_contact_message
     *
     * Loop $_POST['contact'] array and send a contact message
     *
     * @param $args
     * @return string
     */
    public function send_message($args, $content = null)
    {
        $message = '';

        // filter post inputs if available
        if (isset($_POST['contact'])) {

            foreach ($_POST['contact'] as $key => &$val) {
                switch ($key) {
                    case 'email' :
                        $args[$key] = filter_var($val, FILTER_SANITIZE_EMAIL);
                        break;

                    case 'name':
                    case 'message':
                        $args[$key] = filter_var($val, FILTER_SANITIZE_STRING);
                        $message .= sprintf("%s: %s\n", ucwords($key), $val);
                        break;

                    default :
                        $args[$key] = filter_var($val, FILTER_SANITIZE_STRING);
                }
            }

            // send email
            if (!empty($args['email']) && !empty($args['name']) && !empty($args['message'])) {

                $message .= $args['message'];

                foreach($args as $key => &$val) {
                    $message = sprintf("%s: %s\n%s", ucwords($key), $val, $message);
                }

                add_action('wp_mail_from', function() use ($args) { return $args['email']; });
                add_action('wp_mail_from_name', function() use ($args) { return $args['name']; });

                $subject = $args['subject'] ? $args['subject'] : $this->subject;

                if (wp_mail($this->to, $subject, $message)) {
                    $this->success = __("Thank you your message was sent.", THEMENAME);

                    // register google analytics tracking
                    add_action('wp_footer', array($this, 'send_ga_event'));
                }

            } else {
                $this->error = __("Please complete all required form fields", THEMENAME);
            }
        }

        return $this->success;
    }

    /**
     * Track Google Analytics Event
     *
     */
    public function send_ga_event() {

        $track_logged_in = get_theme_mod('track-logged-in');

        if ($track_logged_in || (!$track_logged_in && !is_user_logged_in()) ) : ?>
            <script>
                ga('send', 'event', 'Contact Form', 'submit');
            </script>
        <?php endif;
    }

}