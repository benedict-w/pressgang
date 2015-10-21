<?php

namespace PressGang;

/**
 * Class Site
 *
 * @package PressGang
 */
class Site extends \TimberSite {

    public $keywords;
    public $logo;
    public $copyright;

    /**
     *__construct
     *
     * @param string|int $site_name_or_id
     */
    function __construct( $site_name_or_id = null ) {
        parent::__construct($site_name_or_id);
        $this->init();
    }

    /**
     * init
     *
     */
    protected function init() {
        $this->keywords = apply_filters('site_keywords', implode(', ', array_map(function ($tag) { return $tag->name; }, get_tags(array('orderby' => 'count', 'order' => 'DESC', 'number' => 20)))));
        $this->logo = apply_filters('site_logo', get_theme_mod('logo'));
        $this->copyright = apply_filters('site_copyright', get_theme_mod('copyright'));
    }
}
