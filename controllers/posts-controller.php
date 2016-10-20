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
    public function __construct($post_type = null, $template = null) {

        global $wp_query;

        if (!$post_type && isset($wp_query->query['post_type'])) {
            $post_type = $wp_query->query['post_type'];
        }

        if (!$post_type && $temp = get_post_type()) {
            $post_type = $temp;
        }

        $this->post_type = $post_type;

        if(!$template) {
            // try to guess the view for custom post types

            if (is_category()) {
                $template = 'category.twig';
            } else {
                $template = sprintf("archive%s.twig", $this->post_type === 'post' ? '' : "-{$this->post_type}") ;
            }

        }

        parent::__construct(array($template, 'archive.twig'));
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

        if (is_search()) {
            $args['s'] = get_query_var('s');
        }

        if (is_author()) {
            $args['author'] = get_queried_object_id();
        }

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
        // TODO get from config
        $this->context[Pluralizer::pluralize($this->post_type)] = $this->context['posts'] = $this->get_posts();
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
            $this->page_title = get_the_archive_title();
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