<?php

namespace PressGang\Shortcodes;

/**
 * Class GoogleMap
 *
 * @package PressGang
 */
class GoogleMap extends \Pressgang\Shortcode {

    protected $defaults = array(
        'api' => null,
        'lat' => 0,
        'lng' => 0,
        'zoom' => 10,
    );

    /**
     * do_shortcode
     *
     * Render the shortcode template
     *
     * @return string
     */
    public function do_shortcode($atts, $content = null) {

        $args = shortcode_atts($this->get_defaults(), $atts);

        wp_register_script('pressgang-google-map', get_template_directory_uri() . '/js/src/custom/google-map.js');
        wp_enqueue_script('pressgang-google-map');

        // TODO defer - http://matthewhorne.me/defer-async-wordpress-scripts/
        wp_register_script('google-maps', sprintf('https://maps.googleapis.com/maps/api/js?key=%s&callback=initMap', $args['api']), array('jquery', 'pressgang-google-map'));
        wp_enqueue_script('google-maps');

        \Pressgang\Scripts::$defer[] = 'google-maps';
        \Pressgang\Scripts::$async[] = 'google-maps';
        \Pressgang\Scripts::$async[] = 'pressgang-google-map';

        static $i;
        $i++;
        $args['id'] = sprintf("google-maps-%d", $i);

        $this->context = $args;

        return \Timber::compile($this->template, $this->context);
    }
}

new GoogleMap();