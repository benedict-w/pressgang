<?php

namespace PressGang;

class TrackPostViews {

    const COUNT_KEY = 'pressgang_post_views_count';

    /**
     * __construct
     *
     * @return void
     */
    public function __construct() {
        add_action('wp_head', array('\Pressgang\TrackPostViews', 'track_post_views'));
        add_action('wp_head', array('\Pressgang\TrackPostViews', 'cache_breaker'));
        add_action('the_post', array('\Pressgang\TrackPostViews', 'add_views_to_post'));

        // to keep the count accurate, get rid of pre-fetching
        remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);
    }

    /**
     * add_views_to_post
     *
     * @param $post
     * @return mixed
     */
    public static function add_views_to_post($post) {
        $post->views = self::get_post_views($post->ID);
        return $post;
    }

    /**
     * get_post_views
     *
     * @param $post_id
     * @return int
     */
    public static function get_post_views($post_id){
        return intval(get_post_meta($post_id, self::COUNT_KEY, true));
    }

    /**
     * set_post_views
     *
     * @param int $post_id
     */
    public static function set_post_views($post_id) {

        $count = intval(get_post_meta($post_id, self::COUNT_KEY, true));

        if(!$count){
            delete_post_meta($post_id, self::COUNT_KEY);
            add_post_meta($post_id, self::COUNT_KEY, $count);
        } else {
            $count++;
            update_post_meta($post_id, self::COUNT_KEY, $count);
        }
    }

    /**
     * track_post_views
     *
     * @param $post_id
     */
    public static function track_post_views ($post_id) {
        if (is_single()) {
            if (empty($post_id)) {
                global $post;
                $post_id = $post->ID;
            }

            self::set_post_views($post_id);
        }
    }

    /**
     * get_most_popular
     *
     * @param string $post_type
     * @param int $number
     * @return \WP_Query
     */
    public static function get_most_popular($post_type = 'post', $number = 0) {

        return \Timber::get_posts(array(
            'post_type' => $post_type,
            'posts_per_page' => $number ? $number : get_option('posts_per_page'),
            'meta_key' => self::COUNT_KEY,
            'orderby' => 'meta_value_num',
            'order' => 'DESC',

        ));
    }

    /**
     * cache_breaker
     *
     * add mfunc to update the view on cached pages
     *
     */
    public static function cache_breaker() {
        if (is_single()) { ?>
            <!-- mfunc \Pressgang\TrackPostViews::set_post_views($post_id); --><!-- /mfunc -->
            <?php
        }
    }
}

new TrackPostViews();