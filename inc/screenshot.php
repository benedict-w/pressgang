<?php

namespace Pressgang;

/**
 * Class Screenshot
 *
 * Automatically generate a theme screenshot.png
 *
 * https://codex.wordpress.org/Theme_Development#Screenshot
 *
 * @package Pressgang
 */
class Screenshot {

    // TODO https://screenshotlayer.com/
    // make chron task

    public function __construct() {

    }

    function get_screenshot() {

        $snap = 'http://s.wordpress.com/mshots/v1/';
        $url = 'http://www.wpbeginner.com';
        $width = '1200';
        $height = '900';

        $img = sprintf("%s%s?w='%s&h=%s", $snap, urlencode($url), $width, $height);

        return $img;
    }
}

new Screenshot();