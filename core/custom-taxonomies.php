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
    public static $custom_taxonomies = array();

    /**
     * __construct
     *
     */
    public function __construct() {
        add_action('init', array('PressGang\CustomTaxonomies', 'init'));
    }

    /**
     * footer
     *
     * Register theme widgets, filter with 'widget_{$key}'
     *
     */
    public static function init() {
        self::$custom_taxonomies = Config::get('custom-taxonomies');

        foreach(static::$custom_taxonomies as $key=>&$options) {

            $args = array();

            if (isset($options['args'])) {
                $args = wp_parse_args($options['args'], $args);
            }

            $key = apply_filters("pressgang_taxonomy_{$key}", $key);
            $args = apply_filters("pressgang_taxonomy_{$key}_args", $args);
            register_taxonomy($key, $options['object-type'], $args);
        }
    }
}

new CustomTaxonomies();