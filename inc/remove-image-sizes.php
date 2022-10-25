<?php

namespace PressGang;

class RemoveImageSizes
{
    /**
     * __construct
     *
     * We mostly don't need these as building responsive images in Timber
     */
    public function __construct() {
        add_action('init', array($this, 'remove_sizes'), 1000);
    }

    /**
     * remove_sizes
     */
    public function remove_sizes() {
        foreach (get_intermediate_image_sizes() as &$size) {
            remove_image_size($size);
        }
    }

}

new RemoveImageSizes();