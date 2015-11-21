<?php
/**
 * Importer
 *
 * Custom Base class for importing posts
 *
 * @package WordPress
 * @subpackage Importer
 */

namespace PressGang;

if ( !defined('WP_LOAD_IMPORTERS') ) {
    return;
}

// load importer api
require_once ABSPATH . 'wp-admin/includes/import.php';

if (!class_exists('WP_Importer')) {
    $class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
    if ( file_exists( $class_wp_importer ) ) {
        require_once $class_wp_importer;
    }
}

if ( class_exists( 'WP_Importer' ) ) {

    /**
     * Class Importer
     *
     */
    class PostImporter extends \WP_Importer {

        private $id;
        private $file;

        /*
         * @format
         *
         *  array('field_name' => array(
         *      'filter' => FILTER_SANITIZE_STRING // $filter param for filter_var() func
         *      'filter_options' => null // filter $option param for filter_var() func
         *  )
         */
        protected $header_settings = array();

        const DELIMITER = ',';

        /**
         * __construct
         *
         */
        public function __construct() {
            parent::__construct();
            add_action('importer_meta_field_update', array($this, 'update_post_meta'), 10, 3);
        }

        /**
         * Importer_Import
         */
        public function Importer_Import() { }

        /**
         * header
         *
         */
        public function header() {
            \Timber::render('importer/header.twig');
        }

        /**
         * footer
         */
        public function footer() {
            \Timber::render('importer/footer.twig');
        }

        /**
         * greet
         *
         */
        public function greet() {
            $action = add_query_arg('step', 1);
            \Timber::render('importer/greet.twig', array('action' => $action));
        }

        /**
         * dispatch
         *
         * Registered callback function for the Custom Importer
         *
         * Manages the separate stages of the import process
         *
         */
        public function dispatch() {
            $this->header();

            if (empty ($_GET['step'])) {
                $step = 0;
            }
            else {
                $step = (int)$_GET['step'];
            }

            switch ($step) {
                case 0 :
                    $this->greet();
                    break;
                case 1 :
                    check_admin_referer('import-upload');
                    set_time_limit(0);
                    $result = $this->import();
                    if ( is_wp_error( $result ) ) {
                        echo $result->get_error_message();
                    }
                    break;
            }

            $this->footer();
        }

        /**
         * import
         *
         * The main controller for the actual import stage. Contains all the import steps.
         *
         * @param none
         * @return none
         */
        public function import() {
            $file = wp_import_handle_upload();

            if ( isset( $file['error'] ) ) {

                \Timber::render('importer/error.twig', array(
                    'error' => esc_html($file['error']),
                ));

                return false;

            } else if (!file_exists($file['file'])) {

                \Timber::render('importer/error.twig', array(
                    'error' => sprintf(__("The export file could not be found at <code>%s</code>. It is likely that this was caused by a permissions problem.", TEXTDOMAIN), esc_html($file['file'])),
                ));

                return false;
            }

            $this->id = (int)$file['id'];
            $this->file = get_attached_file($this->id);
            $result = $this->process_posts();
            if (is_wp_error($result)) {
                return $result;
            }
        }

        /**
         * process_posts
         *
         * Imports posts and loads $this->posts
         *
         * @uses $wpdb
         *
         * @param none
         * @return none
         */
        public function process_posts() {

            $headers = array();
            $content = array();

            // read file contents first
            if (($handle = fopen($this->file, 'r')) !== false) {
                $row = 0;
                while (($data = fgetcsv($handle, 1000, self::DELIMITER)) !== false) {
                    // read headers
                    if ($row === 0) {
                        $headers = $data;
                    } else {
                        $content[] = $data;
                    }
                    $row++;
                }
            }

            fclose($handle);

            $results = array('errors' => 0, 'updated' => 0);

            // Check for invalid headers
            $matches = preg_grep('/^(' . implode('|', array_keys($this->header_settings)) . ')$/', $headers, PREG_GREP_INVERT);

            if ($matches) {
                \Timber::render('importer/invalid-headers.twig', array('headers' => $matches));
            }

            // store the post data
            if ($headers && $content) {
                // read cols
                foreach ($content as $row => &$cols) {

                    $post_id = $content[$row][array_search('ID', $headers)];

                    $post = get_post($post_id);

                    if (!$post) {
                        \Timber::render('importer/warning.twig', array('message' => sprintf(__("ID `%s` does not match any existing posts.", TEXTDOMAIN), esc_html($post_id))));
                        $results['errors']++;
                        continue;
                    }

                    $post->post_status = 'publish';

                    foreach ($cols as $col => $val) {

                        if (!isset($headers[$col])) {
                            continue;
                        }

                        $key = $headers[$col];

                        switch ($key) {
                            case 'ID' :
                                continue 2; // skip to next col
                            case 'post_title' :
                            case 'post_content' :
                            case 'post_date' :
                            case 'post_date_gmt' :
                            case 'comment_status' :
                            case 'ping_status' :
                            case 'post_name' :
                            case 'status' :
                            case 'post_parent' :
                            case 'menu_order' :
                            case 'post_type' :
                            case 'post_password' :
                            case 'is_sticky' :
                                $post->$key = sanitize_post_field($key, $val, $post->ID, 'save');
                                break;

                            // TODO categories, tags, attachments!

                            default :

                                foreach($this->header_settings as $pattern => $settings) {

                                    if (preg_match("/$pattern/", $key)) {
                                        $settings = wp_parse_args($settings, array(
                                            'filter' => FILTER_SANITIZE_STRING,
                                            'filter_options' => null,
                                        ));

                                        $val = trim(filter_var($val, $settings['filter'], $settings['filter_options']));
                                        $val = apply_filters('importer_meta_field_value', $val, array($settings));

                                        do_action('importer_meta_field_update', $post, $key, $val);

                                        break;
                                    }
                                }
                        }
                    }

                    if (wp_update_post($post) ) {
                        $results['updated']++;
                    }
                }

                \Timber::render('importer/complete.twig', array(
                    'results' => $results
                ));
            } else {
                \Timber::render('importer/error.twig', array(
                    'error' => __("File had no recognized content!", TEXTDOMAIN),
                ));
            }
        }

        /**
         * update_post_meta
         *
         * Default callback for the importer_meta_field_update action
         *
         * @param $post
         * @param $key
         * @param $val
         */
        public function update_post_meta ($post, $key, $val) {
            update_post_meta($post->ID, "_{$key}", $val);
        }
    }
}

/**
 * @param $pattern
 * @param $input
 * @param int $flags
 * @return array
 */
function preg_grep_keys($pattern, $input, $flags = 0) {
    return array_intersect_key($input, array_flip(preg_grep($pattern, array_keys($input), $flags)));
}