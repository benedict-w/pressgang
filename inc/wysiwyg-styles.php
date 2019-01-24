<?php

namespace PressGang;

/**
 * Class WysiwygStyles
 * @package PressGang
 */
class WysiwygStyles {

    /**
     * WysiwygStyles constructor.
     *
     */
    public function __construct() {

        add_filter('mce_buttons_2', array($this, 'show_tinymce_format'));
        add_filter('tiny_mce_before_init', array($this, 'show_custom_styles_dropdown'));
    }

    /**
     * show_tinymce_buttons
     *
     * Add style selector to the beginning of the toolbar
     *
     * See - https://codex.wordpress.org/TinyMCE_Custom_Styles#Enabling_styleselect
     *
     * @param $buttons
     * @return mixed
     */
    public function show_tinymce_format($buttons) {

        array_unshift( $buttons, 'styleselect' );

        return $buttons;
    }

    /**
     * show_custom_styles_dropdown
     *
     * Add new styles to the TinyMCE "formats" menu dropdown
     *
     * See - https://codex.wordpress.org/TinyMCE_Custom_Styles
     *
     * @param $settings
     * @return mixed
     */
    public function show_custom_styles_dropdown($settings)
    {
        // get from config
        $styles = Config::get('wysiwyg_styles');

        // custom style formats
        $style_formats = array(
            array(
                'title' => "Custom Styles",
                'items' => $styles,
            ),
        );

        // preserve original styles
        $settings['style_formats_merge'] = true;

        // add new styles
        $settings['style_formats'] = json_encode( $style_formats );

        return $settings;
    }

}

new WysiwygStyles();