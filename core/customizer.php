<?php

namespace Pressgang;

/**
 * Class Customize
 *
 * NB name conflicts with legacy inc/customizer.php class :/
 *
 * https://codex.wordpress.org/Theme_Customization_API
 * https://codex.wordpress.org/Plugin_API/Action_Reference/customize_register
 * https://codex.wordpress.org/Class_Reference/WP_Customize_Manager/add_setting
 * https://codex.wordpress.org/Class_Reference/WP_Customize_Manager/add_control
 *
 * @package Pressgang
 */
class Customize
{
    protected $customizer;

    /**
     * __construct
     *
     */
    public function __construct()
    {
        $this->customizer = Config::get('customizer');
        add_action('customize_register', array($this, 'customizer'), 100);
    }

    /**
     * Add to customizer
     *
     * See
     *  - https://codex.wordpress.org/Class_Reference/WP_Customize_Manager/add_setting
     *  - https://codex.wordpress.org/Class_Reference/WP_Customize_Manager/add_control
     *
     * format is
     *
     * 'customizer' => {
     *    'section' => {
     *        'title' => "Section Title",
     *        'settings' =>
     *          'setting' => {
     *            // setting fields
     *            'default' => 'Default', // default field value
     *            'sanitize_callback' => 'sanitize_text_field' // sanitization callback
     *            // control fields
     *            'class' => '\WP_Customize_Image_Control'
     *            'label' => "",
     *            'description' => "",
     *            'type' => 'text'
     *          }
     *       }
     *    }
     * }
     *
     * @param $wp_customize
     */
    public function customizer($wp_customize) {

        foreach ($this->customizer as $section => &$section_options) {

            // add a new section if it does not exist
            if (!$wp_customize->get_section($section)) {
                $wp_customize->add_section($section, array(
                    'title' => isset($section_options['title']) ? $section_options['title'] : ucwords(str_replace(array('-', '_'), ' ', $section)),
                ));
            }

            foreach ($section_options['settings'] as $setting => &$setting_options) {

                // if array not multidimensional use values for setting key for convention over configuration (i.e. options are all default)
                $setting = is_numeric($setting) ? $setting_options : $setting;
                $setting_options = is_array($setting_options) ? $setting_options : array();

                if (!$wp_customize->get_setting($setting)) {

                    $sanitize_callback = 'sanitize_text_field'; //default

                    if (isset($setting_options['sanitize_callback'])) {
                        $sanitize_callback = $setting_options['sanitize_callback'];
                    } elseif (isset($setting_options['class'])) {
                        switch ($setting_options['class']) {
                            case 'WP_Customize_Image_Control':
                            case 'WP_Customize_Background_Image_Control':
                            case 'WP_Customize_Header_Image_Control':
                            case 'WP_Customize_Upload_Control':
                                $sanitize_callback = 'esc_url_raw';
                                break;
                        }
                    }

                    $wp_customize->add_setting(
                        $setting,
                        array(
                            'default' => isset($setting_options['default']) ? $setting_options['default'] : null,
                            'sanitize_callback' => $sanitize_callback,
                        )
                    );

                    $class = isset($setting_options['class']) ? $setting_options['class'] : 'WP_Customize_Control';
                    $class = "\\{$class}";

                    $wp_customize->add_control(new $class($wp_customize, $setting, array(
                        'label' => isset($setting_options['label']) ? $setting_options['label'] : ucwords(str_replace(array('-', '_'), ' ', $setting)),
                        'description' => isset($setting_options['description']) ? $setting_options['description'] : '',
                        'section' => $section,
                        'priority' => isset($setting_options['priority']) ? $setting_options['priority'] : 10,
                        'type' =>  isset($setting_options['type']) ? $setting_options['type'] : null,
                    )));
                }
            }
        }




    }
}

new Customize();