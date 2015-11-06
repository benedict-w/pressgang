<?php

namespace PressGang;

require_once('loader.php');
require_once('site.php');


/**
 * Class Timber
 *
 * Initiate Timber (Twig) Templating Engine
 *
 * http://upstatement.com/timber/
 * http://twig.sensiolabs.org/
 *
 * @package PressGang
 */
class PressGang extends Site {

    /**
     * __construct
     *
     */
    function __construct() {
        add_filter( 'timber_context', array( $this, 'add_to_context' ) );
        add_filter( 'get_twig', array( $this, 'add_to_twig' ) );
        parent::__construct();
    }

    /**
     * add_to_context
     *
     * @param $context
     * @return mixed
     */
    function add_to_context( $context ) {
        $context['site'] = $this;
        return $context;
    }

    /**
     * add_to_twig
     *
     * Add Custom Functions to Twig
     */
    function add_to_twig( $twig ) {
        $twig->addFunction('esc_attr', new \Twig_SimpleFunction('esc_attr', 'esc_attr'));
        $twig->addFunction('esc_url', new \Twig_SimpleFunction('esc_url', 'esc_url'));
        $twig->addFunction('get_search_query', new \Twig_SimpleFunction('get_search_query', 'get_search_query'));
        return $twig;
    }
}

new PressGang();