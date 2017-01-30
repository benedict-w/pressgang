<?php

namespace PressGang\Contact;

/**
 * Class Vimeo
 *
 * @package PressGang
 */
class ContactForm extends \Pressgang\Shortcode {

    protected $template = 'contact-form.twig';

    protected $defaults = array(
        'to' => null,
        'recaptcha' => null,
        'subject' => '',
    );

    /**
     * get_defaults
     *
     * @return array
     */
    protected function get_defaults ()
    {
        $this->defaults['to'] = get_option('admin_email');
        $this->defaults['subject'] = __("New Contact Message", THEMENAME);
        $this->defaults['success'] = __("Your message has been sent.", THEMENAME);

        return $this->defaults;
    }


    /**
     * do_shortcode
     *
     * Render the shortcode template
     *
     * @return string
     */
    public function do_shortcode($atts, $content = null)
    {

        $args = shortcode_atts($this->get_defaults(), $atts);

        $args['success'] = false;

        // filter post inputs if available
        if (isset($_POST['contact'])) {

            $message = '';

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

                if (wp_mail($args['to'], $this->defaults['subject'], $message)) {
                    // if sent set the success message text
                    $args['success'] = $this->defaults['success'];
                }
            } else {
                $args['error'] = __("Please complete all required form fields", THEMENAME);
            }
        }

        $this->context = $args;

        return \Timber::compile($this->template, $this->context);
    }

}

new ContactForm();