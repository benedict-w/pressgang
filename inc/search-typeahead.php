<?php

namespace PressGang;

class SearchTypeahead {

    /**
     * SearchTypeahead constructor
     */
    public function __construct()
    {
        add_action('wp_ajax_search_typeahead', array($this, 'search_typeahead'));
        add_action('wp_ajax_nopriv_search_typeahead', array($this, 'search_typeahead'));

        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));

        Scripts::$scripts['bloodhound'] = array(
            'src' => get_template_directory_uri() . '/js/src/vendor/bloodhound/bloodhound.js',
            'deps' => array('jquery'),
            'ver' => '0.11.1',
        );

        Scripts::$scripts['typeahead'] = array(
            'src' => get_template_directory_uri() . '/js/src/vendor/typeahead/typeahead.jquery.js',
            'deps' => array('bloodhound'),
            'ver' => '0.11.1',
        );

        Scripts::$scripts['search-typeahead'] = array(
            'src' => get_template_directory_uri() . '/js/src/custom/search-typeahead.js',
            'deps' => array('typeahead'),
            'ver' => '0.11.1',
        );

    }

    /**
     * enqueue_scripts
     *
     * @return void
     */
    public function enqueue_scripts() {
        wp_localize_script('search-typeahead', 'search_typeahead', array(
            'url' => admin_url('admin-ajax.php'),
            'action' => 'search_typeahead',
            '_ajax_nonce' => wp_create_nonce('search_typeahead'),
        ));
    }

    /**
     * search_typeahead
     *
     */
    public function search_typeahead() {

        // check_ajax_referer('search_typeahead');

        $s = filter_input(INPUT_GET, 's', FILTER_SANITIZE_STRING);

        $post_types = apply_filters('search_post_types', get_post_types(array('exclude_from_search' => false)));

        $args = array(
            's' => $s,
            'post_type' => $post_types,
            'post_status' => 'publish',
        );

        $args = apply_filters('search_typeahead', $args);

        $query = new \WP_Query($args);

        $posts = array();

        if ($query->posts) {
            foreach ($query->posts as $post) {
                $posts[] = apply_filters('search_typeahead_result', array(
                    'id' => $post->ID,
                    'title' => esc_html($post->post_title),
                    'link' => esc_url(get_permalink($post->ID)),
                ), $post);
            }
        }

        echo json_encode($posts);

        exit;
    }
}

new SearchTypeahead();