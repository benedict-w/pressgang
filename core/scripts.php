<?php

namespace PressGang;

class Scripts {

    /**
     * scripts
     *
     * @var array|mixed
     */
    public static $scripts;

    /**
     * __construct
     *
     * Adds scripts from the settings file to be enqueued on the given hooks (default = 'wp_enqueue_scripts')
     *
     * See - https://codex.wordpress.org/Plugin_API/Action_Reference/wp_enqueue_scripts
     *
     */
    public function __construct() {
        static::$scripts = Config::get('scripts');
        add_action('init', array('PressGang\Scripts', 'register_scripts'));
    }

    /**
     * register_scripts
     *
     * See - https://codex.wordpress.org/Function_Reference/wp_register_script
     *
     */
    public static function register_scripts () {
        foreach(static::$scripts as $key => &$args) {

            $defaults = array (
                'handle' => $key,
                'src' => '',
                'deps' => array(),
                'ver' => false,
                'in_footer' => false,
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
                add_action($args['hook'], function () use ($args) {
                    wp_register_script($args['handle'], $args['src'], $args['deps'], $args['ver'], $args['in_footer']);
                });

                // enqueue on given hook
                add_action($args['hook'], function () use ($args) {
                    wp_enqueue_script($args['handle']);
                });
            }
        }
    }

}

new Scripts();