<?php

namespace PressGang;

require_once __DIR__ . '/../library/pluralizer.php';

/**
 * Trait CustomLabels
 *
 * This trait is shared between PressGang\CustomTaxonomies and Pressgang\CustomTaxonomies
 * It is used to automatically pluralize the singular values provided
 *
 * @package PressGang
 */
trait CustomLabels {

    /**
     * parse_labels
     *
     * @param $key - this is the Custom Post Type or Custom Taxonmy Key passed from the settings.php array
     * @param $args
     *
     * @retun $args - array used for registering the Custom Post Type or Custom Taxonomy
     */
    protected function parse_labels($key, $args) {

        $name = isset($args['name']) ? $args['name'] : $key;
        $name = __(ucwords(str_replace('_', ' ', $name)), THEMENAME);
        $plural = Pluralizer::pluralize($name);

        if  (!isset($args['name'])) {
            $args['name'] = $name;
        }

        if  (!isset($args['labels'])) {
            $args['labels'] = array();
        }

        $labels = array(
            'name' => $plural,
            'singular_name' => $name,
            'add_new_item' => __(sprintf("Add new %s", $name), THEMENAME),
            'search_items' =>  __(sprintf("Search %s", $name), THEMENAME),
            'all_items' => __(sprintf("All %s", $plural), THEMENAME),
            'edit_item' => __(sprintf("Edit %s", $name), THEMENAME),
            'update_item' => __(sprintf("Update %s", $name), THEMENAME),
            'new_item_name' => __(sprintf("New %s", $name), THEMENAME),
            'menu_name' => $plural,
        );

        $labels = wp_parse_args($args['labels'], $labels);

        $args['labels'] = $labels;

        return $args;
    }
}