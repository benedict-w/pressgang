<?php

namespace PressGang;

require_once __DIR__ . '/../library/pluralizer.php';

/**
 * Class Widgets
 *
 * @package PressGang
 */
class CustomPostTypes
{
    /**
     * widgets
     *
     * @var array
     */
    public static $custom_post_types = array();

    /**
     * __construct
     *
     */
    public function __construct() {
        add_action('init', array('PressGang\CustomPostTypes', 'init'));
    }

    /**
     * footer
     *
     * Register theme widgets, filter with 'widget_{$key}'
     *
     */
    public static function init() {
        self::$custom_post_types = Config::get('custom-post-types');

        foreach(static::$custom_post_types as $key=>&$args) {

            $name = __(ucfirst($key), THEMENAME);

            $defaults = array(
                'labels' =>  array(
                    'name' => Pluralizer::pluralize($name),
                    'singular_name' => $name,
                    'add_new_item' => __(sprintf("Add new %s", ucfirst($key)), THEMENAME),
                ),
            );

            $args = wp_parse_args($args, $defaults);

            $key = apply_filters("pressgang_cpt_{$key}", $key);
            $args = apply_filters("pressgang_cpt_{$key}_args", $args);
            register_post_type($key, $args);
        }
    }
}

new CustomPostTypes();