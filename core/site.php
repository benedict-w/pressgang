<?php

namespace PressGang;

/**
 * Class Site
 *
 * @package PressGang
 */
class Site extends \TimberSite
{
    public $stylesheet;
    public $keywords;
    public $logo;
    public $copyright;
    public $open_graph;

    /**
     *__construct
     *
     * @param string|int $site_name_or_id
     */
    function __construct($site_name_or_id = null)
    {
        parent::__construct($site_name_or_id);

        $this->stylesheet = get_theme_mod('stylesheet', 'styles.css');

        // add custom params
        $this->keywords = apply_filters('site_keywords', implode(', ', array_map(function ($tag) {
            return $tag->name;
        }, get_tags(array('orderby' => 'count', 'order' => 'DESC', 'number' => 20)))));

        $this->logo = apply_filters('site_logo', get_theme_mod('logo'));
        $this->copyright = apply_filters('site_copyright', get_theme_mod('copyright'));

        // replace the site icon with an image object
        if ($this->site_icon) {
            $this->site_icon = new \TimberImage($this->site_icon);
        }

        $this->add_open_graph();
    }

    /**
     * add_open_graph
     *
     * Add custom open_graph params
     *
     */
    protected function add_open_graph()
    {
        global $wp;

        $this->open_graph = array();

        $post = new \TimberPost();

        $img = has_post_thumbnail($post->id)
            ? wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'medium')[0]
            : (get_theme_mod('og_img')
                ? get_theme_mod('og_img')
                : esc_url(get_theme_mod('logo')));

        $type = is_author() ? 'profile' : (is_single() ? 'article' : 'website');

        $this->open_graph['site_name'] = esc_attr(apply_filters('og_site_name', get_bloginfo()));
        $this->open_graph['title'] = esc_attr(apply_filters('og_title', $this->title));
        $this->open_graph['description'] = esc_attr(apply_filters('og_description', $this->description));
        $this->open_graph['type'] = esc_attr(apply_filters('og_type', $type));
        $this->open_graph['url'] = rtrim(esc_url(apply_filters('og_url', home_url(add_query_arg(array(), $wp->request)))), '/') . '/'; // slash fixes Facebook Debugger "Circular Redirect Path"
        $this->open_graph['image'] = esc_url(apply_filters('og_image', $img));
    }
}
