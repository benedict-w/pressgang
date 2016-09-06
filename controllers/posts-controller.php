<?php

namespace PressGang;

require_once 'base-controller.php';

/**
 * Class PostsController
 *
 * @package PressGang
 */
class PostsController extends BaseController {

    protected $posts;
    protected $post_type;
    protected $page_title;
    protected $pagination;

    /**
     * __construct
     *
     * PageController constructor
     *
     * @param string $template
     */
    public function __construct($template = 'archive.twig', $post_type = 'post') {

        $this->post_type = $post_type;

        parent::__construct($template);
    }

    /**
     * get_posts
     *
     * @return mixed
     */
    protected function get_posts()
    {
        $args = array(
            'post_type' => $this->post_type,
            'paged' => get_query_var('paged') ? get_query_var('paged') : 1,
            'cat' => get_query_var('cat'),
            'tag_id' => get_query_var('tag_id'),
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
        $this->context['page_title'] = $this->get_page_title();
        $this->context['posts'] = $this->get_posts();
        $this->context['pagination'] = $this->get_pagination();

        return $this->context;
    }

    /**
     * get_page_title
     *
     * @return string|void
     */
    protected function get_page_title() {

        if (empty($this->page_title)) {
            $this->page_title = post_type_archive_title('', false);

            if (is_category()) {
                $this->page_title = single_cat_title('', false);
            } else if (is_tax()) {
                $this->page_title = single_term_title('', false);
            } else if (is_search()) {
                $this->page_title = sprintf(__("Search results for '%s'", THEMENAME), get_search_query());
            }
        }

        return $this->page_title;
    }

    /**
     * get_pagination
     *
     * @return mixed
     */
    protected function get_pagination () {

        if (empty($this->pagination)) {
            $this->pagination = \Timber::get_pagination();
        }

        return $this->pagination;
    }
}