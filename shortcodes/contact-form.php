<?php

namespace PressGang;

require_once __DIR__ . '/../../pressgang/classes/contact.php';

/**
 * Class ContactForm
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

        $contact = new \PressGang\Contact($args['to'], $args['subject'], $args['recaptcha']);
        $flash = \PressGang\Flash::get('contact');

        $success = $flash['success'] ?? null;

        $context['success'] = isset($_GET['success']);
        $context['error'] = $flash['error'] ?? null;
        $context['old'] = $success ? [] : $flash['old'] ?? []; // clears form on success
        $context['action'] = $contact::$action;

        return \Timber::compile($this->template, $context);
    }

}

new ContactForm();