<?php

namespace Pressgang;

class StructuredDataSearch
{
    /**
     * __construct
     *
     * StructuredDataSearch constructor.
     */
    public function __construct()
    {
        add_action('wp_head', array($this, 'render'));
    }

    /**
     * render
     *
     * @return mixed
     */
    public function render() {
        if(is_front_page()) {

            $data = array(
                'name' => get_bloginfo('name'),
                'url' =>  get_bloginfo('url'),
            );

            \Timber::render('structured-data-search.twig', $data);
        }
    }
}

new StructuredDataSearch();