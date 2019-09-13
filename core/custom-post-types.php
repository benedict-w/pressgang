<?php

namespace PressGang;

require_once __DIR__ . '/../classes/custom-labels-trait.php';

/**
 * Class CustomPostTypes
 *
 * @package PressGang
 */
class CustomPostTypes
{
    use CustomLabels;

    /**
     * custom_post_types
     *
     * @var array
     */
    public $custom_post_types = array();

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
        $this->custom_post_types = Config::get('custom-post-types');

        foreach($this->custom_post_types as $key=>&$args) {

            $args = $this->parse_labels($key, $args);

            $key = apply_filters("pressgang_cpt_{$key}", $key);
            $args = apply_filters("pressgang_cpt_{$key}_args", $args);
            register_post_type($key, $args);
        }
    }
}

new CustomPostTypes();