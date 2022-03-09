<?php

namespace PressGang;

/**
 * Class Sidebars
 *
 * @package PressGang
 */
class Sidebars
{
    /**
     * sidebars
     *
     * @var array
     */
    protected $sidebars = array();

    /**
     * __construct
     *
     */
    public function __construct() {
        add_theme_support('widgets');
        $this->sidebars = Config::get('sidebars');
        add_action('widgets_init', array($this, 'init'));
        add_filter('timber/twig', array($this, 'add_widget_functions'));
    }

    /**
     * footer
     *
     * Register theme sidebars, filter with 'widget_{$key}'
     *
     */
    public function init() {
        foreach($this->sidebars as $key=>&$sidebar) {
            $sidebar = apply_filters("widget_{$key}", $sidebar);
            if (is_array($sidebar)) {
                register_sidebar($this->parse_args($sidebar));
            } else {
                unset($this->sidebars[$key]); // remove from Timber context binding
            }
        }
    }

    /**
     * parse_args
     *
     * Add our own defaults for the sidebar params
     *
     * @param $args
     * @return array
     */
    public function parse_args($args) {
        $defaults = array(
            'before_widget' => '',
            'after_widget' => '',
            'before_title' => '',
            'after_title' => '',
        );

        return wp_parse_args($args, $defaults);
    }

    /**
     * add_to_context
     *
     * Add available sidebars to the Timber context
     *
     * @param $twig
     *
     * @return  \Twig\Environment
     */
    public function add_widget_functions(\Twig\Environment $twig) {
        foreach($this->sidebars as $key=>&$sidebar) {

            $twig->addFunction( new \Timber\Twig_Function( "widget_{$key}", function() use ($sidebar) {
                return \Timber::get_widgets($sidebar['id']);
            }));
        }

        return $twig;
    }

}

new Sidebars();