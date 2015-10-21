<?php

namespace PressGang;

/**
 * Class Actions
 *
 * Add all actions here
 *
 * @package PressGang
 */
class Actions {

    public static $actions = array();

    /**
     * add
     *
     */
    public static function add() {
        self::$actions = Config::get('actions');
        foreach (self::$actions as $key => &$args) {
            add_action($key, $args);
        }
    }
}

Actions::add();