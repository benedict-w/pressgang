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
    public $has_recaptcha = false;

    /**
     * __construct
     *
     */
    public function __construct($to = null, $subject = null, $has_recaptcha = false) {
        $this->to = $to ? $to : get_option('admin_email');
        $this->subject = $subject ? $subject : __("New Contact Message", THEMENAME);
        $this->has_recaptcha = $has_recaptcha;
    }

    /**
     * send_contact_message
     *
     * Loop $_POST['contact'] array and send a contact message
     *
     * @param $args
     * @return string
     */
    public function send_message($args = array(), $content = null)
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

                foreach($args as $key => &$val) {
                    if (!in_array($key, array('recaptcha', 'to', 'success', 'message'))) {
                        $message .= sprintf("%s: %s\r\n", ucwords($key), $val);
                    }
                }

                $message .= sprintf("\r\nMessage: %s\r\n", $args['message']);

                // TODO spoofing FROM can cause spam issues
                // add_action('wp_mail_from', function() use ($args) { return $args['email']; });
                // add_action('wp_mail_from_name', function() use ($args) { return $args['name']; });

                $subject = isset($args['subject']) ? $args['subject'] : $this->subject;

                if (!$this->has_recaptcha || ($this->has_recaptcha && $this->verify_recaptcha())) {
                    if (wp_mail($this->to, $subject, $message)) {
                        $this->success = __("Thank you your message was sent.", THEMENAME);

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

    /**
     * verify_recaptcha
     *
     */
    protected function verify_recaptcha () {

        $google_recaptcha_secret = filter_var(get_theme_mod('google-recaptcha-secret', FILTER_SANITIZE_STRING));

        $post_data = http_build_query(
            array(
                'secret' => $google_recaptcha_secret,
                'response' => filter_var($_POST['recaptcha'], FILTER_SANITIZE_STRING),
                'remoteip' => $_SERVER['REMOTE_ADDR']
            )
        );

        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => $post_data
            )
        );

        $context  = stream_context_create($opts);
        $response = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
        $result = json_decode($response);

        return $result->success;
    }

}