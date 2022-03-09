<?php

namespace PressGang;

class CustomPostType {

    public static $post_type = '';

    /**
     * init
     *
     * Init the CPT
     *
     * @param $post_type
     * @param array $args
     */
    public function __construct($post_type, $args = array()) {
        if (!static::$post_type) { // allow only one instance
            static::$post_type = $post_type;
            Config::$settings['custom-post-types'][$post_type] = $args;
        }
    }

    /**
     * get
     *
     * Gets custom posts - calls get_posts for the current post type
     *
     * @param array $args
     * @return array
     */
    public static function get_posts($args = array()) {
        $defaults = array(
            'post_type'=> static::$post_type
        );

        $args = wp_parse_args($args, $defaults);

        return get_posts($args);
   }

}