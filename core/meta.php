<?php

namespace PressGang;

/**
 * Class Meta
 *
 * Adds HTML Meta tags via wp_head
 *
 * @package PressGang
 */
class Meta {

    /**
     * meta
     *
     * @var array
     */
    protected $meta = array();

    /**
     * __construct
     *
     * Register Menus
     *
     */
    public function __construct() {
        $this->meta = Config::get('meta');
        add_action('wp_head', array($this, 'add_meta_tags'));
    }

    /**
     * add_meta_tags
     *
     * @hooked wp_head
     */
    public function add_meta_tags() {
        foreach($this->meta as $name => &$content ) {
            echo sprintf('<meta name="%s" content="%s">', esc_attr($name), esc_attr($content));
        }
    }

}

new Meta();