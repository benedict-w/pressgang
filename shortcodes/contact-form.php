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
        $this->defaults['email'] = get_option('admin_email');
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
    public function do_shortcode($atts, $content = null) {

        $args = shortcode_atts($this->get_defaults(), $atts);

        $args['email'] = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $args['message'] = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);
        $args['name'] = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $args['success'] = false;

        if ($args['email'] && $args['name'] && $args['message']) {

            add_action('wp_mail_from', function() use ($atts) { return $atts['email']; });
            add_action('wp_mail_from_name', function() use ($atts) { return $atts['name']; });

            if (wp_mail($atts['to'], $this->defaults['subject'], $atts['message'])) {
                $args['success'] = $this->defaults['success'];
            }
        }

        $this->context = $args;

        return \Timber::compile($this->template, $this->context);
    }

}

new ContactForm();