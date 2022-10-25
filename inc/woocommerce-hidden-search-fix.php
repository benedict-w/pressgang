<?php

namespace PressGang;

class WooCommerceHiddenSearchFix
{
    /**
     * WooCommerceHiddenSearchFix constructor
     *
     */
    public function __construct()
    {
        add_action('pre_get_posts', array($this, 'hidden_product_search_query_fix'));
    }

    /**
     * hidden_product_search_query_fix
     *
     * @param bool $query
     */
    public function hidden_product_search_query_fix( $query = false ) {

        if(!is_admin() && is_search()){

            $query->set('tax_query', array(
                'relation' => 'OR',
                array(
                    'taxonomy' => 'product_visibility',
                    'field'    => 'name',
                    'terms'    => 'exclude-from-search',
                    'operator' => 'NOT IN',
                ),
            ));

        }
    }
}

new WooCommerceHiddenSearchFix();