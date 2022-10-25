<?php

namespace PressGang;

class Trustpilot {

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

        if (!isset($wp_customize->sections['trustpilot'])) {
            $wp_customize->add_section('trustpilot', array(
                'title' => __("Trustpilot", THEMENAME),
            ));
        }

        // trustpilot business id

        $wp_customize->add_setting(
            'trustpilot_business_id',
            array(
                'default'   => '',
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control(new \WP_Customize_Control($wp_customize, 'trustpilot_business_id', array(
            'label' => __("Business ID", THEMENAME),
            'section'  => 'trustpilot',
            'type' => 'text',
        )));

        // trustpilot template id

        $wp_customize->add_setting(
            'trustpilot_template_id',
            array(
                'default'   => '',
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control(new \WP_Customize_Control($wp_customize, 'trustpilot_template_id', array(
            'label' => __("Template ID", THEMENAME),
            'section'  => 'trustpilot',
            'type' => 'text',
        )));

        // trustpilot reviews url

        $wp_customize->add_setting(
            'trustpilot_reviews_link',
            array(
                'default'   => '',
                'sanitize_callback' => 'esc_url_raw',
            )
        );

        $wp_customize->add_control(new \WP_Customize_Control($wp_customize, 'trustpilot_reviews_link', array(
            'label' => __("Reviews URL", THEMENAME),
            'section'  => 'trustpilot',
            'type' => 'text',
        )));
    }

    /**
     * script
     *
     * @return void
     */
    public function script () {

        ?>
        <script type="text/javascript" src="//widget.trustpilot.com/bootstrap/v5/tp.widget.bootstrap.min.js" async></script>
        <?php
    }
}

new Trustpilot();