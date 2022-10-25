<?php

namespace PressGang;

require_once 'page-controller.php';

/**
 * Class NotFoundController
 *
 * @package PressGang
 */
class NotFoundController extends PageController {

    /**
     * __construct
     *
     * PageController constructor
     *
     * @param string $template
     */
    public function __construct($template = '404.twig') {
        parent::__construct($template);
    }

    /**
     * get_context
     *
     * @return mixed
     */
    protected function get_context()
    {
        $this->context['title'] = __("Not Found", THEMENAME);
        $this->context['content'] = __("Sorry, we couldn't find what you are looking for!", THEMENAME);

        return $this->context;
    }
}