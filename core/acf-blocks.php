<?php

namespace PressGang;

/**
 * Class AcfBlocks
 *
 * @package PressGang
 */
class AcfBlocks
{
    protected $custom_categories = array();

    /**
     * __construct
     *
     */
    public function __construct()
    {
        add_action('acf/init', array($this, 'setup'));
        add_filter('block_categories', array($this, 'add_custom_categories'));
    }

    /**
     * setup
     *
     */
    public function setup()
    {
        if (function_exists('acf_register_block')) {

            $blocks = Config::get('acf-blocks');

            foreach ($blocks as $key => &$args) {

                // when category is an array use it to register custom categories
                // otherwise expect category to be the slug for a default gutenberg category
                if (is_array($args['category'])) {

                    $this->custom_categories[$args['category']['slug']] = $args['category'];
                    $args['category'] = $args['category']['slug'];

                }

                acf_register_block($args);
                $inc = preg_match('/.php/', $key) ? "blocks/{$key}" : "blocks/{$key}.php";
                locate_template($inc, true, true);

            }
        }

    }

    /**
     * add_custom_categories
     *
     * @param $categories
     * @param $post
     * @return array
     */
    public function add_custom_categories($categories) {

        return array_merge($categories, $this->custom_categories);

    }
}

new AcfBlocks();