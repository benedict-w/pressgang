<?php
/**
 * PostImporter
 *
 * Custom Base class for importing posts
 *
 * @package WordPress
 * @subpackage Importer
 */

namespace PressGang;

require_once __DIR__ . '/importer.php';

if ( class_exists( 'WP_Importer' ) ) {
    /**
     * Class PostImporter
     *
     */
    class PostImporter extends \PressGang\Importer
    {

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
        public function process_import()
        {


            $data = $this->get_file_contents();

            $headers = $data['headers'];
            $content = $data['content'];

            $results = array('errors' => 0, 'updated' => 0);

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

                                foreach ($this->header_settings as $pattern => $settings) {

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

                    if (wp_update_post($post)) {
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
    }
}