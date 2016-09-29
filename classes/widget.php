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
    protected $fields = array(
        'title' => array(
            'view' => 'admin.text.twig',
            'class' => 'widefat',
            'label' => "Title",
        ),
    );
    protected $defaults = array(
        'title' => "Title",
    );

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

        if (!$this->description) {
            $this->description = sprintf("%s Widget",  $this->title );
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

        $instance = array_merge($instance, array(
            'before_widget' => $before_widget,
            'after_widget' => $after_widget,
            'title' => "{$before_title}{$instance['title']}{$after_title}",
        ), $this->get_acf_fields($widget_id));

        $class = new \ReflectionClass(get_called_class());
        $name = Helper::camel_to_underscored($class->getShortName());

        do_action("render_widget_{$name}");

        \Timber::render($this->view, $instance);
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

        foreach($this->fields as $field => &$config) {
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
        $instance = wp_parse_args((array)$instance, $this->defaults);

        foreach($this->fields as $field => &$config) {
            \Timber::render($config['view'], array(
                'label' => __($config['label'], THEMENAME),
                'id' => $this->get_field_id($field),
                'name' => $this->get_field_name($field),
                'value' => isset($instance[$field]) ? esc_attr($instance[$field]) : '',
                'class' => $config['class'],
            ));

        }
    }

    /**
     * get_acf_fields
     *
     */
    protected function get_acf_fields($widget_id) {

        if(function_exists('get_fields')) {
            if ($fields = get_fields("widget_{$widget_id}")) {
                return $fields;
            }
        }

        return array();
    }
}