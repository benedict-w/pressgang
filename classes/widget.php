<?php

namespace Pressgang;

/**
 * Class Widget
 *
 * @package Pressgang
 */
class Widget extends \WP_Widget
{
    protected $classname;
    protected $description;
    protected $view;
    protected $title;
    protected $fields = array('title');

    /**
     * __construct
     *
     */
    public function __construct()
    {
        $class = new \ReflectionClass(get_called_class());
        $classname = Helper::camel_to_hyphenated($class->getShortName());

        if (!$this->classname) {
            $this->classname = sprintf("widget-%s", $classname);
        }

        if (!$this->view) {
            $this->view = sprintf("%s.twig", $classname);
        }

        if (!$this->title) {
            $this->title = __(ucwords(preg_replace('/-/', ' ', $classname)), THEMENAME);
        }

        $widget_ops = array(
            'classname' => $this->classname,
            'description' => $this->description,
        );

        parent::__construct($classname, $this->title, $widget_ops);
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

        \Timber::render($this->view, array(
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

        foreach($this->fields as &$field) {
            $instance[$field] = filter_var($new_instance[$field], FILTER_SANITIZE_STRING);
        }

        return $instance;
    }

    /**
     * form
     *
     * Override and render other form components
     *
     * @param array $instance
     * @return void
     */
    public function form($instance)
    {
        $this->form_title($instance);
    }

    /**
     * form_title
     *
     * @param $instance
     */
    protected function form_title($instance)
    {
        // TODO could setup config?


        $defaults = array(
            'title' => $this->title
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