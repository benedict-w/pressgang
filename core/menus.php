<?php

namespace PressGang;

/**
 * Class Menus
 *
 * @package PressGang
 */
class Menus {

    /**
     * menus
     *
     * @var array
     */
    public static $menus = array();

    /**
     * __construct
     *
     * Register Menus
     *
     */
    public function __construct() {
        self::$menus = Config::get('menus');
        add_action( 'init', array('PressGang\Menus', 'register') );
        add_filter( 'timber_context', array('PressGang\Menus', 'add_to_context' ) );
    }

    /**
     * register
     *
     * Register theme menus, filter on 'menu_{$key}'
     *
     */
    public static function register() {
        foreach(static::$menus as $location=>&$description) {
            $menu = apply_filters("menu_{$location}", array(
                $location => $description,
            ));

            if (!$menu) {
                unset(static::$menus[$location]); // remove from timber context bindings
            }
        }
        register_nav_menus(static::$menus);
    }

    /**
     * add_to_context
     *
     * Add available menus to the Timber context
     *
     * @param $context
     *
     * @return array
     */
    public static function add_to_context($context) {
        foreach(static::$menus as $location=>&$description) {
            $context["menu_{$location}"] = new \TimberMenu($location);
        }
        return $context;
    }

}

new Menus();