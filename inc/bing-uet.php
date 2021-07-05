<?php

namespace PressGang;

if (!defined('EXPLICIT_CONSENT')) {
    define("EXPLICIT_CONSENT", false);
}

/**
 * Bing / Microsoft Universal Event Tracking
 *
 * @package PressGang
 */
class BingUniversalEventTracking {

    /**
     * __construct
     *
     *
     * @return void
     */
    public function __construct() {
        add_action('customize_register', array($this, 'customizer'));
        add_action('wp_head', array($this, 'script'));

        $this->consented = isset($_COOKIE['cookie-consent']) && !!$_COOKIE['cookie-consent'];
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
            'bing_uet_id',
            array(
                'default' => '',
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control(new \WP_Customize_Control($wp_customize, 'bing_uet', array(
            'label' => __("Bing UET ID", THEMENAME),
            'description' => sprintf(__("See %s"), 'https://help.ads.microsoft.com/apex/index/3/en/56705'),
            'section'  => 'bing',
        )));

        // track logged in users?

        $wp_customize->add_setting(
            'bing-track-logged-in',
            array (
                'default' => 0
            )
        );

        $wp_customize->add_control(new \WP_Customize_Control($wp_customize, 'bing-track-logged-in', array(
            'label' => __("Track Logged In Users?", THEMENAME),
            'section'  => 'bing',
            'type' => 'checkbox',
        )));
    }

    /**
     * script
     *
     * @return void
     */
    public function script () {
        $track_logged_in = get_theme_mod('bing-track-logged-in');

        if (($track_logged_in || (!$track_logged_in && !is_user_logged_in())) && (!EXPLICIT_CONSENT || $this->consented)) {

            if ($bing_uet_id = get_theme_mod('bing_uet_id')) {
                \Timber::render('bing-uet.twig', array(
                    'bing_uet_id' => $bing_uet_id,
                ));
            }
        }
    }
}

new BingUniversalEventTracking();