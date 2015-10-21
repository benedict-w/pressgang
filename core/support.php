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
        foreach (Config::get('support') as &$support) {
            add_theme_support($support);
        }
    }
}

Support::init();