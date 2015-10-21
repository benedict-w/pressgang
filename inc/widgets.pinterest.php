<?php

namespace Pressgang;

/**
 * Class Pinterest
 *
 * @package Pressgang
 */
class Pinterest extends \WP_Widget
{
    /**
     * __construct
     *
     */
    public function __construct()
    {
        $widget_ops = array(
            'classname' => 'widget-pinterest',
            'description' => __("Add a Pinterest Board or Profile Widget", THEMENAME),
        );

        parent::__construct('pinterest', __("Pinterest", THEMENAME), $widget_ops);
    }

    /**
     * widget
     *
     * @param array $args
     * @param array $instance
     */
    public function widget($args, $instance)
    {
        extract($args);

        wp_register_script('pinterest', '//assets.pinterest.com/js/pinit.js' , array(), false, true);
        wp_enqueue_script('pinterest');

        \Timber::render('widget-pinterest.twig', array(
            'before_widget' => $before_widget,
            'after_widget' => $after_widget,
            'title' => "{$before_title}{$instance['title']}{$after_title}",
            'type' => $instance['type'],
            'image_width' => $instance['image-width'],
            'board_width' => $instance['board-width'],
            'board_height' => $instance['board-height'],
        ));
    }

    /**
     * update
     *
     * @param array $new_instance
     * @param array $old_instance
     * @return array
     */
    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;

        $instance['title'] = filter_var($new_instance['title'], FILTER_SANITIZE_STRING);
        $instance['user'] = filter_var($new_instance['user'], FILTER_SANITIZE_STRING);
        $instance['type'] = filter_var($new_instance['type'], FILTER_SANITIZE_STRING);
        $instance['image-width'] = filter_var($new_instance['image-width'], FILTER_SANITIZE_NUMBER_INT);
        $instance['board-height'] = filter_var($new_instance['board-height'], FILTER_SANITIZE_NUMBER_INT);
        $instance['board-width'] = filter_var($new_instance['board-width'], FILTER_SANITIZE_NUMBER_INT);

        return $instance;
    }

    /**
     * form
     *
     * @param array $instance
     * @return void
     */
    public function form($instance)
    {
        $defaults = array(
            'user' => __("User", THEMENAME),
            'title' => __("Pinterest", THEMENAME),
            'type' => 'board',
            'image-width' => 80,
            'board-height' => 320,
            'board-width' => 400,
        );

        $instance = wp_parse_args((array)$instance, $defaults);

        \Timber::render('admin.text.twig', array(
            'label' => __("Title", THEMENAME),
            'id' => $this->get_field_id('title'),
            'name' => $this->get_field_name('title'),
            'value' => esc_attr($instance['title']),
            'class' => 'widefat',
        ));

        \Timber::render('admin.text.twig', array(
            'label' => __("User", THEMENAME),
            'id' => $this->get_field_id('user'),
            'name' => $this->get_field_name('user'),
            'value' => esc_attr($instance['user']),
            'class' => 'widefat',
        ));

        \Timber::render('admin.select.twig', array(
            'label' => __("Type", THEMENAME),
            'id' => $this->get_field_id('type'),
            'name' => $this->get_field_name('typw'),
            'options' => array('board' => "Board", 'profile' => "Profile"),
            'value' => 'board',
            'class' => 'widefat',
        ));

        \Timber::render('admin.number.twig', array(
            'label' => __("Image width", THEMENAME),
            'id' => $this->get_field_id('image-width'),
            'name' => $this->get_field_name('image-width'),
            'value' => esc_attr($instance['image-width']),
        ));

        \Timber::render('admin.number.twig', array(
            'label' => __("Image width", THEMENAME),
            'id' => $this->get_field_id('image-width'),
            'name' => $this->get_field_name('board-height'),
            'value' => esc_attr($instance['board-height']),
        ));

        \Timber::render('admin.number.twig', array(
            'label' => __("Image width", THEMENAME),
            'id' => $this->get_field_id('image-width'),
            'name' => $this->get_field_name('board-width'),
            'value' => esc_attr($instance['board-width']),
        ));
    }

}

register_widget('Pressgang\Pinterest');