<?php

namespace PressGang;

if (!defined('EXPLICIT_CONSENT')) {
    define("EXPLICIT_CONSENT", false);
}

/**
 * Microsoft Clarity Tracking
 *
 * @package PressGang
 */
class ClarityTracking {

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

        if (!isset($wp_customize->sections['microsoft'])) {
            $wp_customize->add_section('microsoft', array(
                'title' => __("Microsoft", THEMENAME),
            ));
        }

        $wp_customize->add_setting(
            'clarity-id',
            array(
                'default' => '',
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control(new \WP_Customize_Control($wp_customize, 'clarity-id', array(
            'label' => __("Clartiy ID", THEMENAME),
            'description' => sprintf(__("See %s"), 'https://docs.microsoft.com/en-us/clarity/'),
            'section'  => 'microsoft',
        )));

        // track logged in users?

        $wp_customize->add_setting(
            'clarity-track-logged-in',
            array (
                'default' => 0
            )
        );

        $wp_customize->add_control(new \WP_Customize_Control($wp_customize, 'clarity-track-logged-in', array(
            'label' => __("Track Logged In Users?", THEMENAME),
            'section'  => 'clarity',
            'type' => 'checkbox',
        )));
    }

    /**
     * script
     *
     * @return void
     */
    public function script () {
        $track_logged_in = get_theme_mod('clarity-track-logged-in');

        if (($track_logged_in || (!$track_logged_in && !is_user_logged_in())) && (!EXPLICIT_CONSENT || $this->consented)) {

            if ($clarity_id = get_theme_mod('clarity-id')) {
                \Timber::render('microsoft-clarity.twig', array(
                    'clarity_id' => $clarity_id,
                ));
            }
        }
    }
}

new ClarityTracking();