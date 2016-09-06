<?php

namespace PressGang;

class Disqus {

    /**
     * init
     *
     * @return void
     */
    public static function init() {
        add_action('customize_register', array('PressGang\Disqus', 'customizer'));
        add_filter('comments_template', array('PressGang\Disqus', 'render'));
    }

    /**
     * Add to customizer
     *
     * @param $wp_customize
     */
    public static function customizer($wp_customize) {

        $wp_customize->add_section( 'disqus' , array(
            'title' => __("Disqus", THEMENAME),
        ) );

        $wp_customize->add_setting(
            'disqus-shortname',
            array(
                'default' => '',
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control( new \WP_Customize_Control( $wp_customize, 'disqus-shortname', array(
            'label' => __("Disqus Shortname", THEMENAME),
            'section' => 'disqus',
        ) ) );
    }

    /**
     * render
     *
     * Render disqus.twig
     *
     */
    public static function render() {
        \Timber::render('disqus.twig',  array('disqus_shortname' => get_theme_mod('disqus-shortname'),));
    }
}

Disqus::init();