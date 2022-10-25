<?php

namespace PressGang;

class Scripts {

    /**
     * scripts
     *
     * @var array|mixed
     */
    public static $scripts = array();

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
        static::$scripts = Config::get('scripts');
        add_action('init', array('PressGang\Scripts', 'register_scripts'));
        add_action('init', array($this, 'modify_jquery'));
        add_filter('script_loader_tag', array('PressGang\Scripts', 'add_script_attrs'), 10, 3);
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
                'defer' => false,
                'async' => false,
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

                // TODO filemtime()
                $ver = isset($args['version']) ? $args['version'] : (isset($args['ver']) ? $args['ver'] : '1.0.0');

                // register scripts
                add_action('wp_loaded', function () use ($args, $ver) {
                    wp_register_script($args['handle'], $args['src'], $args['deps'], $ver, $args['in_footer']);
                });

                // enqueue on given hook
                add_action($args['hook'], function () use ($args, $ver) {
                    wp_enqueue_script($args['handle'], $args['src'], $args['deps'], $ver, $args['in_footer']);
                }, 20);
            }

            if ($args['defer']) {
                static::$defer[] = $key;
            }

            if ($args['async']) {
                static::$async[] = $key;
            }

        }
    }

    /**
     * add_defer
     *
     * @param $tag
     * @param $handle
     * @return mixed
     */
    public static function add_script_attrs($tag, $handle)
    {
        if (in_array($handle, Scripts::$defer)) {
            $tag = str_replace(' src', ' defer="defer" src', $tag);
        }

        if (in_array($handle, Scripts::$async)) {
            $tag = str_replace(' src', ' async="async" src', $tag);
        }

        return $tag;
    }

    /**
     * modify_jquery
     *
     * load a newer version of jquery for bootstrap 4 etc.
     */
    public function modify_jquery() {
        if (!is_admin()) {
            add_action('wp_enqueue_scripts', function() {
                wp_deregister_script('jquery-core');
                wp_register_script('jquery-core', "https://code.jquery.com/jquery-3.5.1.min.js", array(), '3.5.1');

                wp_deregister_script( 'jquery-migrate' );
                wp_register_script('jquery-migrate', "https://code.jquery.com/jquery-migrate-3.3.1.min.js", array(), '3.3.1');
            }, 0);
        }
    }

}

new Scripts();