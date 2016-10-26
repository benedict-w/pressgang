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

        $img = has_post_thumbnail($post->ID)
            ? wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'large')[0]
            : (get_theme_mod('og_img')
                ? get_theme_mod('og_img')
                : esc_url(get_theme_mod('logo')));

        $type = is_author() ? 'profile' : (is_single() ? 'article' : 'website');

        $description = get_bloginfo('description', 'display');

        if (is_tax()) {
            $url = get_term_link(get_query_var('term'), get_query_var('taxonomy'));
            $title = single_term_title('', false);
            if ($temp = term_description(get_queried_object(), get_query_var('taxonomy'))) {
                $description = $temp;
            }
        }
        elseif(is_post_type_archive()) {
            $url = get_post_type_archive_link(get_query_var('post_type'));
            $title = get_the_archive_title();
            if ($temp = get_the_archive_description()) {
                $description = $temp;
            }
        }
        else {
            $url = get_permalink();
            $title = get_the_title();
            $description = $post->get_preview(60, true, false, true);
        }

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