<?php

namespace PressGang;

/**
 * Class FacebookLikeButton
 *
 * See - https://developers.facebook.com/docs/plugins/like-button/
 *
 * @package PressGang
 */
class FacebookLikeButton {

    /**
     * __construct
     *
     * @return void
     */
    public function __construct() {
        add_action('customize_register', array($this, 'customizer'));
        add_action('wp_body_open', array($this, 'script'));
        add_filter('get_twig', array($this, 'add_to_twig'));
    }

    /**
     * Add to customizer
     *
     * @param $wp_customize
     */
    public function customizer($wp_customize) {

        if (!isset($wp_customize->sections['facebook'])) {
            $wp_customize->add_section('facebook', array(
                'title' => __("Facebook", THEMENAME),
            ));
        }

        // App ID

        $wp_customize->add_setting(
            'facebook_app_id',
            array(
                'default'   => '',
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control(new \WP_Customize_Control($wp_customize, 'facebook_app_id', array(
            'label' => __("Facebook App ID", THEMENAME),
            'section'  => 'facebook',
            'type' => 'text',
        )));

        // URL

        $wp_customize->add_setting(
            'facebook_like_url',
            array(
                'default'   => '',
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control(new \WP_Customize_Control($wp_customize, 'facebook_like_url', array(
            'label' => __("Facebook Like URL", THEMENAME),
            'section'  => 'facebook',
            'type' => 'text',
        )));

        // SDK NONCE

        $wp_customize->add_setting(
            'facebook_sdk_nonce',
            array(
                'default'   => '',
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control(new \WP_Customize_Control($wp_customize, 'facebook_sdk_nonce', array(
            'label' => __("Facebook SDK Nonce", THEMENAME),
            'section'  => 'facebook',
            'type' => 'text',
        )));

    }

    /**
     * script
     *
     * @return void
     */
    public function script () {

        if ($facebook_sdk_nonce = get_theme_mod('facebook_sdk_nonce')) {

            \Timber::render('fb-sdk.twig', array(
                'locale' => get_locale(),
                'nonce' => $facebook_sdk_nonce,
                'facebook_app_id' => get_theme_mod('facebook_app_id')
            ));
        }
    }

    /**
     * add_to_twig
     *
     * Add a Facebook Like button to the Twig scope
     *
     * @param $twig
     * @return mixed
     */
    public function add_to_twig($twig) {

        $twig->addFunction(new \Twig\TwigFunction('fb_like_btn', function() {
            \Timber::render('fb-like.twig', array(
                'url' => get_theme_mod('facebook_like_url')
            ));
        }));

        return $twig;
    }
}

new FacebookLikeButton();