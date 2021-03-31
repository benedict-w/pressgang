<?php

namespace PressGang;

class GoogleAnalyticsWooCommerce {

    /**
     * __construct
     *
     * @return void
     */
    public function __construct() {
         add_action('wp_head', array($this, 'script'), -50); // needs to be added before gtag fires
    }

    /**
     * script
     *
     * @return void
     */
    public function script () {

        if (class_exists('woocommerce') && is_order_received_page() && get_theme_mod('google-analytics-id')) {

            global $wp;
            $order_id = absint($wp->query_vars['order-received']);

            if ($order_id) {

                if ($order = wc_get_order($order_id)) {

                    $items = array();

                    foreach($order->get_items() as &$item) {

                        $product = $item->get_product();

                        foreach (get_the_terms($product->get_id(), 'product_cat') as $category) {
                            if ($category->parent === 0) {
                                break;
                            }
                        }

                        $data = $item->get_data();

                        $items[] = array(
                            'sku' => $product->get_sku(),
                            'name' => $data['name'],
                            'category' => $category ? $category->name : '',
                            'price' => $data['subtotal'],
                            'quantity' => $data['quantity'],
                        );
                    }

                    \Timber::render('google-analytics-ecommerce.twig', array(
                        'transaction_id' => $order->get_order_key(),
                        'transaction_affiliation' => 'WooCommerce',
                        'transaction_total' => $order->get_total(),
                        'transaction_tax' => $order->get_total_tax(),
                        'transaction_shipping' => $order->get_total_shipping(),
                        'transaction_products' => $items,
                    ));
                }
            }
        }
    }

}

new GoogleAnalyticsWooCommerce();