<?php

namespace PressGang;

require_once 'base-controller.php';

class PageController extends BaseController {

    public function __construct($template = 'page.twig') {
        parent::__construct($template);
        $this->context['page'] = \Timber::get_post();
    }
}