<?php

namespace PressGang;

class Mailchimp {

    /**
     * init
     *
     * @return void
     */
    public function __construct() {
        add_action('customize_register', array($this, 'customizer'));
    }

    /**
     * Add to customizer
     *
     * @param $wp_customize
     */
    public function customizer($wp_customize) {

        $wp_customize->add_section( 'mailchimp' , array(
            'title' => __("Mailchimp", THEMENAME),
        ) );

        $wp_customize->add_setting(
            'mailchimp-id',
            array(
                'default'   => '',
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control( new \WP_Customize_Control( $wp_customize, 'mailchimp-id', array(
            'label' => __("Mailchimp ID", THEMENAME),
            'section'  => 'mailchimp',
        ) ) );
    }

}

new Mailchimp();