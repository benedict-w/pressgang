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
        add_filter('timber_context', array($this, 'add_to_timber_context'));
    }

    /**
     * init
     *
     */
    public function init() {
        $this->options = Config::get('options');

        foreach ($this->options as $i => &$options) {

            // default to ACF options page
            if (function_exists('acf_add_options_page')) {
                // See - https://www.advancedcustomfields.com/resources/acf_add_options_page/
                \acf_add_options_page($options);
            } else {

                // TODO fix errors
                // add_action('admin_menu', array($this, 'add_options_pages'));
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

    /**
     * add_to_timber_context
     *
     * TODO is this possible per options page?
     *
     * @param $context
     * @return mixed
     */
    public function add_to_timber_context( $context ) {
        $context['options'] = get_fields('option');
        return $context;
    }

}

new Options();
