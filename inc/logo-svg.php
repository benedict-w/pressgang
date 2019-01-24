<?php

namespace PressGang;

/**
 * Class LogoSvg
 *
 * @package PressGang
 */
class LogoSvg {

    // TODO sanitize https://github.com/darylldoyle/svg-sanitizer
    // TODO fix media library rendering?
    // TODO minimize file?

    /**
     * __construct
     *
     */
    public function __construct() {
        add_action('customize_register', array($this, 'logo_svg'));
        add_filter('upload_mimes', array($this, 'add_svg_mime'));
    }

    /**
     * logo_svg
     *
     * @param $wp_customize
     */
    public static function logo_svg($wp_customize) {

        if (!$wp_customize->get_section('logo')) {
            $wp_customize->add_section('logo', array(
                'title' => __("Logo", THEMENAME),
                'priority' => 30,
            ));
        }

        $wp_customize->add_setting(
            'logo_svg',
            array(
                'default'  => '',
            )
        );

        $wp_customize->add_control( new \WP_Customize_Image_Control( $wp_customize, 'logo_svg', array(
            'label' => __("Logo SVG", THEMENAME),
            'section'  => 'logo',
            'extensions' => array('svg'),
        ) ) );

    }

    /**
     * add_svg_mime
     *
     * @param $mimes
     * @return mixed
     */
    public function add_svg_mime($mimes) {
        if (!isset($mimes['svg'])) {
            $mimes['svg'] = 'image/svg+xml';
        }
        return $mimes;
    }


}

new LogoSvg();