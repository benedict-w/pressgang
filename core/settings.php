<?php

/**
 * Copy this settings file to child themes for specific configuration
 *
 */
return array (

    /*
     * files
     * 
     * Array of files to include in the theme from the '/inc' directory using the Loader class
     *
     */
    'inc' => array(),

    /*
     * menus
     *
     * Array representing each Menu in the theme.
     *
     * @var array
     */
    'menus' => array(),

    /*
     * widgets
     *
     * Array representing each widget sidebar used in the theme.
     *
     * @var array
     */
    'widgets' => array(),

    /*
     * actions
     *
     * Array representing functions to hook on given actions
     *
     * @var array
     */
    'actions' => array(),

    /*
     * scripts
     *
     * Array of scripts on $handle => $args array where $args match wp_register_script, wit additional 'hook' param
     * for the action to enque the script on (default = wp_enqueue_scripts)
     *
    */
    'scripts' => array(
        'bootstrap' => array(
            'src' => get_template_directory_uri() . '/js/build/bootstrap.min.js',
            'deps' => array('jquery'),
            'version' => '3.2.0',
            'in_footer' => true
        ),
    ),

    /*
     * support
     *
     * Include theme support
     *
     * @var array
     */
    'support' => array(
        'html5',
        'post-thumbnails',
    ),

    /*
     * plugins
     *
     * Array of plugins required by the theme (displays admin warning if plugin not activated)
     *
     * Use names of the plugin sub-directory/file.
     * See http://codex.wordpress.org/Function_Reference/is_plugin_active
     *
     * Optionally array values supply the admin warning message
     *
     */
    'plugins' => array(
        'Timber',  // Timber is required for the theme templating!
    ),
);
