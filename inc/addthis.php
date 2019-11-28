<?php

namespace PressGang;

class AddThis {

    protected $consented = false;

    /**
     * init
     *
     * @return void
     */
    public function __construct() {
        add_action('customize_register', array($this, 'customizer'));
        add_action('wp_enqueue_scripts', array($this, 'register_script'));
        add_shortcode('addthis', array($this, array('PressGang\AddThis', 'button')));
        add_filter('get_twig', array($this, 'add_to_twig'));

        $this->consented = isset($_COOKIE['cookie-consent']) && !!$_COOKIE['cookie-consent'];
    }

    /**
     * Add to customizer
     *
     * @param $wp_customize
     */
    public function customizer($wp_customize) {

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
    public function register_script () {
        if ($addthis_id = urlencode(get_theme_mod('addthis-id')) && $this->consented ) {
            wp_register_script('addthis', "//s7.addthis.com/js/300/addthis_widget.js#pubid={$addthis_id}", array(), false, true);
            wp_enqueue_script('addthis');
        }
    }

    /**
     * add_to_twig
     *
     * Add a function to the Twig scope
     *
     * @param $twig
     * @return mixed
     */
    public function add_to_twig($twig) {
        $twig->addFunction(new \Twig_SimpleFunction('addthis', array($this, 'button')));
        return $twig;
    }

    /**
     * button
     *
     * Displays the addthis sharing button configured on the addthis.com dashboard page
     *
     */
    public static function button() {
        if ($addthis_id = get_theme_mod('addthis-id') && (isset($_COOKIE['cookie-consent']) && !!$_COOKIE['cookie-consent'])) {
            wp_enqueue_script('addthis');
            \Timber::render('addthis.twig', array('addthis_class' => get_theme_mod('addthis-class')));
        }
    }
}

new AddThis();