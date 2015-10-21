<?php

namespace PressGang;

/**
 * Class OpenGraph
 *
 * @package PressGang
 */
class OpenGraph {

    /**
     * init
     *
     */
    public static function init() {
        add_action('wp_head', array('PressGang\OpenGraph', 'fb_opengraph'), 5);
    }

    /**
     * fb_opengraph
     *
     */
    public static function fb_opengraph() {
        global $post, $wp;

        if ($post) :
        $img = has_post_thumbnail($post->ID)
            ? wp_get_attachment_image_src(get_post_thumbnail_id( $post->ID ), 'medium')[0]
            : (get_theme_mod('og_img')
                ? get_theme_mod('og_img')
                : esc_url(get_theme_mod('logo')));

        $description = is_single()
            ? str_replace('', "'", strip_tags(wp_trim_words($post->post_excerpt ? $post->post_excerpt : wp_strip_all_tags($post->post_content))))
            : get_bloginfo('description');

        $type = 'website';
        if(is_author()) {
            $type = 'profile';
        } elseif (is_single()) {
            $type = 'article';
        }

        // filters
        $img = esc_url(apply_filters('facebook_og_img', $img));
        $description = esc_attr(apply_filters('facebook_og_description', $description));
        $type = esc_attr(apply_filters('facebook_og_type', $type));
        $url = rtrim(esc_url(apply_filters('facebook_og_url', home_url(add_query_arg(array(), $wp->request)))), '/') . '/'; // slash fixes Facebook Debugger "Circular Redirect Path"
        $sitename = esc_attr(apply_filters('facebook_og_sitename', get_bloginfo()));
        $title = esc_attr(apply_filters('facebook_og_sitename', wp_title('|', false, 'right')));

        ?>
        <!-- open graph -->
        <meta property="og:title" content="<?php echo $title; ?>"/>
        <meta property="og:description" content="<?php echo $description; ?>"/>
        <meta property="og:type" content="<?php echo $type ?>"/>
        <meta property="og:url" content="<?php echo $url; ?>"/>
        <meta property="og:site_name" content="<?php echo $sitename; ?>"/>
        <meta property="og:image" content="<?php echo $img; ?>"/>
        <?php endif;
    }
}

OpenGraph::init();