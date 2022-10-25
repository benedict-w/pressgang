<?php

namespace PressGang;

class FbCustomerChat {

    /**
     * __construct
     *
     * @return void
     */
    public function __construct() {
        add_action('customize_register', array($this, 'customizer'));
        add_action('wp_footer', array($this, 'script'));
    }

    /**
     * Add to customizer
     *
     * @param $wp_customize
     */
    public function customizer($wp_customize) {

        if (!isset($wp_customize->sections['fb-customer-chat'])) {
            $wp_customize->add_section('fb-customer-chat', array(
                'title' => __("FB Customer Chat", THEMENAME),
            ));
        }

        // fb-customer-chat id

        $wp_customize->add_setting(
            'fb-customer-chat-id',
            array(
                'default'   => '',
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control(new \WP_Customize_Control($wp_customize, 'fb-customer-chat-id', array(
            'label' => __("FB Page ID", THEMENAME),
            'section'  => 'fb-customer-chat',
            'type' => 'text',
        )));

        // theme color

        $wp_customize->add_setting(
            'fb-customer-chat-theme-color',
            array(
                'default' => '#000000',
                'sanitize_callback' => 'sanitize_hex_color',
            )
        );

        $wp_customize->add_control(new \WP_Customize_Color_Control($wp_customize, 'fb-customer-chat-theme-color', array(
            'label' => __("Theme Colour", THEMENAME),
            'section'  => 'fb-customer-chat',
        )));

    }

    /**
     * script
     *
     * @return void
     */
    public function script () {
        if ($fb_customer_chat_id = get_theme_mod('fb-customer-chat-id')) {
            \Timber::render('fb-customer-chat.twig', array(
                'fb_customer_chat_id' => $fb_customer_chat_id,
                'theme_color' => get_theme_mod('fb-customer-chat-theme-color', '#000000'),
            ));
        }
    }
}

new FbCustomerChat();