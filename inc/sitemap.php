<?php

namespace PressGang;

class Sitemap
{
    private $change_frequency = 'weekly';
    private $priority = '0.5';

    // available frequencies in order of least recent first.
    private $frequencies = array('never', 'yearly', 'monthly', 'weekly', 'daily', 'hourly', 'always');

    /**
     * __construct
     *
     */
   public function __construct($change_frequency = 'daily', $priority = '0.5')
   {
       // set the default change frequency
        if(in_array($change_frequency, $this->frequencies)) {
            $this->change_frequency = $change_frequency;
        }

        // set the default priority
        $this->priority = number_format($priority, 1);

        // update sitemap.xml when a post is saved
        add_action("save_post", array($this, 'create_sitemap'), 10, 3);

        // add a sitemap shortcode
        add_shortcode('sitemap', array($this, 'html_sitemap'));

       // we need to redirect requests to individual sitemaps files on multisite installs
       if (is_multisite()) {
           // TODO - not working! add redirect manually
           // add_action('init', array($this, 'add_rewrite'));
       }
   }

    /**
     * Function to create sitemap.xml file in root directory
     *
     */
    public function create_sitemap($post_id, $post, $update)
    {
        // only create a site map if the post is new or no file exists
        if (wp_is_post_revision($post_id) || is_file($this->path())) {
            return false;
        }

        $post_types = array('page', 'post');

        $custom_post_types = Config::get('custom-post-types');

        // add custom post types that have pages
        foreach ($custom_post_types as $post_type => $params) {
            if ((!isset($params['query_var']) || $params['query_var'] == true) || (isset($params['query_var']) && $params['query_var'] == true)) {
                $post_types[] = $post_type;
            }
        }

        $posts = \Timber::get_posts(array(
            'numberposts' => -1,
            'orderby' => 'modified',
            'post_type' => $post_types,
            'order' => 'DESC',
            'post_status' => 'publish',
        ));

        foreach($posts as &$post) {

            // see if a custom field has been set for the post change frequency
            $change_frequency = $post->get_field('change_frequency');

            if (!in_array($change_frequency, $this->frequencies)) {

                // otherwise determine a value for the change frequency based on the last modified param
                $interval = date_diff(new \DateTime($post->post_modified), new \DateTime("now"));

                $change_frequency = 'never';

                if ($interval->y > 0) {
                    $change_frequency = 'yearly';
                } else if ($interval->m > 0) {
                    $change_frequency = 'monthly';
                } else if ($interval->d > 6) {
                    $change_frequency = 'weekly';
                } else if ($interval->d > 0) {
                    $change_frequency = 'daily';
                } else if ($interval->h > 0) {
                    $change_frequency = 'hourly';
                } else if ($interval->i > 0) {
                    $change_frequency = 'always';
                }
            }

            $val = apply_filters('sitemap_post_change_frequency', $change_frequency, $post);

            if (in_array($val, $this->frequencies)) {
                $change_frequency = $val;
            }

            $index = array_search($change_frequency, $this->frequencies);

            // set the most recent change_frequency
            if ($index > array_search($this->change_frequency, $this->frequencies)) {
                $this->change_frequency = $change_frequency;
            }

            $post->change_frequency = $change_frequency;

            // see if a custom field has been set for the post change frequency
            $priority = $post->get_field('priority');

            if (!$priority) {
                // otherwise set it to default
                $priority = $this->priority;
            }

            $post->priority = number_format(apply_filters('sitemap_post_priority', $priority, $post), 1);
        }

        $data = array(
            'home_url' => home_url('/'),
            'change_frequency' => $this->change_frequency,
            'priority' => $this->priority,
            'posts' => $posts,
        );

        $sitemap = \Timber::compile('sitemap-xml.twig', $data);

        $path = $this->path();

        $fp = fopen($path, 'w');
        fwrite($fp, $sitemap);
        fclose($fp);
    }

    /**
     * html_sitemap
     *
     * @param array $post_types
     */
    public function html_sitemap ($atts = array()) {

        $atts = shortcode_atts( array(
            'post_type' => 'page',
        ), $atts );

        $data['posts'] = \Timber::get_posts(array(
            'numberposts' => -1,
            'post_type' => $atts['post_type'],
            'post_status' => 'publish',
        ));

        return \Timber::compile('sitemap-html.twig', $data);
    }

    /**
     * filename
     *
     * @return string
     */
    private function filename() {

        $filename = "sitemap.xml";

        if (is_multisite()) {
            $filename = sprintf("%s-sitemap.xml", get_blog_details()->domain);
        }

        return $filename;
    }

    /**
     * path
     *
     * @return string
     */
    private function path() {
        return sprintf("%s%s", ABSPATH, $this->filename());
    }

    /**
     * add_rewrite
     *
     */
    public function add_rewrite() {
        global $wp_rewrite;
        add_rewrite_rule('sitemap\.xml$', $this->filename());
        $wp_rewrite->flush_rules();
    }
}

new Sitemap();