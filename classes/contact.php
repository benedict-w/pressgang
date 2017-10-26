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

                if (wp_mail($this->to, $this->subject, $message)) {
                    $this->success = __("Thank you your message was sent.", THEMENAME);
                }

            } else {
                $this->error = __("Please complete all required form fields", THEMENAME);
            }
        }

        return $this->success;
    }

}