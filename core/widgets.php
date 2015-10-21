<?php

namespace PressGang;

/**
 * Class Widgets
 *
 * @package PressGang
 */
class Widgets
{
    /**
     * widgets
     *
     * @var array
     */
    public static $widgets = array();

    /**
     * __construct
     *
     */
    public function __construct() {
        self::$widgets = Config::get('widgets');
        add_action('widgets_init', array('PressGang\Widgets', 'init'));
        add_filter('timber_context', array('PressGang\Widgets', 'add_to_context'));
    }

    /**
     * footer
     *
     * Register theme widgets, filter with 'widget_{$key}'
     *
     */
    public static function init() {
        foreach(static::$widgets as $key=>&$widget) {
            $widget = apply_filters("widget_{$key}", $widget);
            if (is_array($widget)) {
                register_sidebar(static::parse_args($widget));
            } else {
                unset(static::$widgets[$key]); // remove from Timber context binding
            }
        }
    }

    /**
     * parse_args
     *
     * Add our own defaults for the widget params
     *
     * @param $args
     * @return array
     */
    protected static function parse_args($args) {
        $defaults = array(
            'before_widget' => '',
            'after_widget' => '',
            'before_title' => '',
            'after_title' => '',
        );

        return wp_parse_args($args, $defaults);
    }

    /**
     * add_to_context
     *
     * Add available widgets to the Timber context
     *
     * @param $context
     *
     * @return array
     */
    public static function add_to_context($context) {
        foreach(static::$widgets as $key=>&$widget) {
            $context["widget_{$key}"] = \Timber::get_widgets($widget['id']);
        }
        return $context;
    }
}

new Widgets();