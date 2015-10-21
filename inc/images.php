<?php

namespace PressGang;

/**
 * Class Images
 *
 * @package PressGang
 */
class Images
{
    /**
     * Init
     *
     * override default thumbnail sizes
     *
     */
    public static function  init() {
        // set 4:3 and hard crop
        update_option('thumbnail_size_w', 266);
        update_option('thumbnail_size_h', 200);
        update_option('thumbnail_crop', true);

        // set 4:3 and hard crop
        update_option('medium_size_w', 720);
        update_option('medium_size_h', 540);
        update_option('medium_crop', true);

        // set 16:9 and soft crop
        update_option('large_size_w', 1140);
        update_option('large_size_h', 641);
        update_option('large_crop', false);
    }
}

Images::init();