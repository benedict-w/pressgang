<?php

namespace PressGang;

require_once('loader.php');
require_once('site.php');

/**
 * Class Site
 *
 * @package PressGang
 */
class Site extends \TimberSite
{
    public $stylesheet;
    public $keywords;
    public $logo;
    public $copyright;

    /**
     *__construct
     *
     * @param string|int $site_name_or_id
     */
    function __construct($site_name_or_id = null)
    {
        $this->stylesheet = get_theme_mod('stylesheet', 'styles.css');

        // add custom params
        $this->keywords = apply_filters('site_keywords', implode(', ', array_map(function ($tag) {
            return $tag->name;
        }, get_tags(array('orderby' => 'count', 'order' => 'DESC', 'number' => 20)))));

        $this->logo = apply_filters('site_logo', get_theme_mod('logo'));
        $this->copyright = apply_filters('site_copyright', get_theme_mod('copyright'));

        // replace the site icon with an image object
        if ($this->site_icon) {
            $this->site_icon = new \TimberImage($this->site_icon);
        }

        // google webmaster site verification code
        $this->google_site_verification = get_theme_mod('google_verification_code');

        add_filter('timber_context', array($this, 'add_to_context'));
        add_filter('get_twig', array($this, 'add_to_twig'));

        add_filter('meta_description', array('PressGang\Site', 'meta_description'));

        parent::__construct($site_name_or_id);
    }

    /**
     * add_to_context
     *
     * @param $context
     * @return mixed
     */
    public function add_to_context( $context ) {
        $context['site'] = $this;
        return $context;
    }

    /**
     * add_to_twig
     *
     * Add Custom Functions to Twig
     */
    public function add_to_twig( $twig ) {
        $twig->addFunction('esc_attr', new \Twig_SimpleFunction('esc_attr', 'esc_attr'));
        $twig->addFunction('esc_url', new \Twig_SimpleFunction('esc_url', 'esc_url'));
        $twig->addFunction('get_search_query', new \Twig_SimpleFunction('get_search_query', 'get_search_query'));

        $twig->addFunction('meta_description', new \Twig_SimpleFunction('meta_description', array('PressGang\Site', 'meta_description')));

        // add text-domain to global
        $twig->addGlobal('THEMENAME', THEMENAME);
        return $twig;
    }

    /**
     * add meta_description
     *
     * hook after post has loaded to add a unique meta-description
     *
     */
    public static function meta_description()
    {
        $post = new \TimberPost();

        // check for custom field
        $description = wptexturize($post->get_field('meta_description'));

        // else use preview
        if (empty($description)) {
            $description = str_replace('', "'", $post->get_preview(40, true, false, true));
        }

        // finally use the blog description
        if (empty($description)) {
            $description = get_bloginfo('description', 'raw');
        }

        $description = esc_attr($description);

        // limit to SEO recommended length
        if (strlen($description) > 155) {
            $description = substr($description, 0, 155);
            $description = \TimberHelper::trim_words($description, str_word_count($description) - 1);
        }

        return $description;
    }
}

new Site();