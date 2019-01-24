<?php

namespace PressGang;

require_once('loader.php');

/**
 * Class Site
 *
 * @package PressGang
 */
class Site extends \TimberSite
{
    public $stylesheet;
    public $keywords;
    public $logo;
    public $copyright;

    /**
     *__construct
     *
     * @param string|int $site_name_or_id
     */
    function __construct($site_name_or_id = null)
    {
        $stylesheet = get_theme_mod('stylesheet', 'styles.css');
        $this->stylesheet = sprintf("%s/css/%s?v=%s", get_stylesheet_directory_uri(), $stylesheet, filemtime(get_stylesheet_directory() . "/css/{$stylesheet}"));

        // add custom params
        $this->keywords = apply_filters('site_keywords', implode(', ', array_map(function ($tag) {
            return $tag->name;
        }, get_tags(array('orderby' => 'count', 'order' => 'DESC', 'number' => 20)))));

        $this->email = get_option('admin_email');

        // replace the site icon with an image object
        if ($this->site_icon) {
            $this->site_icon = new \TimberImage($this->site_icon);
        }

        if ($theme_mods = get_theme_mods()) {
            foreach ($theme_mods as $mod_key => &$mod_val) {
                $this->$mod_key = apply_filters($mod_key, $mod_val);
            }
        }

        add_filter('timber_context', array($this, 'add_to_context'));
        add_filter('get_twig', array($this, 'add_to_twig'));

        add_filter('meta_description', array('PressGang\Site', 'meta_description'));

        add_filter('wp_headers', array($this, 'add_ie_header'));

        if (class_exists('WooCommerce')) {
            add_filter('timber_context', array($this, 'add_woocommerce_to_context'));
        }

        parent::__construct($site_name_or_id);
    }

    /**
     * add_to_context
     *
     * @param $context
     * @return mixed
     */
    public function add_to_context($context) {
        $context['site'] = $this;

        $context = \apply_filters("site_context", $context);
        return $context;
    }

    /**
     * add_woocommerce_to_context
     *
     * @param $context
     * @return mixed
     */
    public function add_woocommerce_to_context($context) {

        global $woocommerce;

        $account_page_id = get_option('woocommerce_myaccount_page_id');

        $context['my_account_link'] = get_permalink($account_page_id);
        $context['logout_link'] = wp_logout_url(get_permalink($account_page_id));
        $context['cart_link'] = wc_get_cart_url();
        $context['checkout_link'] = wc_get_checkout_url();
        $context['cart_contents'] = $woocommerce->cart->cart_contents;

        return $context;
    }

    /**
     * add_to_twig
     *
     * Add Custom Functions to Twig
     */
    public function add_to_twig( $twig ) {
        $twig->addFunction(new \Twig_SimpleFunction('esc_attr', 'esc_attr'));
        $twig->addFunction(new \Twig_SimpleFunction('esc_url', 'esc_url'));
        $twig->addFunction(new \Twig_SimpleFunction('get_search_query', 'get_search_query'));

        $twig->addFunction(new \Twig_SimpleFunction('meta_description', array('PressGang\Site', 'meta_description')));

        $twig->addFunction(new \Twig_SimpleFunction('get_option', 'get_option'));
        $twig->addFunction(new \Twig_SimpleFunction('get_theme_mod', 'get_theme_mod'));

        if (class_exists('WooCommerce')) {
            $twig->addFunction(new \Twig_SimpleFunction('timber_set_product', array('PressGang\Site', 'timber_set_product')));
        }

        // add svg sanitizer
        // use plugin safe-svg
        // $twig->getExtension('Twig_Extension_Core')->setEscaper('svg', array($this, 'svg_sanitizer'));

        // TODO can we lazy load or include?

        $twig->addFilter(new \Twig_SimpleFilter('pluralize', array('PressGang\Pluralizer', 'pluralize')));

        // add text-domain to global
        $twig->addGlobal('THEMENAME', THEMENAME);
        return $twig;
    }

    /**
     * add meta_description
     *
     * hook after post has loaded to add a unique meta-description
     *
     */
    public static function meta_description()
    {
        $post = new \TimberPost();

        // check for custom field
        $description = wptexturize($post->get_field('meta_description'));

        if (is_tax()) {
            if ($temp = term_description(get_queried_object(), get_query_var('taxonomy'))) {
                $description = $temp;
            }
        }
        elseif(is_post_type_archive()) {
            if ($temp = get_the_archive_description()) {
                $description = $temp;
            } else {
                $description = get_bloginfo('description', 'raw');
            }
        }

        // else use preview
        if (empty($description)) {
            $description = $post->get_preview(50, false, false, true);
        }

        // finally use the blog description
        if (empty($description)) {
            $description = get_bloginfo('description', 'raw');
        }

        $description = esc_attr(wp_strip_all_tags($description));

        // limit to SEO recommended length
        if (strlen($description) > 155) {
            $description = substr($description, 0, 155);
            $description = \TimberHelper::trim_words($description, str_word_count($description) - 1);
        }

        return $description;
    }

    /**
     * force_ie_headers
     *
     * see - http://stackoverflow.com/questions/14198594/bad-value-x-ua-compatible-for-attribute-http-equiv-on-element-meta
     */
    public function add_ie_header() {
        if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)) {
            header('X-UA-Compatible: IE=edge,chrome=1');
        }
    }

    /**
     * timber_set_product
     *
     * Set the timber post context for WooCommerce teaser-product.twig
     */
    public static function timber_set_product($post) {
        global $product;
        if (is_woocommerce()) {
            $product = wc_get_product($post->ID);
        }
    }

    /**
     * svg_sanitizer
     *
     * see - https://github.com/darylldoyle/svg-sanitizer
     */
    public function svg_sanitizer($twig, $content, $charset) {

        $sanitizer = new enshrined\svgSanitize\Sanitizer();

        return $sanitizer->sanitize($content);
    }
}

new Site();