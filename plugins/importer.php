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
    class Importer extends \WP_Importer {

        protected $id;
        protected $file;

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
            $result = $this->process_import();
            if (is_wp_error($result)) {
                return $result;
            }
        }

        /**
         * process_import
         *
         * Override this in child classes to do the import work
         *
         * @return array
         */
        protected function process_import() {

            $data = $this->get_file_contents();

            // TODO override

            return array();
        }

        /**
         * get_file_contents
         *
         * Reads the .csv file and returns an array of 'headers' => [], 'contents' => []
         * @return array
         */
        protected function get_file_contents() {
            $headers = array();
            $content = array();

            // read file contents first
            if (($handle = fopen($this->file, 'r')) !== false) {
                $row = 0;
                while (($data = fgetcsv($handle, 1000, self::DELIMITER)) !== false) {
                    // read headers
                    if ($row === 0) {
                        $headers = array_map('trim', $data);
                    } else {
                        $content[] = $data;
                    }
                    $row++;
                }
            }

            fclose($handle);

            $this->validate_headers($headers);

            return array (
                'headers' => $headers,
                'rows' => $content,
            );
        }

        /**
         * validate_headers
         *
         */
        protected function validate_headers($headers) {
            // Check for invalid headers
            $matches = preg_grep('/^(' . implode('|', array_keys($this->header_settings)) . ')$/', $headers, PREG_GREP_INVERT);

            if ($matches) {
                \Timber::render('importer/invalid-headers.twig', array('headers' => $matches));
            }
        }
    }
}