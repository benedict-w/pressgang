<?php

Namespace Pressgang;

require_once 'base-controller.php';

class SinglePageController extends BaseController {

    public function __construct($template = 'single-page.twig') {

        parent::__construct($template);

        $post = \Timber::get_post();
        $args = array(
            'post_parent' => $post->ID,
            'post_type' => 'page',
            'numberposts' => -1,
            'post_status' => 'publish',
            'orderby' => 'menu_order',
            'order' => 'ASC',
        );

        $pages = array($post);

        foreach (get_children($args) as $child) {

            $page = new \TimberPost($child);

            $template = get_page_template_slug($page->ID);
            $template = preg_replace(array('/.*\//', '/\.php/i'), array('', '.twig'), $template);

            $page->template = $template;
            $pages[] = $page;
        }

        $this->context['pages'] = $pages;
    }
}