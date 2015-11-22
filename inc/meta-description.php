<?php

namespace PressGang;

class MetaDescription {

    /**
     * __construct
     *
     */
    public function __construct() {
        add_action('meta_description', array($this, 'meta_description'), 10);
    }

    /**
     * meta_description
     *
     */
    public function meta_description()
    {
        $post = new \TimberPost();

        // check for custom field
        $description = $post->get_field('meta_description');

        // else use preview
        if (empty($description)) {
            $description = $post->get_preview(40, true, false, true);
        }

        // finally use the blog description
        if (empty($description)) {
            $description = get_bloginfo('description', 'display');
        }

        // limit to SEO recommended length
        if (count($description) > 155) {
            $description = \TimberHelper::trim_words(substr($description, 0, 155)) . '...';
        }

        return $description;
    }
}

new MetaDescription();

