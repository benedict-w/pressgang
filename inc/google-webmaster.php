<?php

namespace PressGang;

class GoogleWebmaster {

    /**
     * __construct
     *
     * Adds a google-webmaster customizer field, the value is added in site.php
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

        if (!isset($wp_customize->sections['google'])) {
            $wp_customize->add_section('google', array(
                'title' => __("Google", THEMENAME),
            ));
        }

        $wp_customize->add_setting(
            'google_verification_code',
            array(
                'default' => '',
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control(new \WP_Customize_Control($wp_customize, 'google_verification_code', array(
            'label' => __("Google Webmaster Verification Code", THEMENAME),
            'description' => sprintf(__("See %s"), 'https://goo.gl/kXrMha'),
            'section'  => 'google',
        )));
    }
}

new GoogleWebmaster();