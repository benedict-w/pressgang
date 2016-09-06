<?php

namespace PressGang;

class Gallery {

    public static function init() {
        add_theme_support('gallery');
        add_filter('post_gallery', array('PressGang\Gallery', 'setup'), 10, 2);

        // add scripts to loader queue
        Scripts::$scripts['magnific-popup'] = array(
            'src' => get_template_directory_uri() . '/js/src/vendor/magnific-popup/jquery.magnific-popup.js',
            'deps' => array('jquery'),
            'ver' => '1.0.1',
            'in_footer' => true
        );

        // load pressgang.js which inits the magnific popup
        Scripts::$scripts['pressgang-magnific'] = array(
            'src' => get_template_directory_uri() . '/js/src/custom/gallery.js',
            'deps' => array('magnific-popup'),
            'ver' => '0.1',
            'in_footer' => true
        );

    }

    public static function setup($output, $attr) {

        global $post;

        $html5 = current_theme_supports('html5');

        $atts = shortcode_atts( array(
            'order'      => 'ASC',
            'orderby'    => 'menu_order ID',
            'id'         => $post ? $post->ID : 0,
            'itemtag'    => $html5 ? 'figure' : 'dl',
            'icontag'    => $html5 ? 'div' : 'dt',
            'captiontag' => $html5 ? 'figcaption' : 'dd',
            'columns'    => 3,
            'size'       => 'thumbnail',
            'include'    => '',
            'exclude'    => '',
            'link'       => '',
        ), $attr, 'gallery' );

        $id = intval($atts['id']);

        if (!empty($atts['include'])) {

            $posts = get_posts(array(
                'include' => $atts['include'],
                'post_status' => 'inherit',
                'post_type' => 'attachment',
                'post_mime_type' => 'image',
                'order' => $atts['order'],
                'orderby' => $atts['orderby'],
            ));

            $attachments = array();

            foreach ($posts as $key => $val) {
                $attachments[$val->ID] = $posts[$key];
            }

        } elseif (!empty($atts['exclude'])) {

            $attachments = get_children(array(
                'post_parent' => $id,
                'exclude' => $atts['exclude'],
                'post_status' => 'inherit',
                'post_type' => 'attachment',
                'post_mime_type' => 'image',
                'order' => $atts['order'],
                'orderby' => $atts['orderby']
            ));

        } else {
            $attachments = get_children(
                array(
                    'post_parent' => $id,
                    'post_status' => 'inherit',
                    'post_type' => 'attachment',
                    'post_mime_type' => 'image',
                    'order' => $atts['order'],
                    'orderby' => $atts['orderby']
                ));
        }

        if (count($attachments)) {

            foreach($attachments as &$attachment) {
                $attachment = new \TimberImage($attachment);
            }

            // add a static incrementer for the gallery HTML ID to allow more than one per page.
            static $gallery_inc = 0;
            $gallery_inc ++;

            $data['id'] = sprintf("gallery-%s", $gallery_inc);
            $data['thumbnails'] = $attachments;

            return \Timber::compile('gallery.twig', $data);
        }
    }
}

Gallery::init();