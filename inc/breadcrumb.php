<?php

namespace PressGang;

/**
 * Class Breadcrumb
 *
 * @package PressGang
 */
class Breadcrumb {

    protected $separator;
    protected $breadcrumbs_id;
    protected $breadcrumbs_class;
    protected $home_title;

    protected $custom_taxonomy;

    public $breadcrumbs = array();

    /**
     * __construct
     *
     */
    public function __construct() {
        $this->breadcrumbs_class = 'breadcrumb';
        $this->home_title  = __("Home", THEMENAME);

        add_filter('get_twig', array($this, 'add_to_twig'));
    }

    /**
     * add_to_twig
     *
     * Add a 'breadcrumb' function to the Twig scope
     *
     * @param $twig
     * @return mixed
     */
    public function add_to_twig($twig) {
        $twig->addFunction(new \Twig_SimpleFunction('breadcrumb', array($this, 'render')));
        return $twig;
    }

    /**
     * Breadcrumb Links
     *
     */
    public function links() {

        $prefix = '';

        global $post, $wp_query;

        // do not display on the homepage
        if ( !is_front_page() ) {

            // home page
            $this->append_link($this->home_title, 'breadcrumb-home', get_site_url());

            if ( is_archive() && !is_tax() && !is_category() && !is_tag() ) {

                $this->append_link(post_type_archive_title($prefix, false), 'breadcrumb-archive breadcrumb-current');

            }

            else if ( is_archive() && is_tax() && !is_category() && !is_tag() ) {

                // if custom post type
                if ($post_type = get_post_type()) {

                    if ($post_type !== 'post') {

                        $post_type_object = get_post_type_object($post_type);
                        $post_type_archive = get_post_type_archive_link($post_type);

                        $this->append_link($post_type_object->labels->name, "breadcrumb-{$post_type}", $post_type_archive);
                    }
                }

                $this->append_link(get_queried_object()->name, 'breadcrumb-taxonomy breadcrumb-current');

            }

            else if ( is_single() ) {

                $post_type = get_post_type();

                $this->add_archive_link($post_type);

                // get post category info
                $category = get_the_category();

                if(!empty($category)) {

                    // get the last post category
                    $last_category = array_values($category);
                    $last_category = end($last_category);

                    // get the parent categories
                    $get_cat_parents = rtrim(get_category_parents($last_category->term_id, true, ','), ',');
                    $cat_parents = explode(',', $get_cat_parents);

                    // create breadcrumbs for parents
                    foreach($cat_parents as $parent) {
                        $this->append_link($parent, 'breadcrumb-parent-category breadcrumb-current');
                    }

                }

                // if a custom post type within a custom taxonomy
                $taxonomy_exists = taxonomy_exists($this->custom_taxonomy);
                if(empty($category) && !empty($this->custom_taxonomy) && $taxonomy_exists) {

                    $taxonomy_terms = get_the_terms( $post->ID, $this->custom_taxonomy );
                    $cat_id         = $taxonomy_terms[0]->term_id;
                    $cat_nicename   = $taxonomy_terms[0]->slug;
                    $cat_link       = get_term_link($taxonomy_terms[0]->term_id, $this->custom_taxonomy);
                    $cat_name       = $taxonomy_terms[0]->name;

                    $this->append_link($cat_nicename, "breadcrumb-{$post_type}-{$cat_name}", $cat_link);

                }

                $this->append_link(get_the_title(), 'breadcrumb-current');


            } else if (is_category()) {

                $post_type = get_post_type();

                $this->add_archive_link($post_type);

                // category page
                $this->append_link(single_cat_title('', false), 'breadcrumb-category');

            } else if (is_page()) {

                // standard page
                if($post->post_parent){

                    // if child page, get parents
                    $ancestors = get_post_ancestors($post->ID);

                    // get parents in the right order
                    $ancestors = array_reverse($ancestors);

                    // parent page loop
                    foreach ($ancestors as $ancestor) {
                        $this->append_link(get_the_title($ancestor), "breadcrumb-page breadcrumb-{$ancestor}", get_permalink($ancestor));
                    }

                    // current page
                    $this->append_link(get_the_title(), 'breadcrumb-page breadcrumb-current');

                } else {

                    // just display current page if no parents
                    $this->append_link(get_the_title(), 'breadcrumb-page breadcrumb-current');

                }

            } else if ( is_tag() ) {

                // tag page

                // get tag information
                $term_id = get_query_var('tag_id');
                $taxonomy = 'post_tag';
                $args = "include={$term_id}";
                $terms = get_terms($taxonomy, $args);

                // display the tag name
                $this->append_link($terms[0]->name, "breadcrumb-tag breadcrumb-{$terms[0]->slug} breadcrumb-current");

            } elseif ( is_day() ) {
                // year link
                $this->append_link(get_the_time('Y'),  "breadcrumb-year", get_year_link( get_the_time('Y') ));

                // month link
                $this->append_link(get_the_time('M'),  "breadcrumb-month", get_month_link(get_the_time('Y'), get_the_time('m')));

                // day display
                $this->append_link(sprintf("%s %s", get_the_time('jS'), get_the_time('M')), "breadcrumb-day");

            } else if ( is_month() ) {
                // year link
                $this->append_link(get_the_time('Y'),  "breadcrumb-year", get_year_link( get_the_time('Y')));

                // month link
                $this->append_link(get_the_time('M'),  "breadcrumb-month breadcrumb-current");

            } else if ( is_year() ) {

                $this->append_link(get_the_time('Y'),  "breadcrumb-year breadcrumb-current");

            } else if ( is_author() ) {

                global $author;
                $userdata = get_userdata( $author );
                $this->append_link($userdata->display_name, "breadcrumb-author bread-crumb-{$userdata->user_nicename}");

            } else if ( get_query_var('paged') ) {

                // paged archives
                $this->append_link(get_query_var('paged'), "breadcrumb-paged breadcrumb-current");

            } else if ( is_search() ) {

                // search results page
                $this->append_link(get_search_query(), "breadcrumb-search breadcrumb-current");

            } elseif ( is_404() ) {

                // 404
                $this->append_link(__("Error 404", THEMENAME), "breadcrumb-404 breadcrumb-current");
            }

        }
    }

    /**
     * add_archive_link
     *
     * @param $post_type
     */
    private function add_archive_link($post_type) {

        $post_type_object = get_post_type_object($post_type);
        $post_type_archive = get_post_type_archive_link($post_type);

        $archive_title = $post_type === 'post'
            ? get_the_title(get_option('page_for_posts', true))
            : $post_type_object->labels->name;

        $this->append_link($archive_title, "breadcrumb-{$post_type}", $post_type_archive);
    }

    /**
     * append_link
     *
     * @param $title
     * @param string $class
     * @param null $url
     */
    private function append_link($title, $class = '', $url = null) {
        $this->breadcrumbs[] = array(
            'title' => $title,
            'class' => $class,
            'url' => $url,
        );
    }

    /**
     * render
     *
     * Renders the breadcrumb and linked to {{ breadcrumb() }} in Twig
     *
     */
    public function render() {

        $this->links();

        \Timber::render('breadcrumb.twig', array(
            'class' => $this->breadcrumbs_class,
            'breadcrumbs' => $this->breadcrumbs,
        ));
    }
}

new Breadcrumb();