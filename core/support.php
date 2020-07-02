<?php

namespace PressGang;

/**
 * Class Support
 *
 * @package PressGang
 */
class Support {

    /**
     * init
     *
     */
    public static function init() {

        foreach (Config::get('support') as $key => &$value) {

            if (is_numeric($key)) {
                add_theme_support($value);
            } elseif (is_array($value)) {
                add_theme_support($key, $value);
            }
        }
    }
}

Support::init();