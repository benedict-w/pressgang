<?php
/**
 *
 * This class allows the loading of the Pressgang page templates in the child theme's settings.php
 */

namespace PressGang;

class Templates {

    /**
     * $templates
     *
     * Templates to be loaded, specified in settings.php
     *
     * @var array
     */
    protected $templates;

    /**
     * $templates_folder
     *
     * @var string
     */
    private $templates_folder = 'page-templates';

    /**
     * __construct
     *
     * initializes the plugin by setting filters and administration functions.
     */
    public function __construct() {

        $this->templates = Config::get('templates');

        // inject pressgang templates into the page attributes drop down
        add_filter('page_attributes_dropdown_pages_args', array($this, 'register_templates'));

        // on save post to inject template into the page cache
         add_filter('wp_insert_post_data', array($this, 'register_templates'));

        // assign template paths
        add_filter('template_include', array($this, 'view_template'));
    }

    /**
     * register_project_templates
     *
     * adds templates to the pages cache in order to trick Wordpress into thinking the template file exists.
     *
     * @param $atts
     *
     * @return $atts
     */
    public function register_templates($atts) {

        // create the key used for the themes cache
        $cache_key = 'page_templates-' . md5(get_theme_root() . '/' . get_stylesheet());

        // retrieve the cache list
        $templates = wp_get_theme()->get_page_templates();
        if (empty($templates)) {
            $templates = array();
        }

        // clear the existing cache
        wp_cache_delete($cache_key , 'themes');

        // merge existing templates with pressgang templates
        $templates = array_merge($templates, $this->templates);

        // add to cache
        wp_cache_add($cache_key, $templates, 'themes', 1800);

        return $atts;
    }

    /**
     * view_project_template
     *
     * Checks if the template is assigned to the page
     *
     * @param $template
     *
     * @return mixed $template|$file
     */
    public function view_template($template) {

        global $post;

        if ($post) {

            if (!isset($this->templates[get_post_meta($post->ID, '_wp_page_template', true)])) {
                return $template;
            }

            $file = sprintf("%s/%s/%s", get_template_directory(), $this->templates_folder, get_post_meta($post->ID, '_wp_page_template', true));

            // sanity check the file exists
            if (file_exists($file)) {
                return $file;
            } else {
                throw new \exception(sprintf(__("Template file: '%' is missing!", THEMENAME), $file));
            }
        }

        return $template;
    }

}

new Templates();