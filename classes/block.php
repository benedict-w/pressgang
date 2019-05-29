<?php

namespace PressGang;

class Block
{
    protected static $id = null;
    protected static $context = array();

    /**
     * render
     *
     * @param $block
     */
    public static function render ($block) {
        // convert name into path friendly slug
        $slug = substr($block['name'], strpos($block['name'], '/') + 1, strlen($block['name']));

        static::$id = $block['id'];

        $context = static::get_context($block);

        \Timber::render("blocks/{$slug}.twig", $context);
    }

    /**
     * get_context
     *
     * @param $block
     * @return array
     */
    public static function get_context($block) {

        // clear each static context
        static::$context = array();

        // add a reference to the post
        static::$context['post'] = new \TimberPost();

        foreach(static::get_acf_fields($block) as $name => $value) {
            static::$context[$name] = $value;
        }

        return static::$context;
    }

    /**
     * get_acf_fields
     *
     * @param $block
     */
    public static function get_acf_fields($block) {

        $fields = array();

        if (!empty($block['data'])) {
            $fields = static::get_fields_from_keys($block['data']);
        }

        return $fields;

    }

    /*
     * get_fields_from_keys
     *
     * Recursively check for ACF field keys
     *
     * DEPRECATE? Use for ACF 5.8 ALPHA
     *
     * @param $data
     * @return array
     *
    protected static function get_fields_from_keys($data) {

        $fields = array();

        foreach ($data as $key => &$value) {

            if (substr($key, 0, 1) === '_') {
                $field = \get_field_object(ltrim($key, '_'));
                if ($field) {
                    $fields[$field['name']] = $field['value'];
                }

            }
        }

        return $fields;
    }
    */

    /**
     * get_fields_from_keys
     *
     * Recursively check for ACF field keys
     *
     * @param $data
     * @return array
     */
    protected static function get_fields_from_keys($data) {
        $fields = array();
        foreach ($data as $key => $value) {
            $fields[self::get_acf_field_name($key)] = is_array($value)
                ? self::get_fields_from_keys($value)
                : $value;
        }
        return $fields;
    }
    /**
     * get_acf_field_name
     *
     * @param $key
     * @param $value
     */
    protected static function get_acf_field_name($key) {
        if (substr($key, 0, 6) === 'field_') {
            $field = get_field_object($key);
            $key = $field['name'];
        }
        return $key;
    }

}