<?php

namespace PressGang;

/**
 * Class WooCommerceTaxToggle
 *
 * @package PressGang
 */
class WooCommerceTaxToggle {

    const COOKIE_NAME = 'woocommerce_tax_display';

    protected $woocommerce_tax_display_shop = null;
    protected $woocommerce_tax_display_cart = null;

    /**
     * WooCommerceTaxToggle constructor.
     *
     */
    public function __construct() {

        $this->woocommerce_tax_display_shop = get_option('woocommerce_tax_display_shop');
        $this->woocommerce_tax_display_cart = get_option('woocommerce_tax_display_cart');

        add_filter('timber/twig', array($this, 'add_to_twig'), 100, 2);

        add_filter('option_woocommerce_tax_display_shop', array($this, 'woocommerce_tax_display_shop'), 10, 2);
        add_filter('option_woocommerce_tax_display_cart', array($this, 'woocommerce_tax_display_shop'), 10, 2);

        add_action('init', array($this, 'set_tax_display'));
    }

    /**
     * set_tax_display
     *
     */
    public function set_tax_display() {

        if (isset($_POST[sprintf("%s_toggle", self::COOKIE_NAME)])) {

            $display_tax = filter_input(INPUT_POST, self::COOKIE_NAME, FILTER_VALIDATE_BOOLEAN);
            $display_tax = $display_tax ? 'incl' : 'excl';

            setcookie('woocommerce_tax_display', $display_tax, time() + 365 * 24 * 60 * 60, COOKIEPATH, COOKIE_DOMAIN);

            global $wp;

            if (wp_redirect(\Timber\UrlHelper::get_current_url())) {
                exit;
            }

        }


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

        $display_tax = $this->woocommerce_tax_display_shop() === 'incl';

        \Timber::render('woocommerce/tax-toggle.twig', array(
            'display_tax' => $display_tax,
            'cookie_name' => self::COOKIE_NAME,
        ));

    }

    /**
     * woocommerce_tax_display_shop
     *
     * @return string ('incl' / 'excl')
     */
    public function woocommerce_tax_display_shop() {

        return isset($_COOKIE[self::COOKIE_NAME]) ? $_COOKIE[self::COOKIE_NAME] : $this->woocommerce_tax_display_shop;

    }

    /**
     * woocommerce_tax_display_cart
     *
     * @return string ('incl' / 'excl')
     */
    public function woocommerce_tax_display_cart() {

        return isset($_COOKIE[self::COOKIE_NAME]) ? $_COOKIE[self::COOKIE_NAME] : $this->woocommerce_tax_display_cart;

    }

}

new WooCommerceTaxToggle();