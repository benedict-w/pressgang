<?php

namespace PressGang\Shortcodes;

/**
 * Class Sitemap
 *
 * Render a Sitemap shortcode by post type
 *
 * @package PressGang
 */
class Sitemap extends \Pressgang\Shortcode {

    /**
     * do_shortcode
     *
     * Render the shortcode
     *
     * @return string
     */
    public function do_shortcode($atts, $content = null) {

        $atts = shortcode_atts( array(
            'post_type' => 'page',
        ), $atts );

        $data['posts'] = \get_posts(array(
            'numberposts' => -1,
            'post_type' => $atts['post_type'],
            'post_status' => 'publish',
            'orderby' => 'menu_order',
            'order' => 'ASC',
            'fields' => 'ids',
        ));

        return \Timber::compile('sitemap-html.twig', $data, 24 * 60 * 60);
    }
}

new Sitemap();