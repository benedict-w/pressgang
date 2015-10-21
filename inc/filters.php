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
}

Filters::add();