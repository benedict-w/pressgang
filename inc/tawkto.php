<?php

namespace PressGang;

class Tawkto {

    /**
     * __construct
     *
     * @return void
     */
    public function __construct() {
        add_action('wp_footer', array($this, 'render'), 100 );
    }

    /**
     * render
     *
     */
    public function render() {
        \Timber::render('tawkto.twig');
    }
}

new Tawkto();