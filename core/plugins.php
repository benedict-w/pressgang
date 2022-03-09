<?php

namespace PressGang;

/**
 * Class Plugins
 *
 * @package PressGang
 */
class Plugins {

    /**
     * plugins
     *
     * @var array|mixed
     */
    public static $plugins;

    /**
     * __construct
     *
     * Read the plugins from the config and display an admin warning if they are not active.
     *
     */
    public function __construct() {
        self::$plugins = Config::get('plugins');
        add_filter('admin_init', array('PressGang/Plugins', 'check_plugins_active'));
    }

    /**
     * check_plugins_active
     *
     * Check required plugins are active and display an admin warning if not.
     *
     */
    public static function check_plugins_active () {
        foreach(static::$plugins as $plugin => &$message) {
            if(is_plugin_active($plugin)) {
                $message = $message || sprintf("%s %s", $plugin, __(" not activated. Make sure you activate the plugin in ", THEMENAME));
                add_action('admin_notices', function() use ($message, $plugin) { ?>
                    <div class="error"><p><?php echo esc_html($message); ?><a href="<?php esc_url(admin_url("plugins.php#{$plugin}")); ?>"><?php esc_url(admin_url("plugins.php")); ?></a>.</p></div>
                <?php
                });
            }
        }
    }
}