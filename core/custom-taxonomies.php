<?php

namespace PressGang;

require_once __DIR__ . '/../library/pluralizer.php';

/**
 * Class CustomTaxonomies
 *
 * @package PressGang
 */
class CustomTaxonomies
{
    /**
     * widgets
     *
     * @var array
     */
    public $custom_taxonomies = array();

    /**
     * __construct
     *
     */
    public function __construct() {
        add_action('init', array($this, 'init'), 5);
    }

    /**
     * footer
     *
     * Register theme widgets, filter with 'widget_{$key}'
     *
     */
    public function init() {
        $this->custom_taxonomies = Config::get('custom-taxonomies');

        foreach($this->custom_taxonomies as $key=>&$options) {

            // TODO DRY - also in custom-post-types.php
            $name = __(ucwords(str_replace('_', ' ', $key)), THEMENAME);
            $plural = Pluralizer::pluralize($name);

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

            $args = isset($options['args']) ? $options['args'] : array();

            $args = wp_parse_args($args, $defaults);

            $object_type = isset($options['object-type']) ? $options['object-type'] : 'post';

            $key = apply_filters("pressgang_taxonomy_{$key}", $key);
            $args = apply_filters("pressgang_taxonomy_{$key}_args", $args);
            register_taxonomy($key, $object_type, $args);
        }
    }
}

new CustomTaxonomies();