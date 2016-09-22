<?php

namespace PressGang;

/**
 * Class ArchiveTitles
 *
 * @package PressGang
 */
class ArchiveTitles {

    /**
     * __construct
     *
     */
    public function __construct() {
        add_action('customize_register', array($this, 'customizer'));
        add_filter('get_the_archive_title', array($this, 'custom_archive_title'));
    }

    /**
     * Add to customizer
     *
     * TODO add a config file?
     *
     * @param $wp_customize
     */
    public static function customizer($wp_customize) {

        $wp_customize->add_section('archive-titles' , array(
            'title' => __("Archive Titles", THEMENAME),
        ));

        // default

        $wp_customize->add_setting(
            'archives_title',
            array(
                'default' => "Archives",
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control( new \WP_Customize_Control( $wp_customize, 'archives_title', array(
            'label' => __("Archives Title", THEMENAME),
            'section'  => 'archive-titles',
        )));

        // cat

        $wp_customize->add_setting(
            'single_cat_title',
            array(
                'default' => "Category: %%s",
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control( new \WP_Customize_Control( $wp_customize, 'single_cat_title', array(
            'label' => __("Single Category Title", THEMENAME),
            'section'  => 'archive-titles',
        )));

        // tag

        $wp_customize->add_setting(
            'single_tag_title',
            array(
                'default' => 'Tag: %%s',
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control( new \WP_Customize_Control( $wp_customize, 'single_tag_title', array(
            'label' => __("Single Tag Title", THEMENAME),
            'section'  => 'archive-titles',
        )));

        // author

        $wp_customize->add_setting(
            'single_author_title',
            array(
                'default' => 'Author: %%s',
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control( new \WP_Customize_Control( $wp_customize, 'single_author_title', array(
            'label' => __("Single Author Title", THEMENAME),
            'section'  => 'archive-titles',
        )));

        // year

        $wp_customize->add_setting(
            'single_year_title',
            array(
                'default' => 'Year: %%s',
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control( new \WP_Customize_Control( $wp_customize, 'single_year_title', array(
            'label' => __("Single Year Title", THEMENAME),
            'section'  => 'archive-titles',
        )));

        // month

        $wp_customize->add_setting(
            'single_month_title',
            array(
                'default' => 'Month: %%s',
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control( new \WP_Customize_Control( $wp_customize, 'single_month_title', array(
            'label' => __("Single Month Title", THEMENAME),
            'section'  => 'archive-titles',
        )));

        // day

        $wp_customize->add_setting(
            'single_day_title',
            array(
                'default' => 'Day: %%s',
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control( new \WP_Customize_Control( $wp_customize, 'single_day_title', array(
            'label' => __("Single Day Title", THEMENAME),
            'section'  => 'archive-titles',
        )));

        // archives

        $wp_customize->add_setting(
            'post_type_archive_title',
            array(
                'default' => 'Archives: %%s',
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control( new \WP_Customize_Control( $wp_customize, 'post_type_archive_title', array(
            'label' => __("Post Type Archive Title", THEMENAME),
            'section'  => 'archive-titles',
        )));


        // search results

        $wp_customize->add_setting(
            'search_results_title',
            array(
                'default' => 'Search Results for &#8220;%%s&#8221;',
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control( new \WP_Customize_Control( $wp_customize, 'search_results_title', array(
            'label' => __("Search Results Title", THEMENAME),
            'section'  => 'archive-titles',
        )));

    }

    /**
     * custom_archive_title
     *
     * @return string
     */
    public function custom_archive_title($title)
    {
        if (is_category()) {
            $title = sprintf(__(get_theme_mod('single_cat_title'), THEMENAME), single_cat_title('', false));
        } else if (is_tag()) {
            $title = sprintf(__(get_theme_mod('single_tag_title'), THEMENAME), single_tag_title('', false));
        } else if (is_author()) {
            $title = sprintf(__(get_theme_mod('single_author_title'), THEMENAME ), '<span class="vcard">' . get_the_author() . '</span>');
        } else if (is_year()) {
            $title = sprintf(__(get_theme_mod('single_year_title'), THEMENAME ), get_the_date(_x('Y', 'yearly archives date format')));
        } else if (is_month()) {
            $title = sprintf(__(get_theme_mod('single_month_title'), THEMENAME ), get_the_date(_x('F Y', 'monthly archives date format')));
        } else if (is_day()) {
            $title = sprintf(__(get_theme_mod('single_day_title'), THEMENAME ), get_the_date(_x('F j, Y', 'daily archives date format')));
        } elseif(is_post_type_archive()) {
            $title = sprintf(__(get_theme_mod('post_type_archive_title'), THEMENAME ), post_type_archive_title( '', false ) );;
        } else if (is_search()) {
            $title = sprintf(__(get_theme_mod('search_results_title')), get_search_query());
        } else if ($title === 'Archives') {
            $title = __(get_theme_mod('archives_title'));
        }

       return $title;
    }
}

new ArchiveTitles();