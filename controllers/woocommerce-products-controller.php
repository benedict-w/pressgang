<?php

namespace PressGang;

/**
 * Class WCProductsController
 *
 * @package PressGang
 */
class WoocommerceProductsController extends PostsController {

    protected $product_categories = array();

    /**
     * __construct
     *
     * WCProductController constructor
     *
     * @param string $template
     */
    public function __construct($template = 'woocommerce/archive.twig') {
        parent::__construct($template);
    }

    /**
     * get_product_categories
     *
     * @return array
     */
    public function get_product_categories() {

        if (empty($this->product_categories)) {

            $term = get_queried_object();
            $parent_id = empty($term->term_id) ? 0 : $term->term_id;

            $product_categories = get_categories(apply_filters('woocommerce_product_subcategories_args', array(
                'parent' => $parent_id,
                'menu_order' => 'ASC',
                'hide_empty' => 0,
                'hierarchical' => 1,
                'taxonomy' => 'product_cat',
                'pad_counts' => 1
            )));

            foreach($product_categories as &$category) {
                $category = new \TimberTerm($category);
                $meta = get_term_meta($category->term_id);
                if (isset($meta['thumbnail_id'][0])) {
                    $category->thumbnail = new \TimberImage($meta['thumbnail_id'][0]);
                }
            }

            $this->product_categories = $product_categories;

        }

        return $this->product_categories;
    }

    /**
     * get_context
     *
     */
    public function get_context()
    {
        parent::get_context();

        $this->context['widget_sidebar'] = \Timber::get_widgets('shop_sidebar');
        $this->context['shop_page_display'] = get_option('woocommerce_shop_page_display');
        $this->context['product_categories'] = $this->get_product_categories();

        return $this->context;
    }
}