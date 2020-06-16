<?php

namespace PressGang;

/**
 * Class Filters
 *
 * Add all actions here
 *
 * @package Filters
 */
class Filters
{

    /**
     * add
     *
     */
    public static function add()
    {
        add_filter('nav_menu_css_class', array('PressGang\Filters', 'add_custom_link_menu_item_classes'), 10, 2);
        add_filter('upload_mimes', array('PressGang\Filters', 'mime_types'));
        add_filter('gallery_style', array('PressGang\Filters', 'gallery_style'));
        if (in_array('single-page.php', Config::get('templates'))) {
            add_filter('_get_page_link', array('PressGang\Filters', 'single_page_permalink'), 10, 2);
            add_filter('the_permalink', array('PressGang\Filters', 'single_page_permalink'), 10, 2);
            add_filter('get_sample_permalink', array('PressGang\Filters', 'single_page_permalink'), 10, 2);
            // Hack fix for WPML trailing slashes
            add_filter('page_link', function($permalink) {

                if(substr($permalink, 0, 1 ) === '#') {
                    $permalink = rtrim($permalink,'/');
                }

                return $permalink;
            }, 10, 1);
        }
    }

    /**
     * Add .svg to mine types
     *
     * @param $mimes
     * @return mixed
     */
    public static function mime_types($mimes)
    {
        $mimes['svg'] = 'image/svg+xml';
        return $mimes;
    }

    /**
     * gallery_style
     *
     * Remove CSS for the Wordpress Gallery so we can do our own
     *
     * @param $existing_code
     */
    public static function gallery_style($existing_code)
    {
        return;
    }

    /**
     * single_page_layout_permalink
     *
     * Because the site uses a "single-page-layout", if a page has a parent with the template
     * 'single_page.php', then we want to #hash the child page links, instead of placing them in sub-folders.
     *
     * @param $permalink
     * @param $id
     * @return string
     */
    public static function single_page_permalink($permalink, $id)
    {
        if ($parent_id = wp_get_post_parent_id($id)) {
            if ($parent = get_post($parent_id)) {
                if (preg_match('/single-page.php$/', get_page_template_slug($parent_id))) {
                    $post = get_post($id);
                    if (is_array($permalink)) {
                        // this is for the admin sample permalink
                        $permalink = str_replace('/%pagename%', '#%pagename%', $permalink);
                    } else {
                        if( !is_admin() ) {
                            $the_id = get_the_ID();
                            if (in_array($parent_id, array($the_id, wp_get_post_parent_id($the_id)))) {
                                // link is relative to the current page
                                $permalink = "#{$post->post_name}";
                            }
                            else {
                            }
                        }
                        // else absolute
                        $permalink = preg_replace('/\/' . preg_quote($post->post_name, '/') . '\/?$/', "#{$post->post_name}", $permalink);
                    }
                }
            }
        }
        return $permalink;
    }

    /**
     * add_custom_link_menu_item_classes
     *
     * @param $classes
     * @param $item
     * @param array $args
     * @param int $depth
     * @return array
     */
    public static function add_custom_link_menu_item_classes($classes, $item)
    {
        if ($item->url === \Timber\URLHelper::get_current_url()) {
            $classes[] = 'active';
            $classes[] = 'current-menu-item';
        }

        return $classes;
    }
}

Filters::add();