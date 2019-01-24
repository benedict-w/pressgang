<?php

namespace PressGang;

class HeapAnalytics {

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

        if (!isset($wp_customize->sections['heap-analytics'])) {
            $wp_customize->add_section('heap-analytics', array(
                'title' => __("Heap Analytics", THEMENAME),
            ));
        }

        // heap_analytics id

        $wp_customize->add_setting(
            'heap-analytics-id',
            array(
                'default'   => '',
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control(new \WP_Customize_Control($wp_customize, 'heap-analytics-id', array(
            'label' => __("Heap Analytics ID", THEMENAME),
            'section'  => 'heap-analytics',
            'type' => 'text',
        )));

        // track logged in users?

        $wp_customize->add_setting(
            'heap-analytics-track-logged-in',
            array (
                'default' => 0
            )
        );

        $wp_customize->add_control(new \WP_Customize_Control($wp_customize, 'heap-analytics-track-logged-in', array(
            'label' => __("Track Logged In Users?", THEMENAME),
            'section'  => 'heap-analytics',
            'type' => 'checkbox',
        )));
    }

    /**
     * script
     *
     * @return void
     */
    public function script () {
        $track_logged_in = get_theme_mod('heap-analytics-track-logged-in');

        if ($track_logged_in || (!$track_logged_in && !is_user_logged_in()) ) {

            if ($heap_analytics_id = urlencode(get_theme_mod('heap-analytics-id'))) {
                \Timber::render('heap-analytics.twig', array(
                    'heap_analytics_id' => $heap_analytics_id,
                ));
            }
        }
    }
}

new HeapAnalytics();