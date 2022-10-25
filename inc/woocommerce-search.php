<?php

namespace PressGang;

require_once __DIR__ . '/search-exclude.php';

/**
 * Class WooCommerceSearch
 *
 * Customisations for WooCommerce Search
 *
 * @package PressGang
 */
class WooCommerceSearch
{

    /**
     * WooCommerce constructor.
     */
    public function __construct()
    {
        // see filter in inc/search-exclude.php
        add_filter('search_post_types', array($this, 'search_post_types'));
        add_action('woocommerce_after_shop_loop', array($this, 'add_search_pagination'), 5);
    }

    /**
     * search_post_types
     *
     */
    public function search_post_types() {
        return array('product');
    }

    /**
     * add_search_pagination
     *
     * TODO Woocommerce bug? loop total prop is set via wc_query not wp_query, this fixes the total so that pagination is
     * displayed on search results page. See - wc_setup_loop
     *
     */
    public function add_search_pagination() {
        if (is_search()) {
            global $wp_query;
            $GLOBALS['woocommerce_loop']['total'] = $wp_query->found_posts;
            $GLOBALS['woocommerce_loop']['total_pages'] = $wp_query->max_num_pages;
        }
    }
}

new WooCommerceSearch();