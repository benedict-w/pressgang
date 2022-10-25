<?php

namespace PressGang;

class CookieConsent {

    protected $implied_consent_text;
    protected $button_text;
    protected $privacy_link_text;
    protected $privacy_url;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct() {

        // set defaults
        $this->implied_consent_text = __("This site uses cookies, as described in our Policy. You can disable cookies as mentioned in our Privacy Policy. By continuing to use this website without disabling the cookies, you consent to our use of those cookies which you have not disabled.", THEMENAME);
        $this->button_text = __("OK", THEMENAME);
        $this->privacy_link_text = __("View privacy policy", THEMENAME);
        $this->privacy_url = get_privacy_policy_url();

        Scripts::$scripts['js-cookie'] = array(
            'src' => get_template_directory_uri() . '/js/src/vendor/js-cookie/js.cookie.2.1.4.js',
            'deps' => array(),
            'ver' => '2.1.4',
            'in_footer' => true,
            'hook' => 'show_cookie_consent',
        );

        Scripts::$scripts['cookie-consent'] = array(
            'src' => get_template_directory_uri() . '/js/src/custom/cookie-consent.js',
            'deps' => array('jquery', 'js-cookie'),
            'ver' => '0.1',
            'in_footer' => true,
            'hook' => 'show_cookie_consent'
        );

        add_action('customize_register', array($this, 'customizer'));
        add_filter('wp_footer', array($this, 'render'));
    }

    /**
     * Add to customizer
     *
     * @param $wp_customize
     */
    public function customizer($wp_customize) {

        $wp_customize->add_section('cookie-consent', array(
            'title' => __("Cookie Consent", THEMENAME),
        ) );

        $wp_customize->add_setting(
            'implied-consent-text',
            array(
                'default' => $this->implied_consent_text,
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control(new \WP_Customize_Control( $wp_customize, 'implied-consent-text', array(
            'label' => __("Implied Consent Text", THEMENAME),
            'section' => 'cookie-consent',
        )));

        $wp_customize->add_setting(
            'button-text',
            array(
                'default' => $this->button_text,
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control(new \WP_Customize_Control( $wp_customize, 'button-text', array(
            'label' => __("Implied Consent Button", THEMENAME),
            'section' => 'cookie-consent',
        )));

        $wp_customize->add_setting(
            'privacy-url',
            array(
                'default' => $this->privacy_url,
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control(new \WP_Customize_Control( $wp_customize, 'privacy-url', array(
            'label' => __("Privacy URL", THEMENAME),
            'section' => 'cookie-consent',
        )));

        $wp_customize->add_setting(
            'privacy-link-text',
            array(
                'default' => $this->privacy_link_text,
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control(new \WP_Customize_Control( $wp_customize, 'privacy-link-text', array(
            'label' => __("Privacy Link Text", THEMENAME),
            'section' => 'cookie-consent',
        )));
    }

    /**
     * render
     *
     * Render cookie-consent.twig
     *
     */
    public function render() {

        if(!isset($_COOKIE['cookie-consent'])) {

            do_action('show_cookie_consent');

            \Timber::render('cookie-consent.twig',  array(
                'implied_consent_text' => get_theme_mod('implied-consent-text', $this->implied_consent_text),
                'button_text' => get_theme_mod('button-text', $this->button_text),
                'privacy_url' => get_theme_mod('privacy-url', $this->privacy_url),
                'privacy_link_text' => get_theme_mod('privacy-link-text', $this->privacy_link_text),
            ));
        }
    }
}

new CookieConsent();