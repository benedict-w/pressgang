<?php

namespace Pressgang;

/**
 * Class SearchExclude
 *
 * Exclude pages from search
 *
 * @package Pressgang
 */
class SearchExclude
{
    /**
     * __construct
     *
     * SearchExclude constructor.
     */
    public function __construct()
    {
        if (!is_admin()) {
            add_filter('pre_get_posts', array($this, 'filter_search_post_types'));
        }
    }

    /**
     * exclude_search
     *
     * @param $query
     * @return mixed
     */
    public function filter_search_post_types($query) {

        if ($query->is_search) {
            $search_post_types = empty($query->get('post_type')) ? array('post') : $query->get('post_type');
            $search_post_types = apply_filters('search_post_types', $search_post_types);
            $query->set('post_type', $search_post_types);
        }

        return $query;
    }
}

new SearchExclude();