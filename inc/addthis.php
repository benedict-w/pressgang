<?php

namespace PressGang;

class AddThis {

    /**
     * init
     *
     * @return void
     */
    public static function init() {
        add_action('customize_register', array('PressGang\AddThis', 'customizer'));
        add_action('wp_enqueue_scripts', array('PressGang\AddThis', 'register_script'));
        add_shortcode('addthis', array('PressGang\AddThis', array('PressGang\AddThis', 'button')));
    }

    /**
     * Add to customizer
     *
     * @param $wp_customize
     */
    public static function customizer($wp_customize) {

        $wp_customize->add_section( 'addthis' , array(
            'title' => __("AddThis", THEMENAME),
        ) );

        $wp_customize->add_setting(
            'addthis-id',
            array(
                'default'   => '',
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control( new \WP_Customize_Control( $wp_customize, 'addthis-id', array(
            'label' => __("AddThis ID", THEMENAME),
            'section'  => 'addthis',
        ) ) );

        $wp_customize->add_setting(
            'addthis-class',
            array(
                'default' => 'addthis_native_toolbox',
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control( new \WP_Customize_Control( $wp_customize, 'addthis-class', array(
            'label' => __("AddThis Toolbox Class", THEMENAME),
            'section'  => 'addthis',
        ) ) );
    }

    /**
     * script
     *
     * Go to www.addthis.com/dashboard to customize your tools
     *
     * @return void
     */
    public static function register_script () {
        if ($addthis_id = urlencode(get_theme_mod('addthis-id'))) {
            wp_register_script('addthis', "//s7.addthis.com/js/300/addthis_widget.js#pubid={$addthis_id}", array(), false, true);
            wp_enqueue_script('addthis');
        }
    }

    /**
     * button
     *
     * Displays the addthis sharing button configured on the addthis.com dashboard page
     *
     */
    public static function button() {
        if ($addthis_id = get_theme_mod('addthis-id')) {
            wp_enqueue_script('addthis');
            \Timber::render('addthis.twig', array('addthis_class' => get_theme_mod('addthis-class')));
        }
    }
}

AddThis::init();