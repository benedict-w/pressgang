<?php

namespace Pressgang;

/**
 * Class Schema
 *
 * See - https://iamsteve.me/blog/entry/how-to-use-json-ld-to-replace-microdata-with-wordpress
 *
 * @package Pressgang
 */
class Schema
{
    /**
     * __construct
     *
     */
    public function __construct()
    {
        add_action('wp_head', array($this, 'organization'));
        add_action('wp_head', array($this, 'website'));
        add_action('wp_head', array($this, 'creative_work'));
        add_action('wp_head', array($this, 'webpage'));
        add_action('wp_head', array($this, 'blog_posting'));
        add_action('wp_head', array($this, 'person'));
        add_action('wp_head', array($this, 'job_posting'));
        add_action('wp_head', array($this, 'event'));
    }

    /**
     * organization
     *
     * http://schema.org/Organization
     *
     */
    public function organization()
    {
        $data = array(
            'id' => get_bloginfo('url'),
            'name' => get_bloginfo('name'),
            'url' => get_bloginfo('url'),
            'logo' => get_theme_mod('logo'),
            'same_as' => array_column(get_field('social_networks', 'option'), 'url'),
            'address_locality' => get_field('address_city', 'option'),
            'address_region' => get_field('address_region', 'option'),
            'postal_code' => get_field('address_post_code', 'option'),
            'street_address' => implode(', ', array_filter(array(get_field('address_line_1', 'option'), get_field('address_line_2', 'option'), get_field('address_city', 'option'), get_field('address_post_code', 'option')))),
            'email' => get_field('email', 'option'),
            'telephone' => get_field('phone', 'option'),
            'vat_id' => get_field('vat_registration_number', 'option'),
        );

        \Timber::render('json-ld/organization.twig', $data);
    }

    /**
     * website
     *
     * http://schema.org/Website
     *
     * With SearchAction
     * https://developers.google.com/search/docs/data-types/sitelinks-searchbox
     * http://schema.org/SearchAction
     *
     * @return mixed
     */
    public function website()
    {
        $data = array(
            'id' => get_bloginfo('url'),
            'name' => get_bloginfo('name'),
            'url' => get_bloginfo('url'),
        );

        \Timber::render('json-ld/website.twig', $data);
    }

    /**
     * creative Work
     *
     * http://schema.org/CreativeWork
     *
     */
    public function creative_work() {
        if (is_singular('project')) {

            $post = \Timber::get_post();

            $contributors = array();

            if ($project_leaders = $post->get_field('project_leaders')) {
                foreach ($project_leaders as $project_leader) {
                    $contributors[] = array(
                        'name' => esc_html($project_leader->name),
                    );
                }
            }
            if ($post)

                $data = array(
                    'organization' => array(
                        'name' => get_bloginfo('name'),
                        'same_as' => get_bloginfo('url'),
                    ),
                    'url' => $post->link,
                    'headline' => $post->title,
                    'description' => $post->get_preview(20, false, false),
                    'contributors' => $contributors,
                    'thumbnail_url' => !empty($post->thumbnail()) ? $post->thumbnail->src : '',
                    'keywords' => implode(', ', $post->terms('sector')),
                );

            \Timber::render('json-ld/creative-work.twig', $data);
        }
    }

    /**
     * web_page
     *
     * http://schema.org/WebPage
     *
     * @return mixed
     */
    public function webpage()
    {
        if (is_page()) {

            $post = \Timber::get_post();

            $data = array(
                'publisher' => get_bloginfo('url'),
                'headline' => $post->title,
                'image' => !empty($post->thumbnail()) ? $post->thumbnail->src : '',
                'main_content_of_page' => $post->link,
                'primary_image_of_page' => isset($post->thumbnail) ? $post->thumbnail->src : '',
                'last_reviewed' => $post->modified_date('Y-m-d H:i:s'),
            );

            \Timber::render('json-ld/webpage.twig', $data);
        }
    }

    /**
     * blogposting
     *
     * http://schema.org/BlogPosting
     *
     */
    public function blog_posting() {

        if (is_single() &&  get_post_type() === 'post') {

            $post = \Timber::get_post();
            self::add_blog_posting($post);

        }
    }

    /**
     * add_blog_posting
     *
     * http://schema.org/BlogPosting
     *
     * @param $post
     */
    public static function add_blog_posting($post) {
        $data = array(
            'publisher' => get_bloginfo('url'),
            'author' => $post->author,
            'headline' => $post->post_title,
            'article_body' => $post->post_content,
            'date_published' => $post->date('Y-m-d H:i:s'),
            'image' => !empty($post->thumbnail()) ? $post->thumbnail->src : get_template_directory_uri() . '/dist/images/1x/logo-all.png',
            'date_modified' => $post->modified_date('Y-m-d H:i:s'),
            'main_entity_of_page' => $post->link,
        );

        \Timber::render('json-ld/blog-posting.twig', $data);
    }

    /**
     * person
     *
     * http://schema.org/Person
     *
     */
    public function person() {

        if (is_single() &&  get_post_type() === 'team_member') {

            $post = \Timber::get_post();
            self::add_person($post);

        }
    }

    /**
     * add_person
     *
     * @param $post
     */
    public static function add_person($post) {
        $data = array(
            'given_name' => $post->get_field('person_firstname'),
            'family_name' => $post->get_field('person_surname'),
            'awards' => $post->get_field('person_qualifications'),
            'image' => !empty($post->thumbnail()) ? $post->thumbnail->src : '',
            'url' => $post->link,
            'job_title' => implode(', ', $post->terms('person_postition')),
            'works_for' => get_bloginfo('url'),
            'work_location' => get_field('address', 'option'),
        );

        \Timber::render('json-ld/person.twig', $data);
    }

    /**
     * job_posting
     *
     * http://schema.org/JobPosting
     *
     */
    public function job_posting() {

        if (is_single() &&  get_post_type() === 'job') {

            $post = \Timber::get_post();

            $data = array(
                'title' => $post->title,
                'description' => wp_strip_all_tags($post->post_content),
                'employment_type' => $post->get_field('job_type'),
                'base_salary' => $post->get_field('job_salary'),
                'valid_through' => $post->get_field('date_end'),
                'hiring_organization' => get_bloginfo('url'),
                'date_posted' => $post->date,
            );

            \Timber::render('json-ld/job-posting.twig', $data);

        }
    }

    /**
     * event
     *
     * http://schema.org/Event
     *
     */
    public function event() {

        if (is_single() &&  get_post_type() === 'event') {

            $post = \Timber::get_post();

            $data = array(
                'start_date' => $post->get_field('start_date'),
                'end_date' => $post->get_field('end_date'),
                'url' => $post->link,
                'name' => $post->title,
                'description' => wp_strip_all_tags($post->post_content),
                'image' => !empty($post->thumbnail()) ? $post->thumbnail->src : '',
                'location' => $post->get_field('post_map') ? $post->get_field('post_map')['address'] : $post->get_field('custom_location'),
            );

            \Timber::render('json-ld/event.twig', $data);

        }
    }
}

new Schema();
