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

        // add a block ID in case needed for front end
        static::$context['id'] = static::$id;

        if($fields = get_field_objects()) {
            foreach ($fields as $name => $field) {
                static::$context[$field['name']] = $field['value'];
            }
        }


        static::$context['css_class'] = isset($block['className']) ? $block['className'] : '';

        return static::$context;
    }


}