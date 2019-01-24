<?php

namespace PressGang;

class AcfGoogleMaps {

    /**
     * __construct
     *
     */
    public function __construct() {

        add_action('acf/init', array($this, 'set_google_maps_key'));
        add_action('customize_register', array($this, 'customizer'));
    }

    /**
     * set_google_maps_key
     *
     */
    public function set_google_maps_key() {

        if ($google_maps_key = filter_var(get_theme_mod('acf_google_maps_key'), FILTER_SANITIZE_STRING)) {
            acf_update_setting('google_api_key', $google_maps_key);
        }
    }

    /**
     * customizer
     *
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
            'acf_google_maps_key',
            array(
                'default' => '',
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control(new \WP_Customize_Control($wp_customize, 'acf_google_maps_key', array(
            'label' => __("ACF Google Maps Key", THEMENAME),
            'description' => sprintf(__("See %s"), 'https://goo.gl/Dn36CD'),
            'section'  => 'google',
        )));
    }
}

new AcfGoogleMaps();
