<?php

namespace PressGang;

/**
 * Class WCProductController
 *
 * @package PressGang
 */
class WoocommerceProductController extends PageController {

    /**
     * WCProductController constructor.
     * @param string $template
     */
    public function __construct($template = 'woocommerce/single-product.twig') {
        parent::__construct($template);
    }

    /**
     * get_context
     */
    public function get_context()
    {
        parent::get_context();
        $this->context['widget_sidebar'] = \Timber::get_widgets('shop_sidebar');
        $this->context['product'] = \wc_get_product($this->get_post()->ID);
        $this->context['post'] = \wc_get_product($this->get_post()->ID);

        return $this->context;
    }

}