<?php
namespace PressGang;

/**
 * Flush permalinks in wp-admin
 *
 * Static asset rewrites
 * see - https://gist.github.com/wycks/2315295
 *
 * rewrite /wp-content/themes/theme-name/css/ to /css/
 * rewrite /wp-content/themes/theme-name/js/ to /js/
 * rewrite /wp-content/themes/theme-name/img/ to /img/
 * rewrite /wp-content/plugins/ to /plugins/
 *
 */
class Permalinks {

    /**
     * init
     */
    public static function init() {
        add_action('generate_rewrite_rules', array('PressGang\Permalinks', 'add_rewrites'));
    }

    /**
     * add_rewrites
     *
     * @param $content
     */
    public static function add_rewrites($content) {
        $var = explode('/themes/', get_stylesheet_directory());
        $theme_name = next($var);
        global $wp_rewrite;
        $new_non_wp_rules = array(
            'style.css' => 'wp-content/themes/' . $theme_name . '/style.css',
            'css/(.*)' => 'wp-content/themes/' . $theme_name . '/css/$1',
            'js/(.*)' => 'wp-content/themes/' . $theme_name . '/js/$1',
            'img/(.*)' => 'wp-content/themes/' . $theme_name . '/img/$1',
            'fonts/(.*)' => 'wp-content/themes/' . $theme_name . '/fonts/$1',
            'plugins/(.*)' => 'wp-content/plugins/$1'
        );
        $wp_rewrite->non_wp_rules += $new_non_wp_rules;
    }
}

Permalinks::init();