<?php

namespace PressGang;

/**
 * Class Customizer
 *
 * @package PressGang
 */
class Customizer {

    /**
     * Init
     *
     */
    public static function init() {
        add_action('customize_register', array('PressGang\Customizer', 'setup'));
    }

    /**
     * Setup all the Customize objects
     *
     * @param $wp_customize
     */
    public static function setup($wp_customize) {
        self::main($wp_customize);
        self::footer($wp_customize);
    }

    /**
     * Main
     *
     * @param $wp_customize
     */
    protected static function main($wp_customize) {

        // logo

        $wp_customize->add_section( 'logo' , array(
            'title' => __("Logo", THEMENAME),
            'priority' => 30,
        ) );

        $wp_customize->add_setting(
            'logo',
            array(
                'default'   => '',
            )
        );

        $wp_customize->add_control( new \WP_Customize_Image_Control( $wp_customize, 'logo', array(
            'label' => __("Logo", THEMENAME),
            'section'  => 'logo',
        ) ) );

        // google

        $wp_customize->add_section( 'google' , array(
            'title' => __("Google", THEMENAME),
            'priority' => 50,
        ) );

        $wp_customize->add_setting(
            'google_verification_code',
            array(
                'default'   => '',
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control( new \WP_Customize_Control( $wp_customize, 'google_verification_code', array(
            'label' => __("Google Webmaster Verification Code", THEMENAME),
            'description' => sprintf(__("See %s"), 'https://goo.gl/kXrMha'),
            'section'  => 'google',
        ) ) );
    }

    /**
     * Footer
     *
     * @param $wp_customize
     */
    protected static function footer ($wp_customize) {
        $wp_customize->add_panel( 'footer', array(
            'title' => __("Footer", THEMENAME),
            'description' => __("Customise elements of the theme footer", THEMENAME),
            'priority' => 160,
        ) );

        $wp_customize->add_section( 'footer' , array(
            'title' => __("Copyright", THEMENAME),
            'panel' => 'footer',
            'priority' => 10,
        ) );

        $wp_customize->add_setting(
            'copyright',
            array(
                'default'   => '',
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control( new \WP_Customize_Control( $wp_customize, 'copyright', array(
            'label' => __("Copyright", THEMENAME),
            'section' => 'footer',
        ) ) );
    }
}

Customizer::init();