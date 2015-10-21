<?php

namespace Pressgang;

/**
 * Class Instagram
 *
 * @package Pressgang
 */
class Instagram extends \WP_Widget
{
    /**
     * __construct
     *
     */
    public function __construct()
    {
        $widget_ops = array(
            'classname' => 'widget-instagram',
            'description' => __("Load Instagram images for a user", THEMENAME),
        );

        parent::__construct('instagram', __("Instagram", THEMENAME), $widget_ops);
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

        \Timber::render('widget-instagram.twig', array(
            'before_widget' => $before_widget,
            'after_widget' => $after_widget,
            'title' => "{$before_title}{$instance['title']}{$after_title}",
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
            'title' => __("Instagram", THEMENAME),
        );

        $instance = wp_parse_args((array)$instance, $defaults);

        \Timber::render('admin.text.twig', array(
            'label' => __("Title", THEMENAME),
            'id' => $this->get_field_id('title'),
            'name' => $this->get_field_name('title'),
            'value' => esc_attr($instance['title']),
            'class' => 'widefat',
        ));
    }

}

register_widget('Pressgang\Instagram');