<?php

namespace PressGang;

/**
 * Class WCProductsController
 *
 * @package PressGang
 */
class WoocommerceProductCategoryController extends TaxonomyController {

    protected $category = null;

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
     * get_context
     *
     */
    public function get_context()
    {
        parent::get_context();

        $this->context['widget_sidebar'] = \Timber::get_widgets('shop_sidebar');
        $this->context['category'] = $this->get_taxonomy();
        $this->context['title'] = single_term_title('', false);

        return $this->context;
    }
}