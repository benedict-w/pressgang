<?php

namespace PressGang;

class TwitterPixel {

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

        if (!isset($wp_customize->sections['twitter'])) {
            $wp_customize->add_section('twitter', array(
                'title' => __("Twitter", THEMENAME),
            ));
        }

        // tracking id

        $wp_customize->add_setting(
            'twitter-pixel-id',
            array(
                'default'   => '',
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control(new \WP_Customize_Control($wp_customize, 'twitter-pixel-id', array(
            'label' => __("Twitter Pixel ID", THEMENAME),
            'section'  => 'twitter',
            'type' => 'text',
        )));

        // track logged in users?

        $wp_customize->add_setting(
            'twitter-track-logged-in',
            array (
                'default' => 0
            )
        );

        $wp_customize->add_control(new \WP_Customize_Control($wp_customize, 'twitter-track-logged-in', array(
            'label' => __("Track Logged In Users?", THEMENAME),
            'section'  => 'twitter',
            'type' => 'checkbox',
        )));
    }

    /**
     * script
     *
     * @return void
     */
    public function script () {
        $track_logged_in = get_theme_mod('twitter-track-logged-in');

        if ($track_logged_in || (!$track_logged_in && !is_user_logged_in()) ) {

            if ($facebook_pixel_id = urlencode(get_theme_mod('twitter-pixel-id'))) {
                \Timber::render('twitter-pixel.twig', array(
                    'twitter_pixel_id' => $facebook_pixel_id,
                ));
            }
        }
    }
}

new TwitterPixel();