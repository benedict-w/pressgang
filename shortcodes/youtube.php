<?php

namespace PressGang\Shortcodes;

/**
 * Class Youtube
 *
 * @package PressGang
 */
class Youtube extends \Pressgang\Shortcode {

    // see - https://developers.google.com/youtube/iframe_api_reference
    protected $defaults = array(
        'id' => null,
        'width' => 640,
        'height' => 360,
        'enablejsapi' => 0,
        'loop' => 0,
        'autoplay' => 0,
    );

    public function do_shortcode($atts, $content = null) {
        $atts['origin'] = get_permalink();
        parent::do_shortcode($atts);
    }
}

new Vimeo();