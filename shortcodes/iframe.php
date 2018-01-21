<?php

namespace PressGang\Shortcodes;

/**
 * Class Iframe
 *
 * @package PressGang
 */
class Iframe extends \Pressgang\Shortcode {

    protected $defaults = array(
        'id' => null,
        'title' => '',
        'src' => '',
        'height' => 360,
        'width' => 640,
        'aspect_ratio' => '4by3',
        'allowfulscreen' => false
    );
}

new Iframe();