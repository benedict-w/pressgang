<?php

namespace Pressgang;

class StructuredDataSearch
{
    public function __construct()
    {
        add_action('wp_head', array($this, 'render'));
    }

    public function render() {
        if(is_front_page()) {
            return \Timber::compile('structured-data-search.twig');
        }
    }
}

new StructuredDataSearch();