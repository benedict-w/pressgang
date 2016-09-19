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
     * Initialize
     *
     */
    public function __construct ($template = null, $context = null) {
        $class = new \ReflectionClass(get_called_class());
        $shortcode = strtolower($class->getShortName());
        if ($template === null) {
            $template = "{$shortcode}.twig";
        }
        $this->template = $template;
        $this->context = $context;
        add_shortcode($shortcode, array($this, 'do_shortcode'));
    }

    /**
     * do_shortcode
     *
     * Render the shortcode template
     *
     * @return string
     */
    public function do_shortcode($atts, $content = null) {

        $args = shortcode_atts($this->defaults, $atts);

        $this->context = $args;

        return \Timber::compile($this->template, $this->context);
    }

}