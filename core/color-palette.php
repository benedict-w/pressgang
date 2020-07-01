<?php

namespace PressGang;

/**
 * Class ColorPalette
 *
 * Registers a colour palette for the Gutenberg editor.
 *
 * See - https://developer.wordpress.org/block-editor/developers/themes/theme-support/
 *
 * @package Pressgang
 */
class ColorPalette {

    protected $color_palette = array();

    /**
     * constructor
     *
     */
    public function __construct() {
        add_action('after_setup_theme', array($this, 'setup'), 50);
    }

    /**
     * Theme Setup
     *
     * @hooked after_setup_theme
     */
    public function setup() {

        $this->color_palette = Config::get('color-palette');

        if(is_array($this->color_palette)) {

            foreach ($this->color_palette as &$palette) {
                if (!isset($palette['slug'])) {
                    $palette['slug'] = sanitize_title($palette['name']);
                }
            }

            // disable custom Colors
            add_theme_support('disable-custom-colors');

            // editor color palette
            add_theme_support('editor-color-palette', $this->color_palette);
        }
    }
}

new ColorPalette();