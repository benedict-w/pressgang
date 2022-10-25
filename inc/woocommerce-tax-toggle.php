<?php

namespace PressGang;

/**
 * Class WooCommerceTaxToggle
 *
 * @package PressGang
 */
class WooCommerceTaxToggle {

    const COOKIE_NAME = 'woocommerce_tax_display';

    static $woocommerce_tax_display_shop = 'incl';
    static $woocommerce_tax_display_cart = 'incl';

    /**
     * WooCommerceTaxToggle constructor.
     *
     */
    public function __construct() {

        static::$woocommerce_tax_display_shop = get_option('woocommerce_tax_display_shop');
        static::$woocommerce_tax_display_cart = get_option('woocommerce_tax_display_cart');

        add_filter('timber/twig', array($this, 'add_to_twig'), 100, 2);

        add_filter('option_woocommerce_tax_display_shop', array('\PressGang\WooCommerceTaxToggle', 'woocommerce_tax_display_shop'), 10, 2);
        add_filter('option_woocommerce_tax_display_cart', array('\PressGang\WooCommerceTaxToggle', 'woocommerce_tax_display_shop'), 10, 2);

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

            $this->set_tax_cookie($display_tax);

            // redirect to prevent resubmit - add query string cache buster for dynamic caching
            if (wp_redirect(add_query_arg('vat', $display_tax, \Timber\UrlHelper::get_current_url()))) {
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

        \Timber::render('woocommerce/tax-toggle.twig', array(
            'display_tax' => (self::woocommerce_tax_display_shop() === 'incl'),
            'cookie_name' => self::COOKIE_NAME,
        ));

    }

    /**
     * woocommerce_tax_display_shop
     *
     * @return string ('incl' / 'excl')
     */
    public static function woocommerce_tax_display_shop() {

        if (!isset($_COOKIE[self::COOKIE_NAME])) {
            self::set_tax_cookie(static::$woocommerce_tax_display_shop);
            return static::$woocommerce_tax_display_shop;
        }

        return $_COOKIE[self::COOKIE_NAME];

    }

    /**
     * woocommerce_tax_display_cart
     *
     * @return string ('incl' / 'excl')
     */
    public static function woocommerce_tax_display_cart() {

        return isset($_COOKIE[self::COOKIE_NAME]) ? $_COOKIE[self::COOKIE_NAME] : static::$woocommerce_tax_display_cart;

    }

    /**
     * set_tax_cookie
     *
     * @param $display_tax
     */
    protected static function set_tax_cookie($display_tax) {
        setcookie('woocommerce_tax_display', $display_tax, time() + 365 * 24 * 60 * 60);
    }

}

new WooCommerceTaxToggle();