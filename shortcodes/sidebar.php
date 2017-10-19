<?php

namespace PressGang\Shortcodes;

/**
 * Class Sidebar
 *
 * Render a Sidebar (widget area) from a shortcode by name
 *
 * @package PressGang
 */
class Sidebar extends \Pressgang\Shortcode {

    protected $defaults = array(
        'name' => null,
    );

    /**
     * do_shortcode
     *
     * Render the shortcode
     *
     * @return string
     */
    public function do_shortcode($atts, $content = null) {

        $args = shortcode_atts($this->get_defaults(), $atts);

        if (!empty($args['name'])) {

            $name = filter_var($args['name'], FILTER_SANITIZE_STRING);

            return \Timber::compile('sidebar-shortcode.twig', array(
                'content' => \Timber::get_widgets($name),
                'class' => "sidebar-{$name}",
            ));
        }

        return null;
    }
}

new Sidebar();