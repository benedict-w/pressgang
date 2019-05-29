<?php

namespace PressGang;

/**
 * Class WooCommerceTaxToggle
 *
 * @package PressGang
 */
class WooCommerceTaxToggle {

    const AJAX_ACTION = 'set_tax_display';

    /**
     * WooCommerceTaxToggle constructor.
     *
     */
    public function __construct() {
        add_filter('timber/twig', array($this, 'add_to_twig'), 100, 2);

        add_action(sprintf("wp_ajax_%s", self::AJAX_ACTION), array($this, self::AJAX_ACTION)); // user logged in
        add_action(sprintf("wp_ajax_nopriv_%s", self::AJAX_ACTION), array($this, self::AJAX_ACTION)); // not logged in
    }

    /**
     * set_tax_display
     *
     */
    public function set_tax_display() {

        check_ajax_referer(self::AJAX_ACTION);

        $display_tax = filter_input(INPUT_POST, 'post_type', FILTER_VALIDATE_BOOLEAN);

        update_option('woocommerce_tax_display_shop', $display_tax);
        update_option('woocommerce_tax_display_cart', $display_tax);

    }

    /**
     * add_to_twig
     *
     */
    public function add_to_twig($twig) {
        $twig->addFunction(new \Twig_SimpleFunction('tax_toggle', array($this, 'render_tax_toggle')));

        return $twig;
    }

    /**
     * render_tax_toggle
     *
     * @return mixed
     */
    public function render_tax_toggle() {

        $display_tax = get_option('woocommerce_tax_display_shop');
        $display_label = __("VAT: Inclusive", THEMENAME);

        \Timber::render('woocommerce/tax-toggle.twig', array(
            'display_tax' => $display_tax,
            'display_label' => $display_label,
        ));
    }

}

new WooCommerceTaxToggle();