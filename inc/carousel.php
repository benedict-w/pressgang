<?php

namespace PressGang;

require_once __DIR__ . '/../inc/slick.php';

/**
 * Class Carousel
 *
 * Renders carousel sliders, using Bootstrap (default) or Slick.js (Optional)
 *
 * See -
 * https://getbootstrap.com/javascript/#carousel
 * http://kenwheeler.github.io/slick/
 *
 * @package PressGang
 */
class Carousel {

    public function __construct()
    {

        // TODO queue bootstrap carousel?

        Scripts::$scripts['slick'] = array(
            'src' => get_template_directory_uri() . '/js/src/vendor/slick/slick.js',
            'deps' => array('jquery'),
            'ver' => '1.6.0',
            'in_footer' => true
        );

    }

    /**
     * Render the carousel template
     *
     */
    public static function render($template = 'carousel.twig', $query = array(), $width = 1140, $height = 500, $slick = array()) {

        if (!isset($query['post_type'])) {
            $query['post_type'] = 'slide';
        }

        // uniquely identify each slider on the page
        static $slider_id = 0;
        $slider_id ++;

        $slides = \Timber::get_posts($query);

        foreach($slides as &$slide) {
            $slide->height = $height;
            $slide->width = $width;

            apply_filters('slick_slide', $slide);
        }

        $carousel['id'] = sprintf("%s-carousel-%s", $query['post_type'], $slider_id);
        $carousel['slides'] = $slides;

        if (count($slides)) {
            if ($slick) {
                $carousel['options'] = $slick;
            }

            return \Timber::compile($template, $carousel);
        }

    }
}

new Carousel();