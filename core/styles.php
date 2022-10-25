<?php

namespace PressGang;

class Styles {

    /**
     * Styles
     *
     * @var array|mixed
     */
    public static $styles = array();

    public static $async = array();
    public static $defer = array();

    /**
     * __construct
     *
     * Adds scripts from the settings file to be enqueued on the given hooks (default = 'wp_enqueue_scripts')
     *
     * See - https://codex.wordpress.org/Plugin_API/Action_Reference/wp_enqueue_scripts
     *
     */
    public function __construct() {
        static::$styles = Config::get('styles');
        add_action('init', array('PressGang\Styles', 'register_styles'));
    }

    /**
     * register_styles
     *
     * See - https://codex.wordpress.org/Function_Reference/wp_register_style
     *
     */
    public static function register_styles () {
        foreach(static::$styles as $key => &$args) {

            $defaults = array (
                'handle' => $key,
                'src' => '',
                'deps' => array(),
                'ver' => false,
                'media' => 'all',
                'hook' => 'wp_enqueue_scripts',
            );

            if (is_string($args)) {
                // TODO could validate URL?
                // TODO could - get_template_directory_uri
                $args['src'] = $args;
            }

            if (is_array($args)) {
                $args = wp_parse_args($args, $defaults);
            }

            if (isset($args['src']) && $args['src']) {

                // register scripts
                add_action('wp_loaded', function () use ($args) {
                    // TODO filemtime()
                    $ver = isset($args['version']) ? $args['version'] : (isset($args['ver']) ? $args['ver'] : '1.0.0');
                    wp_register_style($args['handle'], $args['src'], $args['deps'], $ver, $args['media']);
                });

                // enqueue on given hook
                add_action($args['hook'], function () use ($args) {
                    wp_enqueue_style($args['handle']);
                });
            }
        }
    }
}

new Styles();