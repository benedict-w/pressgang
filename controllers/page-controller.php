<?php

namespace PressGang;

require_once 'base-controller.php';

/**
 * Class PageController
 *
 * @package PressGang
 */
class PageController extends BaseController {

    protected $post;

    /**
     * __construct
     *
     * PageController constructor
     *
     * @param string $template
     */
    public function __construct($template = 'page.twig') {
        parent::__construct($template);
    }

    /**
     * get_post
     *
     * @return mixed
     */
    protected function get_post()
    {
        if (empty($this->post)) {
            $this->post = \Timber::get_post();
        }

        return $this->post;
    }

    /**
     * get_context
     *
     * @return mixed
     */
    protected function get_context()
    {
        $this->context['page'] = $this->get_post();

        return $this->context;
    }
}