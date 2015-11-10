<?php

namespace PressGang;

require_once __DIR__ . '/../classes/custom-post-type.php';
require_once __DIR__ . '/../classes/metabox.php';

/**
 * Class Slider
 *
 * Adds a bootstrap carousel slider as a custom post type
 *
 * This is useful for adding a main slider to a site homepage

 * See - https://getbootstrap.com/javascript/#carousel
 * See - https://codex.wordpress.org/Post_Types
 * See - https://codex.wordpress.org/Function_Reference/register_post_type
 *
 * @package PressGang
 */
class Slider extends CustomPostType {

    /**
     * __construct
     *
     * @param string $post_type
     * @param array $args
     */
    public function __construct($post_type = 'slide', $args = array()) {

        $args = array(
            'description'          => __("Add sliders to the site carousel.", THEMENAME),
            'exclude_from_search'  => true,
            'publicly_queryable'   => false,
            'show_ui'              => true,
            'show_in_menu'         => true,
            'show_in_nav_menus'    => false,
            'show_in_admin_bar'    => true,
            'supports'             => array('title', 'editor', 'thumbnail',),
            'rewrite'              => false,
            'query_var'            => false,
            'can_export'           => true,
        );

        parent::__construct($post_type, $args);

        // register shortcode to render the slider carousel
        add_shortcode('slider', array('PressGang\Slider', 'render'));

        // add scripts to loader queue
        Scripts::$scripts['focuspoint'] = array(
            'src' => get_template_directory_uri() . '/js/vendor/focuspoint/jquery.focuspoint.js',
            'deps' => array('jquery'),
            'ver' => '1.0.3b',
            'in_footer' => true
        );

        // load pressgang which inits the focus point
        Scripts::$scripts['pressgang'] = array(
            'src' => get_template_directory_uri() . '/js/custom/pressgang.js',
            'deps' => array('focuspoint'),
            'ver' => '0.1',
            'in_footer' => true
        );

        self::add_link_metabox();
        self::add_focus_point_metabox();
    }

    /**
     * add_link_metabox
     *
     * Adds a metabox to give the slider a hyperlink
     *
     */
    private static function add_link_metabox() {

        $link = array(
            'id' => 'slide-url',
            'name' => 'slide_url',
            'label' => __("Slide Link", THEMENAME),
            'placeholder' => __("Absolute or relative URL", THEMENAME),
            'class' => 'widefat',
            'type' => 'text',
        );

        new MetaBox('slider-metabox', self::$post_type, __("Add a slide link", THEMENAME), array($link));
    }

    /**
     * add_focus_point_metabox
     *
     */
    private static function add_focus_point_metabox() {

        $x_focus = array(
            'id' => 'x-focus',
            'name' => 'x_focus',
            'label' => __("X-axis focus point in pixels (0px is center)", THEMENAME),
            'placeholder' => "0",
            'class' => '',
            'type' => 'text',
        );

        $y_focus = array(
            'id' => 'y-focus',
            'name' => 'y_focus',
            'label' => __("Y-axis focus point in pixels (0px is center)", THEMENAME),
            'placeholder' => "0",
            'class' => '',
            'type' => 'text',
        );

        new MetaBox('focuspoint-metabox', self::$post_type, __("Add a focal point for responsive image cropping", THEMENAME), array($x_focus, $y_focus));
    }

    /**
     * Render the carousel template
     *
     */
    public static function render($atts) {

        $atts = shortcode_atts(
            array(
                'height' => 500,
                'width' => 1140,
            ), $atts);

        // uniquely identify each slider on the page
        static $id = 0;
        $id++;

        // TODO enqueue bootstrap JS?

        $slides = array();
        foreach(parent::get_posts() as $slide) {
            $slide = new \TimberPost($slide);
            $slide->height = $atts['height'];
            $slide->width = $atts['width'];
            $slides[] = $slide;
        }

        $carousel['id'] = sprintf("%s-carousel-%s", self::$post_type, $id);
        $carousel['slides'] = $slides;

        if (count($slides)) {
            \Timber::render('carousel.twig', $carousel);
        }
    }
}

new Slider();