<?php

namespace Pressgang\Widget;

/**
 * Class Image
 *
 * @package Pressgang
 */
class ContactForm extends \Pressgang\Widget {

    protected $view = 'contact-form.twig';

    /**
     * __construct
     *
     * ContactForm Widget constructor.
     *
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * widget
     *
     * @param array $args
     * @param array $instance
     */
    public function widget($args, $instance)
    {
        $contact = new \Pressgang\Contact();

        if ($contact->send_message($args)) {

        }

        $instance['success'] = $contact->success;
        $instance['error'] = $contact->error;

        parent::widget($args, $instance);
    }

}

register_widget('PressGang\Widget\ContactForm');