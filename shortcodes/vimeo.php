<?php

namespace PressGang\Shortcodes;

/**
 * Class Vimeo
 *
 * @package PressGang
 */
class Vimeo extends \Pressgang\Shortcode {

    protected $defaults = array(
        'id' => null,
        'title' => 0,
        'badge' => 0,
        'byline' => 0,
        'color' => '000',
        'loop' => 0,
        'portrait' => 0,
        'autoplay' => 0,
        'autopause' => 1,
    );
}

new Vimeo();