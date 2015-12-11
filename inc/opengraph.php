<?php

namespace PressGang;

/**
 * Class OpenGraph
 *
 * @package PressGang
 */
class OpenGraph {

    /**
     * init
     *
     */
    public static function init() {
        add_action('wp_head', array('PressGang\OpenGraph', 'fb_opengraph'), 5);
    }

    /**
     * fb_opengraph
     *
     */
    public static function fb_opengraph() {

        $post = new \TimberPost();

        $title = $post->title;

        $description = is_single()
            ? str_replace('', "'", $post->get_preview(60, true, false, true))
            : get_bloginfo('description', 'display');

        $img = has_post_thumbnail($post->id)
            ? wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'large')[0]
            : (get_theme_mod('og_img')
                ? get_theme_mod('og_img')
                : esc_url(get_theme_mod('logo')));

        $type = is_author() ? 'profile' : (is_single() ? 'article' : 'website');

        $url = get_permalink();
        $url = rtrim(esc_url(apply_filters('og_url', $url)));
        if (!substr($url, -1) === '/') {
            $url .= '/'; // slash fixes Facebook Debugger "Circular Redirect Path"
        }

        $open_graph = array(
            'site_name' => esc_attr(apply_filters('og_site_name', get_bloginfo())),
            'title' => esc_attr(apply_filters('og_title', $title)),
            'description' => esc_attr(apply_filters('og_description', $description)),
            'type' => esc_attr(apply_filters('og_type', $type)),
            'url' => $url,
            'image' => esc_url(apply_filters('og_image', $img)),
        );

        \Timber::render('open-graph.twig', array('open_graph' => $open_graph));
    }
}

OpenGraph::init();