<?php

namespace PressGang;

require_once __DIR__ . '/../library/pluralizer.php';

/**
 * Class CustomPostTypes
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

            // TODO DRY - also in custom-taxonomies.php

            $name = isset($args['name']) ? $args['name'] : $key;
            $name = __(ucwords(str_replace('_', ' ', $name)), THEMENAME);
            $plural = Pluralizer::pluralize($name);

            if  (!isset($args['name'])) {
                $args['name'] = $name;
            }

            $defaults = array(
                'labels' =>  array(
                    'name' => $plural,
                    'singular_name' => $name,
                    'add_new_item' => __(sprintf("Add new %s", $name), THEMENAME),
                    'search_items' =>  __(sprintf("Search %s", $name), THEMENAME),
                    'all_items' => __(sprintf("All %s", $plural), THEMENAME),
                    'edit_item' => __(sprintf("Edit %s", $name), THEMENAME),
                    'update_item' => __(sprintf("Update %s", $name), THEMENAME),
                    'new_item_name' => __(sprintf("New %s", $name), THEMENAME),
                    'menu_name' => $plural,
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