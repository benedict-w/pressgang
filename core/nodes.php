<?php

namespace PressGang;

/**
 * Class Nodes
 *
 * @package PressGang
 */
class Nodes {

    /**
     * nodes
     *
     * @var array
     */
    public $nodes = array();

    /**
     * __construct
     *
     * Register Menus
     *
     */
    public function __construct() {
        $this->nodes = Config::get('nodes');
        add_action('admin_bar_menu', array($this, 'remove_toolbar_node'), 999);
    }

    /**
     * remove_toolbar_node
     *
     */
    public function remove_toolbar_node($wp_admin_bar) {
        foreach($this->nodes as $node) {
            $wp_admin_bar->remove_node($node);
        }
    }

}

new Nodes();