<?php

namespace Pressgang;

/**
 * Class Clients
 *
 * @package Pressgang
 */
class Shortcode {

    protected $template = null;
    protected $context = array();
    protected $defaults = array();

    /**
     * __construct
     *
     */
    public function __construct ($template = null, $context = null) {
        $class = new \ReflectionClass(get_called_class());
        $shortcode = Helper::camel_to_underscored($class->getShortName());
        if ($template === null) {
            $template = sprintf("%s.twig", Helper::camel_to_hyphenated($class->getShortName()));
        }
        $this->template = $template;
        $this->context = $context;
        add_shortcode($shortcode, array($this, 'do_shortcode'));
    }

    /**
     * get_defaults
     *
     * Override to fill dynamic default values
     *
     * @return array
     */
    protected function get_defaults() {
        return $this->defaults;
    }

    /**
     * get_context
     *
     * Override to provide custom context
     *
     * @param $atts
     */
    protected function get_context($args) {
        return $this->context = $args;
    }

    /**
     * do_shortcode
     *
     * Render the shortcode template
     *
     * @return string
     */
    public function do_shortcode($atts, $content = null) {
        $args = shortcode_atts($this->get_defaults(), $atts);
        return \Timber::compile($this->template, $this->get_context($args));
    }

}