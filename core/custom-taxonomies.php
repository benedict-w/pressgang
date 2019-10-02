<?php

namespace PressGang;

require_once __DIR__ . '/../classes/custom-labels-trait.php';

/**
 * Class CustomTaxonomies
 *
 * @package PressGang
 */
class CustomTaxonomies
{
    use CustomLabels;

    /**
     * custom_taxonomies
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

        foreach($this->custom_taxonomies as $key=>&$args) {

            $args = $this->parse_labels($key, $args);

            $key = apply_filters("pressgang_taxonomy_{$key}", $key);
            $args = apply_filters("pressgang_taxonomy_{$key}_args", $args);

            $object_type = isset($args['object-type']) ? $args['object-type'] : 'post';

            register_taxonomy($key, $object_type, $args);
        }
    }
}

new CustomTaxonomies();