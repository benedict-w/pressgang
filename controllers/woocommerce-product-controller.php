<?php

namespace PressGang;

/**
 * Class WCProductController
 *
 * @package PressGang
 */
class WoocommerceProductController extends BaseController {

    /**
     * WCProductController constructor.
     * @param string $template
     */
    public function __construct($template = 'woocommerce/single-product.twig') {
        parent::__construct($template);
    }

    /**
     * get_post
     *
     * @return mixed
     */
    protected function get_post()
    {
        if (empty($this->post)) {
            $this->post = \Timber::get_post();
        }

        return $this->post;
    }

    /**
     * get_context
     */
    public function get_context()
    {
        parent::get_context();
        $this->context['widget_sidebar'] = \Timber::get_widgets('shop_sidebar');
        $this->context['product'] = \wc_get_product($this->get_post()->ID);
        $this->context['post'] = \Timber::get_post($this->get_post()->ID);

        return $this->context;
    }

}