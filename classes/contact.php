<?php

namespace PressGang;

require_once(__DIR__ . '/flash.php');

/**
 * Class Contact
 *
 * @package PressGang
 */
class Contact {

    use Recaptcha;

    public static $action = 'contact_form';

    /**
     * Contact constructor.
     *
     * @param null $to
     * @param null $subject
     * @param bool $has_recaptcha
     * @param null $template - template to use for the message
     */
    public function __construct($to = null, $subject = null, $has_recaptcha = false, $template = null) {
        $to = sanitize_email($to ? $to : get_option('admin_email'));
        $subject = $subject ? $subject : __("New Contact Message", THEMENAME);
        $has_recaptcha = $has_recaptcha;

        $flash = Flash::get('contact');

        Flash::add('contact', [
            'to' => $to,
            'subject' => $subject,
            'has_recaptcha' => $has_recaptcha,
            'success' => $flash['success'] ?? false,
            'error' => $flash['error'] ?? false,
            'old' => $flash['old'] ?? [],
            'template' => $template,
        ]);

    }

    /**
     * post_form
     *
     * Loop $_POST['contact'] array and send a contact message
     *
     * @param $args
     * @param $template - twig template for compiling into message
     * @return string
     */
    public static function post_form()
    {
        $flash = Flash::get('contact');
        $message = '';
        $to = $flash['to'] ?? get_option('admin_email');
        $subject = $flash['subject'] ?? __("New Contact Message", THEMENAME);
        $has_recaptcha = $flash['has_recaptcha'] ?? false;
        $args = $flash['args'] ?? null;
        $template = $flash['template'] ?? null;
        $success = false;
        $error = false;
        $old = [];


        // filter post inputs if available
        if (isset($_POST['contact'])) {

            foreach ($_POST['contact'] as $key => &$val) {
                switch ($key) {
                    case 'email' :
                        $val = filter_var($val, FILTER_SANITIZE_EMAIL);
                        break;

                    case 'name':
                    case 'message':
                        $val = filter_var($val, FILTER_SANITIZE_STRING);
                        break;

                    default :
                        $val = filter_var($val, FILTER_SANITIZE_STRING);
                        break;
                }

                $args[$key] = $val;
            }

            $old = $args;

            // send email
            if (!empty($args['email']) && !empty($args['name']) && !empty($args['message'])) {

                if($template) {

                    $message = \Timber::compile($template, $args);

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

                $subject = isset($args['subject']) ? $args['subject'] : $subject;

                if (!$has_recaptcha || ($has_recaptcha && static::verify_recaptcha())) {
                    if (wp_mail($to, $subject, $message)) {
                        $success = __("Thank you, your message was sent.", THEMENAME);

                        // register google analytics tracking
                        add_action('wp_footer', array('PressGang\Contact', 'send_ga_event'));
                    }
                } else {
                    $error = __("Recaptcha failed please contact us by email or phone", THEMENAME);
                }

            } else {
                $error = __("Please complete all required form fields", THEMENAME);
            }
        }

        Flash::add('contact', [
            'to' => $to,
            'subject' => $subject,
            'has_recaptcha' => $has_recaptcha,
            'success' => $success,
            'error' => $error,
            'old' => $old,
            'template' => $template,
        ]);

        $redirect = filter_input(INPUT_POST, '_wp_http_referer', FILTER_SANITIZE_STRING);
        $redirect .= '/?success=' . time(); // cache  buster

        wp_safe_redirect($redirect);
        exit;
    }

    /**
     * Track Google Analytics Event
     *
     */
    public static function send_ga_event() {

        $track_logged_in = get_theme_mod('track-logged-in');

        if ($track_logged_in || (!$track_logged_in && !is_user_logged_in()) ) : ?>
            <script>
                ga('send', 'event', 'Contact Form', 'submit');
            </script>
        <?php endif;
    }
}

add_action(sprintf('admin_post_%s', Contact::$action), array('PressGang\Contact', 'post_form'));
add_action(sprintf('admin_post_nopriv_%s', Contact::$action), array('PressGang\Contact', 'post_form'));