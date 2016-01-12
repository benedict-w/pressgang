<?php

namespace PressGang;

/**
 * Class Filters
 *
 * Add all actions here
 *
 * @package Filters
 */
class Filters {

    /**
     * add
     *
     */
    public static function add() {
        add_filter('upload_mimes', array('PressGang\Filters', 'mime_types'));
        add_filter('gallery_style' , array('PressGang\Filters', 'gallery_style'));
    }

    /**
     * Add .svg to mine types
     *
     * @param $mimes
     * @return mixed
     */
    public static function mime_types($mimes) {
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
    function gallery_style($existing_code) {
        return;
    }
}

Filters::add();