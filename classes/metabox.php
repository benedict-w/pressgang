<?php

namespace PressGang;

class MetaBox
{
    static $meta_name = '';
    static $post_type = '';
    static $title = '';
    static $fields = array();

    /**
     * @param $meta_name
     * @param $post_type
     * @param $title
     * @param array $fields array(array('id', 'name', 'label', 'type', 'class'), ...)
     */
    public function __construct($meta_name, $post_type, $title, $fields = array()) {
        static::$post_type = $post_type;
        static::$meta_name = $meta_name;
        static::$fields = $fields;
        static::$title = $title;

        // hook to add metabox
        add_action('add_meta_boxes', array('PressGang\MetaBox', 'add_meta_box'));

        // hook to save post meta
        add_action('save_post', array('PressGang\MetaBox', 'save_post_meta'), 10, 2);
    }

    /**
     * add_meta_box
     *
     * Callback function for registering add_meta_box hook
     *
     * See - http://codex.wordpress.org/Function_Reference/add_meta_box
     *
     * @param $post_type
     */
    public static function add_meta_box($post_type) {
        if ($post_type === static::$post_type) {
            add_meta_box(sprintf("metabox_%s", static::$post_type), static::$title, array('PressGang\MetaBox', 'render_meta_box_content'), static::$post_type, 'normal');
        }
    }

    /**
     * render_meta_box_content
     *
     * @param $post
     */
    public static function render_meta_box_content($post)
    {
        // add a nonce - https://codex.wordpress.org/WordPress_Nonces
        wp_nonce_field(static::$meta_name, sprintf("%s_nonce", static::$meta_name));

        foreach (static::$fields as &$field) {

            $field['value'] = get_post_meta($post->ID, $field['name'], true);

            switch ($field['type']) {
                case 'text' :
                    \Timber::render('admin.text.twig', $field);
                    break;

                case 'number' :
                    \Timber::render('admin.number.twig', $field);
                    break;

                // TODO add more types!
            }
        }
    }

    /**
     * save_meta
     *
     * Save the meta box's post metadata.
     *
     * @param $post_id
     * @param $post
     */
    public static function save_post_meta($post_id, $post)
    {
        // check post type
        if (get_post_type_object($post->post_type) !== static::$post_type) {
            // verify nonce
            $nonce = sprintf("%s_nonce", static::$meta_name);
            if (isset($_POST[$nonce]) && wp_verify_nonce($_POST[$nonce], static::$meta_name)) {
                // check user can edit
                if (current_user_can('edit_posts', $post_id)) {

                    // get the existing data
                    $old = static::get_field_values($post);

                    // get the new data
                    $new = static::sanitize_custom_input();

                    // add new values that did not previously exist
                    foreach ($new as $key => $new_value) {
                        if ($new_value && (!isset($old[$key]) || !$old[$key])) {
                            add_post_meta($post_id, $key, $new_value);
                        }
                    }

                    foreach ($old as $key => $old_value) {
                        // update existing value where the data has changed
                        if (isset($new[$key])) {
                            if ($new[$key] && $new[$key] != $old_value) {
                                update_post_meta($post_id, $key, $new[$key]);
                            }
                        } // delete existing value where it is no longer present
                        else {
                            // TODO delete removed fields
                            // delete_post_meta($post_id,  "_{$key}", $old_value);
                        }
                    }
                }
            }
        }

        return $post_id;
    }

    /**
     * get_field_values
     *
     * Gets the existing values of the custom fields from the database
     *
     * @param $post
     * @return array
     */
    protected static function get_field_values($post) {

        $values = array();

        foreach(static::$fields as &$field) {
            $values[$field['name']] = get_post_meta($post->ID, $field['name'], true);
        }

        return $values;
    }

    /**
     * sanitize_custom_input
     *
     * Sanitize the posted input for the fields according to the field type
     *
     * @return array
     */
    protected static function sanitize_custom_input() {

        $values = array();

        foreach(static::$fields as &$field) {
            switch($field['type']) {
                case ('text') :
                    $values[$field['name']] = filter_input(INPUT_POST, $field['name'], FILTER_SANITIZE_STRING);
                    break;
                case ('number') :
                    $values[$field['name']] = filter_input(INPUT_POST, $field['name'], FILTER_SANITIZE_NUMBER_INT);
                    break;

                // TODO add more types!
            }
        }

        return $values;
    }
}