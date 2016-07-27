<?php

namespace PressGang;

class BaseController {

    public $context;
    public $template;

    public function __construct($template = null) {
        $this->template = $template;
        $this->context = \Timber::get_context();
    }

    public function render() {
        \Timber::render($this->template, $this->context);
    }
}