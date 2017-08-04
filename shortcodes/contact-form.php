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

        $contact = new Contact();

        if ($contact->send_message($args)) {
            // register google analytics tracking
            add_action('wp_footer', array($this, 'send_ga_event'));
        }

        $args['success'] = $contact->success;
        $args['error'] = $contact->error;

        $this->context = $args;

        return \Timber::compile($this->template, $this->context);
    }

    /**
     * Track Google Analytics Event
     *
     */
    public function send_ga_event() { ?>
        <script>
            ga('send', 'event', 'Contact Form', 'submit');
        </script>
        <?php
    }

}

new ContactForm();