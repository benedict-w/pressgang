<?php

namespace PressGang;

require_once 'page-controller.php';

/**
 * Class PostController
 *
 * @package PressGang
 */
class PostController extends PageController {

    protected $tags;
    protected $categories;
    protected $related_posts;

    /**
     * __construct
     *
     * PageController constructor
     *
     * @param string $template
     */
    public function __construct($template = 'single.twig') {
        parent::__construct($template);
    }

    /**
     * get_context
     *
     * @return mixed
     */
    protected function get_context()
    {
        $this->context['post'] = $this->get_post();
        $this->context['tags'] = $this->get_tags();
        $this->context['categories'] = $this->get_categories();
        $this->context['related_posts'] = $this->get_related_posts();

        return $this->context;
    }

    /**
     * get_tags
     *
     * @param $page
     * @return array
     */
    protected function get_tags() {

        if(empty($this->tags)) {
            $this->tags = wp_get_post_tags($this->get_post()->ID);
            $this->tags = count($this->tags) ? $this->tags[0]->term_id : array();
        }

        return $this->tags;
    }

    /**
     * get_categories
     *
     * @return array
     */
    protected function get_categories() {
        if (empty($this->categories)) {
            $this->categories = get_the_category(', ');
        }

        return $this->categories;
    }

    /**
     * get_related_posts
     *
     * @param $post
     * @param $tags
     * @return array
     */
    protected function get_related_posts($number = 5) {

        if(empty($this->related_posts)) {
            $this->related_posts = get_posts(array(
                'category__in' => wp_get_post_categories($this->get_post()->ID),
                'numberposts' => $number,
                'post__not_in' => array($this->get_post()->ID),
                'tag__in' => $this->get_tags(),
                'ignore_sticky_posts' => true,
            ));
        }

        return $this->related_posts;
    }
}