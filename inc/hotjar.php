<?php

namespace PressGang;

class Hotjar {

    /**
     * __construct
     *
     * @return void
     */
    public function __construct() {
        add_action('customize_register', array($this, 'customizer'));
        add_action('wp_head', array($this, 'script'));
    }

    /**
     * Add to customizer
     *
     * @param $wp_customize
     */
    public function customizer($wp_customize) {

        if (!isset($wp_customize->sections['hotjar'])) {
            $wp_customize->add_section('hotjar', array(
                'title' => __("Hotjar", THEMENAME),
            ));
        }

        // hotjar id

        $wp_customize->add_setting(
            'hotjar-id',
            array(
                'default'   => '',
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control(new \WP_Customize_Control($wp_customize, 'hotjar-id', array(
            'label' => __("Hotjar ID", THEMENAME),
            'section'  => 'hotjar',
            'type' => 'text',
        )));

        // track logged in users?

        $wp_customize->add_setting(
            'hotjar-track-logged-in',
            array (
                'default' => 0
            )
        );

        $wp_customize->add_control(new \WP_Customize_Control($wp_customize, 'hotjar-track-logged-in', array(
            'label' => __("Track Logged In Users?", THEMENAME),
            'section'  => 'hotjar',
            'type' => 'checkbox',
        )));
    }

    /**
     * script
     *
     * @return void
     */
    public function script () {
        $track_logged_in = get_theme_mod('hotjar-track-logged-in');

        if ($track_logged_in || (!$track_logged_in && !is_user_logged_in()) ) {

            if ($google_analytics_id = urlencode(get_theme_mod('hotjar-id'))) {
                \Timber::render('hotjar.twig', array(
                    'hotjar_id' => $google_analytics_id,
                ));
            }
        }
    }
}

new Hotjar();