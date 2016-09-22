<?php

namespace PressGang;

require_once 'base-controller.php';

/**
 * Class AuthorController
 *
 * @package PressGang
 */
class AuthorController extends BaseController {

    protected $author;
    protected $posts;

    /**
     * __construct
     *
     * PageController constructor
     *
     * @param string $template
     */
    public function __construct($template = 'author.twig') {
        parent::__construct($template);
    }

    /**
     * get_author
     *
     * @return mixed
     */
    protected function get_author()
    {
        if (empty($this->author)) {
            if ($id = get_queried_object_id()) {
                $this->author = new \TimberUser($id);
                $this->author->thumbnail = new \TimberImage(get_avatar_url($this->author->id));
            }
        }

        return $this->author;
    }

    /**
     * get_posts
     *
     * @return mixed
     */
    protected function get_posts()
    {
        $args = array(
            'paged' => get_query_var('paged') ? get_query_var('paged') : 1,
        );

        if (empty($this->posts)) {
            $this->posts = \Timber::get_posts($args);
        }

        return $this->posts;
    }

    /**
     * get_context
     *
     * @return mixed
     */
    protected function get_context()
    {
        $this->context['author'] = $this->get_author();
        $this->context['posts'] = $this->get_author();

        return $this->context;
    }
}