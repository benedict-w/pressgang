<?php

namespace PressGang;

/**
 * Class Contact
 *
 * @package PressGang
 */
class Contact {

    use Recaptcha;

    public $success = false;
    public $error = false;
    public $subject = '';
    public $to = '';
    public $has_recaptcha = false;

    /**
     * __construct
     *
     */
    public function __construct($to = null, $subject = null, $has_recaptcha = false) {
        $this->to = sanitize_email($to ? $to : get_option('admin_email'));
        $this->subject = $subject ? $subject : __("New Contact Message", THEMENAME);
        $this->has_recaptcha = $has_recaptcha;
    }

    /**
     * send_contact_message
     *
     * Loop $_POST['contact'] array and send a contact message
     *
     * @param $args
     * @param $template - twig template for compiling into message
     * @return string
     */
    public function send_message($args = array(), $template = null)
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
                        break;

                    default :
                        $args[$key] = filter_var($val, FILTER_SANITIZE_STRING);
                        break;
                }
            }

            // send email
            if (!empty($args['email']) && !empty($args['name']) && !empty($args['message'])) {

                if($template) {

                    $message = Timber::compile($template, $args);

                } else {
                    foreach($args as $key => &$val) {
                        if (!in_array($key, array('recaptcha', 'to', 'success', 'message'))) {
                            $message .= sprintf("%s: %s\r\n", ucwords($key), $val);
                        }
                    }

                    $message .= sprintf("\r\nMessage: %s\r\n", $args['message']);
                }


                // TODO spoofing FROM can cause spam issues
                // add_action('wp_mail_from', function() use ($args) { return $args['email']; });
                // add_action('wp_mail_from_name', function() use ($args) { return $args['name']; });

                $subject = isset($args['subject']) ? $args['subject'] : $this->subject;

                if (!$this->has_recaptcha || ($this->has_recaptcha && $this->verify_recaptcha())) {
                    if (wp_mail($this->to, $subject, $message)) {
                        $this->success = __("Thank you, your message was sent.", THEMENAME);

                        // register google analytics tracking
                        add_action('wp_footer', array($this, 'send_ga_event'));
                    }
                } else {
                    $this->error = __("Recaptcha failed please contact us by email or phone", THEMENAME);
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