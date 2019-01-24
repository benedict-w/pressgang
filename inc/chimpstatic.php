<?php

namespace PressGang;

class Chimpstatic {

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

        if (!isset($wp_customize->sections['chimpstatic'])) {
            $wp_customize->add_section('chimpstatic', array(
                'title' => __("Chimpstatic", THEMENAME),
            ));
        }

        // tracking id

        $wp_customize->add_setting(
            'chimpstatic-id',
            array(
                'default'   => '',
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control(new \WP_Customize_Control($wp_customize, 'chimpstatic-id', array(
            'label' => __("Chimpstatic ID", THEMENAME),
            'section'  => 'chimpstatic',
            'type' => 'text',
        )));

    }

    /**
     * script
     *
     * @return void
     */
    public function script () {
        if ($chimpstatic_id = urlencode(get_theme_mod('chimpstatic-id'))) {
            \Timber::render('chimpstatic.twig', array(
                'chimpstatic_id' => $chimpstatic_id,
            ));
        }
    }
}

new Chimpstatic();