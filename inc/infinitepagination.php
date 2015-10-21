<?php

namespace PressGang;

/**
 * Class InfinitePagination
 *
 * Class handles Wordpress paging and adds an infinite paginator to the theme
 *
 */
class InfinitePagination {

    public static $posts_per_page = 4;

    /**
     * init
     *
     */
    public static function init() {
        add_action('wp_ajax_infinite_scroll', array('PressGang\Paginator', 'infinte_pagination')); // user logged in
        add_action('wp_ajax_nopriv_infinite_scroll', array('PressGang\Paginator', 'infinte_pagination')); // not logged in

        add_action('wp_footer', array('PressGang\Paginator', 'infinite_script'));
        add_action('pre_get_posts', array('PressGang\Paginator', 'set_query_offset'));
    }

    /**
     * infinte_pagination
     *
     * Filter the query with 'infinite_paginator_query'.
     * Hook Template with action 'infinte_pagination'.
     *
     * @return void
     */
    public static function infinte_pagination()
    {
        $post_type = filter_input(INPUT_POST, 'post_type', FILTER_SANITIZE_STRING);
        $paged = filter_input(INPUT_POST, 'page_no', FILTER_SANITIZE_NUMBER_INT);

        $query = array(
            'paged' => $paged,
            'posts_per_page' => self::$posts_per_page,
            'post_type' => $post_type,
        );

        apply_filters('infinte_pagination', $query);

        // load the posts
        query_posts($query);

        do_action('infinte_pagination');

        exit;
    }

    /**
     * infinite_script
     *
     * @return void
     */
    public static function infinite_script() {
        if(!is_single() && !is_page()) : ?>
        <!-- infinite pagination -->
        <script type="text/javascript">
            jQuery(document).ready(function($) {

                $('.page-numbers').remove();

                var page = 2;
                var fetched_all = false;

                var $spinner = $('<div id="infinite-pagination-spinner" class="spinner"></div>');

                $('body > .container > .row:last').after($spinner);

                $(window).scroll(function() {

                    $spinner = $('#infinite-pagination-spinner');

                    if  ($(window).scrollTop() == $(document).height() - $(window).height()) {

                        if (!fetched_all) {

                            $spinner.fadeIn(250);

                            var search = window.location.search.replace('?', '').split('&');
                            var query = {};

                            for(var i in search){
                                query[search[i].split('=')[0]] = search[i].split('=')[1];
                            }

                            query['action'] = 'infinite_scroll';
                            query['page_no'] = page;
                            query['post_type'] = $('#searchform input[name=post_type]').val();

                            $.ajax({
                                url: "<?php bloginfo('wpurl') ?>/wp-admin/admin-ajax.php",
                                type: 'POST',
                                data: $.param(query),
                                success: function (html) {

                                    var $html = $(html).filter('.row').addClass('fadeIn')
                                    $spinner.hide();

                                    fetched_all = !$html.length;

                                    $('body > .container > .row:last').before($html);
                                }
                            });

                            page++;
                        }
                    }
                });
            });
        </script><?php
        endif;
    }

    /**
     * set_query_offset
     *
     * Manually determine page query offset (offset + current page (minus one) x posts per page)
     *
     * @param $query
     */
    public static function set_query_offset(&$query) {
        if (!is_admin() && $query->is_paged && $query->query_vars['paged'] > 1) {
            $offset = get_option('posts_per_page');
            $page_offset = $offset + (($query->query_vars['paged'] - 2) * self::$posts_per_page);
            $query->set('offset', $page_offset);
        }
    }
}

InfinitePagination::init();