<?php

namespace PressGang;

class Pinterest {

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

        if (!isset($wp_customize->sections['pinterest'])) {
            $wp_customize->add_section('pinterest', array(
                'title' => __("Pinterest", THEMENAME),
            ));
        }

        $wp_customize->add_setting(
            'pinterest',
            array(
                'default'   => '',
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control(new \WP_Customize_Control($wp_customize, 'pinterest', array(
            'label' => __("Verification", THEMENAME),
            'section'  => 'pinterest',
            'type' => 'text',
        )));
    }

    /**
     * script
     *
     * @return void
     */
    public function script () {
        $pinterest = filter_var(get_theme_mod('pinterest'), FILTER_SANITIZE_STRING);

        if ($pinterest) : ?>
            <meta name="p:domain_verify" content="<?php echo $pinterest; ?>" />
        <?php endif;
    }
}

new Pinterest();