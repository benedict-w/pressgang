<?php

namespace PressGang;

/**
 * Class BaseController
 *
 * @package PressGang
 */
abstract class BaseController {

    public $context;
    public $template;

    /**
     * __construct
     *
     * BaseController constructor.
     *
     * @param null $template
     */
    public function __construct($template = null) {
        $this->template = $template;
        $this->context = \Timber::get_context();
    }

    /**
     * get_context
     *
     * Get the Timber context
     *
     * @return mixed
     */
    protected function get_context() {
        return $this->context;
    }

    /**
     * render
     *
     * Render the twig $template
     *
     */
    public function render() {
        \Timber::render($this->template, $this->get_context());
    }
}