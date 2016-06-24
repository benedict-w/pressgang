<?php

namespace PressGang;

require_once __DIR__ . '/../classes/custom-post-type.php';
require_once __DIR__ . '/../classes/metabox.php';
require_once __DIR__ . '/../inc/slick.php';
require_once __DIR__ . '/../inc/carousel.php';

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

        self::add_link_metabox();

        // register shortcode to render the slider carousel
        add_shortcode('slider', array('PressGang\Slider', 'do_shortcode'));
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
     * Render the carousel template
     *
     */
    public static function do_shortcode() {

        return Carousel::render();
    }
}

new Slider();