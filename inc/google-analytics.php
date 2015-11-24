<?php

namespace PressGang;

class GoogleAnalytics {

    /**
     * init
     *
     * @return void
     */
    public static function init() {
        add_action('customize_register', array('PressGang\GoogleAnalytics', 'customizer'));
        add_action('wp_footer', array('PressGang\GoogleAnalytics', 'script'));
    }

    /**
     * Add to customizer
     *
     * @param $wp_customize
     */
    public static function customizer($wp_customize) {

        $wp_customize->add_section( 'google' , array(
            'title' => __("Google", THEMENAME),
        ) );

        $wp_customize->add_setting(
            'google-analytics-id',
            array(
                'default'   => '',
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control( new \WP_Customize_Control( $wp_customize, 'google-analytics-id', array(
            'label' => __("Google Analytics ID", THEMENAME),
            'section'  => 'google',
        ) ) );
    }

    /**
     * script
     *
     * @return void
     */
    public static function script () {
        if ($google_analytics_id = urlencode(get_theme_mod('google-analytics-id'))) {
            \Timber::render('google-analytics.twig', array(
                'google_analytics_id' => $google_analytics_id,
            ));
        }
    }
}

GoogleAnalytics::init();