<?php

namespace Pressgang;

class Options
{
    protected $options;

    /**
     * __construct
     *
     *
     */
    public function __construct()
    {
        add_action('init', array($this, 'init'));
    }

    /**
     * init
     *
     */
    public function init() {
        $this->options = Config::get('options');

        foreach ($this->options as $i => &$options) {

            // default to ACF options page
            if (true) {
                // See - https://www.advancedcustomfields.com/resources/acf_add_options_page/
                acf_add_options_page($options);
            } else {
                add_action('admin_menu', array($this, 'add_options_pages'));
            }
        }
    }

    /**
     * add_options_pages
     *
     * Add options page
     *
     * See https://codex.wordpress.org/Function_Reference/add_options_page
     *
     */
    public function add_options_pages()
    {
        foreach($this->options as $key=>&$options) {

            add_options_page(
                $options['title'], // title
                isset($options['menu-title']) ? $options['menu-title'] : $options['title'], // menu title
                isset($options['capability']) ? $options['capability'] : 'manage_theme', // capability
                $key, // slug
                function() use ($key) { // callback
                    $this->create_admin_page($key);
                }
            );
        }
    }

    /**
     * create_admin_page
     *
     * Options page callback
     */
    public function create_admin_page($key) {
        \Timber::render('admin/settings.twig', array(
            'title' => $this->options[$key]['title'],
            'key' => $key,
        ));
    }
}

new Options();