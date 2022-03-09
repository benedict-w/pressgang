<?php

namespace PressGang;

class FacebookVerify {

    /**
     * __construct
     *
     * Adds a Facebook Domain Verification customizer field
     *
     * See - https://developers.facebook.com/docs/sharing/domain-verification/verifying-your-domain
     *
     * @return void
     */
    public function __construct() {
        add_action('customize_register', array($this, 'customizer'));
        add_action ('wp_head', array($this, 'add_meta_tag'));
    }

    /**
     * Add to customizer
     *
     * @param $wp_customize
     * @hooked customize_register
     */
    public function customizer($wp_customize) {

        if (!isset($wp_customize->sections['facebook'])) {
            $wp_customize->add_section('facebook', array(
                'title' => __("Facebook", THEMENAME),
            ));
        }

        $wp_customize->add_setting(
            'facebook_domain_verification',
            array(
                'default' => '',
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control(new \WP_Customize_Control($wp_customize, 'facebook_domain_verification', array(
            'label' => __("Facebook Domain Verification", THEMENAME),
            'description' => sprintf(__("See %s"), 'https://bit.ly/3eUd2fI'),
            'section'  => 'facebook',
        )));
    }

    /**
     * add_meta_tag
     *
     * @hooked wp_head
     */
    public function add_meta_tag() {

        $facebook_domain_verification = get_theme_mod('facebook_domain_verification');

        if($facebook_domain_verification) {
            echo '<meta name="facebook-domain-verification" content="' . esc_attr($facebook_domain_verification) . '" />';
        }
    }
}

new FacebookVerify();