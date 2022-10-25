<?php

namespace PressGang;

/**
 * Class SumoMe
 *
 * Adds support for SumoMe (https://sumome.com/) traffic tools.
 *
 * @package PressGang
 */
class SumoMe {

    /**
     * init
     *
     * @return void
     */
    public static function init() {
        add_action('customize_register', array('PressGang\SumoMe', 'customizer'));
        add_action('wp_head', array('PressGang\SumoMe', 'add_script'));
    }

    /**
     * Add to customizer
     *
     * Get Website ID at: https://sumome.com/register
     *
     * @param $wp_customize
     */
    public static function customizer($wp_customize) {

        $wp_customize->add_section('sumome' , array(
            'title' => __("Sumo Me", THEMENAME),
        ));

        $wp_customize->add_setting(
            'sumome-id',
            array(
                'default' => '',
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control( new \WP_Customize_Control( $wp_customize, 'sumome-id', array(
            'label' => __("Sumo Me ID", THEMENAME),
            'section'  => 'sumome',
        )));
    }

    /**
     * script
     *
     * @return void
     */
    public static function add_script ()
    {
        if ($sumome_id = get_theme_mod('sumome-id')) {
            \Timber::render('sumome.twig', array('sumome_id' => $sumome_id));
        }
    }

}

SumoMe::init();