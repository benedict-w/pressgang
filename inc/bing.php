<?php

namespace PressGang;

class Bing {

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

        if (!isset($wp_customize->sections['bing'])) {
            $wp_customize->add_section('bing', array(
                'title' => __("Bing", THEMENAME),
            ));
        }

        $wp_customize->add_setting(
            'bing_verification_code',
            array(
                'default' => '',
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control(new \WP_Customize_Control($wp_customize, 'bing_verification_code', array(
            'label' => __("Bing Verification Code", THEMENAME),
            'description' => sprintf(__("See %s"), 'goo.gl/xeaAOv'),
            'section'  => 'bing',
        )));
    }
}

new Bing();