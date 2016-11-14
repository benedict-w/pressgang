<?php

namespace PressGang;

class Adsense {

    /**
     * __construct
     *
     * @return void
     */
    public function __construct() {
        add_action('customize_register', array($this, 'customizer'));
        add_action('wp_head', array($this, 'script'), 500);
        add_action('wp_head', array($this, 'add_page_level_ads'), 500);
    }

    /**
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

        // adsense id

        $wp_customize->add_setting(
            'google-adsense-id',
            array(
                'default' => '',
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control(new \WP_Customize_Control($wp_customize, 'google-adsense-id', array(
            'label' => __("Google Adsense ID", THEMENAME),
            'section' => 'google',
            'type' => 'text',
        )));

        $wp_customize->add_setting('show-page-level-ads', array(
            'default' => '0'
        ));

        $wp_customize->add_control(new \WP_Customize_Control($wp_customize, 'show-page-level-ads', array(
            'label'  => __('Show Page-Level Ads', THEMENAME ),
            'section' => 'google',
            'settings' => 'show-page-level-ads',
            'type' => 'checkbox',
        )));
    }

    /**
     * script
     *
     * @return void
     */
    public function script () {
        if ($google_adsense_id = get_theme_mod('google-adsense-id')) {
            \Timber::render('google-adsense.twig', array(
                'google_adsense_id' => $google_adsense_id,
                'show-page-level-ads' => !!get_theme_mod('show-page-level-ads'),
            ));
        }
    }
}

new Adsense();