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
        add_filter('acf/fields/google_map/api', array($this, 'get_google_maps_key'));
    }

    /**
     * set_google_maps_key
     *
     */
    public function set_google_maps_key() {
        if ($google_maps_key = filter_var(get_theme_mod('acf_google_maps_key'), FILTER_SANITIZE_STRING)) {
            acf_update_setting('acf_google_maps_key', $google_maps_key);
        }
    }

    /**
     * Added after ACF update
     * see - https://support.advancedcustomfields.com/forums/topic/google-map-not-displaying-on-wp-backend/
     *
     * @param $api
     * @return mixed
     */
    public function get_google_maps_key($api) {

        if ($google_maps_key = filter_var(get_theme_mod('acf_google_maps_key'), FILTER_SANITIZE_STRING)) {
            $api['key'] = $google_maps_key;
        }

        return $api;
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
