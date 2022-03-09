<?php

// TODO Investigate and add these: http://cubiq.org/clean-up-and-optimize-wordpress-for-your-next-theme

namespace PressGang;

/**
 * Class Emojicons
 *
 * Remove the Wordpress Emojicons
 *
 * @package Globis
 */
class Emojicons
{
    /**
     * init
     *
     * @return void
     */
    public static function init() {
        add_action( 'init', array('PressGang\Emojicons', 'disable') );
    }

    /**
     * disable
     *
     * @return void
     */
    public static function disable() {
        remove_action( 'admin_print_styles', 'print_emoji_styles' );
        remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
        remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
        remove_action( 'wp_print_styles', 'print_emoji_styles' );
        remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
        remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
        remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );

        // filter to remove TinyMCE emojis
        add_filter( 'tiny_mce_plugins', array('PressGang\Emojicons', 'disable_emojicons_tinymce') );
    }

    /**
     * disable_emojicons_tinymce
     *
     * @param $plugins
     * @return array
     */
    public static function disable_emojicons_tinymce( $plugins ) {
        if ( is_array( $plugins ) ) {
            return array_diff( $plugins, array( 'wpemoji' ) );
        }
        return array();
    }
}

Emojicons::init();