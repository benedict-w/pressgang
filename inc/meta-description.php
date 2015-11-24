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
        if (strlen($description) > 155) {
            $description = substr($description, 0, 155);
            $description = \TimberHelper::trim_words($description, str_word_count($description) - 1);
        }

        return $description;
    }
}

new MetaDescription();

