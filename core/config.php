<?php

namespace PressGang;

/**
 * Class Config
 *
 * @package PressGang
 */
class Config {

    public static $settings = array();

    /**
     * Get the config array
     *
     * @param $key - optionally specify a setting param to return
     *
     * @return array|mixed
     */
    public static function get($key = null) {
        if(!self::$settings) {

            $parent = require_once TEMPLATEPATH . '/core/settings.php';

            $child = file_exists(STYLESHEETPATH . '/core/settings.php')
                ? require_once STYLESHEETPATH . '/core/settings.php'
                : array();

            self::$settings = apply_filters('pressgang_get_settings', array_merge($parent, $child));
        }

        if ($key) {
            if (isset(static::$settings[$key])) {
                return static::$settings[$key];
            }

            return null;
        }

        return static::$settings;
    }
}