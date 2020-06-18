<?php

namespace PressGang;

if (!defined('POST_NO_RELATED_POSTS')) {
    define('POST_NO_RELATED_POSTS', 4);
}

require_once 'page-controller.php';

/**
 * Class PostController
 *
 * @package PressGang
 */
class PostController extends PageController {

    protected $post_type;
    protected $author;
    protected $tags;
    protected $categories;
    protected $related_posts = array();
    protected $custom_taxonomy_terms = array();

    /**
     * __construct
     *
     * PageController constructor
     *
     * @param string $template
     */
    public function __construct($template = null, $post_type = null) {

        $this->post_type = $post_type ? $post_type : get_post_type();

        if(!$template) {
            // try to guess the view for custom post types
            $post_type = str_replace('_', '-', $this->post_type);
            $template = sprintf("single%s.twig", $post_type === 'post' ? '' : "-{$post_type}") ;
        }

        // add page specific twig rendering
        add_filter('timber/twig', array($this, 'add_to_twig'));

        parent::__construct(array($template, 'single.twig'));
    }

    /**
     * get_tags
     *
     * @param $page
     * @return array
     */
    public function get_tags() {

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
    public function get_categories() {
        if (empty($this->categories)) {
            $this->categories = get_the_category(', ');
        }

        return $this->categories;
    }

    /**
     * get_custom_taxonomies
     *
     */
    public function get_custom_taxonomy_terms() {

        if(empty($this->custom_taxonomy_terms)) {

            $id = $this->get_post()->ID;

            $this->custom_taxonomy_terms = wp_cache_get(sprintf("custom_tax_terms_%d", $id), 'custom_tax_terms');

            if (!$this->custom_taxonomy_terms) {

                $taxonomies = get_object_taxonomies($this->post_type, 'objects');

                foreach ($taxonomies as $slug => &$taxonomy) {

                    $terms = get_the_terms($this->get_post()->ID, $slug);

                    if (is_array($terms) && count($terms)) {

                        foreach ($terms as &$term) {
                            $term = new \TimberTerm($term);
                        }

                        $name = Pluralizer::pluralize($slug);
                        $this->custom_taxonomy_terms[$name] = $terms;
                    }
                }
            }

            wp_cache_add(sprintf("custom_tax_terms_%d", $id), $this->custom_taxonomy_terms, 'custom_tax_terms', 0);

        }

        return $this->custom_taxonomy_terms;
    }

    /**
     * get_related_posts
     *
     * @param $post
     * @param $tags
     * @return array
     */
    public function get_related_posts($posts_per_page = null) {

        $posts_per_page = $posts_per_page ?? $posts_per_page;

        if(empty($this->related_posts)) {

            $id = $this->get_post()->ID;

            $key = sprintf("related_posts_%d", $id);

            $this->related_posts = wp_cache_get($key, 'related_posts', true);

            if (!$this->related_posts) {

                $not_in = array($id);

                $args = array(
                    'post_type' => $this->post_type,
                    'orderby' => 'rand',
                    'numberposts' => $posts_per_page,
                    'post__not_in' => $not_in,
                    'ignore_sticky_posts' => true,
                    'tax_query' => array(
                        'relation' => 'AND',
                    ),
                );

                $taxonomies = get_object_taxonomies($this->post_type, 'objects');

                foreach ($taxonomies as &$taxonomy) {

                    if ($terms = wp_get_object_terms($id, $taxonomy->name, array('fields' => 'ids'))) {
                        $args['tax_query'][] = array(
                            'taxonomy' => $taxonomy->name,
                            'field' => 'term_id',
                            'terms' => $terms,
                            'operator' => 'IN',
                            'include_children' => false,
                        );
                    }
                }

                $posts = \Timber::get_posts($args);

                foreach ($posts as &$post) {
                    $this->related_posts[$post->ID] = $post;
                }

                if (is_array($this->related_posts) && count($this->related_posts) < $posts_per_page) {

                    $not_in = array_merge($not_in, array_keys($this->related_posts));

                    $args['tax_query']['relation'] = 'OR';
                    $args['post__not_in'] = $not_in;
                    $args['numberposts'] = $posts_per_page - count($this->related_posts);

                    $posts = \Timber::get_posts($args);

                    foreach ($posts as &$post) {
                        $this->related_posts[$post->ID] = $post;
                    }
                }

                if (is_array($this->related_posts) && count($this->related_posts) < $posts_per_page) {
                    $not_in = array_merge($not_in, array_keys($this->related_posts));

                    unset($args['tax_query']);
                    $args['numberposts'] = $not_in;
                    $args['numberposts'] = $posts_per_page - count($this->related_posts);

                    $posts = \Timber::get_posts($args);

                    foreach ($posts as &$post) {
                        $this->related_posts[$post->ID] = $post;
                    }
                }

                wp_cache_add($key, $this->related_posts, 'related_posts', 24 * 60 * 60);
            }
        }

        return $this->related_posts;
    }

    /**
     * get_author
     *
     * @return TimberUser
     */
    public function get_author() {

        if (empty($this->author)) {
            $post = $this->get_post();
            if ($post) {
                $this->author = $post->get_author();
                $this->author->thumbnail = new \TimberImage(get_avatar_url($this->author->id));
            }
        }

        return $this->author;
    }

    /**
     * get_context
     *
     * @return mixed
     */
    protected function get_context()
    {
        $this->context['post'] = $this->context[$this->post_type] = $this->get_post();

        return $this->context;
    }

    /**
     * add_to_twig
     *
     * Add these functions to twig so that the required data can be retrieved only when needed
     *
     * @param $twig
     * @return mixed
     */
    public function add_to_twig($twig) {

        $twig->addFunction(new \Timber\Twig_Function('get_author', array($this, 'get_author')));
        $twig->addFunction(new \Timber\Twig_Function('get_related_posts', array($this, 'get_related_posts')));
        $twig->addFunction(new \Timber\Twig_Function('get_tags', array($this, 'get_tags')));
        $twig->addFunction(new \Timber\Twig_Function('get_categories', array($this, 'get_categories')));
        $twig->addFunction(new \Timber\Twig_Function('get_custom_taxonomy_terms', array($this, 'get_custom_taxonomy_terms')));
        return $twig;
    }

}