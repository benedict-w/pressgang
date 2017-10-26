<?php

namespace Pressgang\Widget;

/**
 * Class Image
 *
 * @package Pressgang
 */
class Image extends \Pressgang\Widget {

    protected $view = 'image-widget.twig';

    /**
     * __construct
     *
     * Image Widget constructor.
     */
    public function __construct() {
        $this->add_acf_fields();
        parent::__construct();
    }

    /**
     * add_acf_fields
     *
     */
    private function add_acf_fields() {

        if( function_exists('acf_add_local_field_group') ):

            acf_add_local_field_group(array (
                'key' => 'group_57ed1718763de',
                'title' => 'Image Widget Fields',
                'fields' => array (
                    array (
                        'key' => 'field_57ed1728683cd',
                        'label' => 'Image',
                        'name' => 'image',
                        'type' => 'image',
                        'instructions' => 'Add an image to display in the widget',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'return_format' => 'array',
                        'preview_size' => 'thumbnail',
                        'library' => 'all',
                        'min_width' => '',
                        'min_height' => '',
                        'min_size' => '',
                        'max_width' => '',
                        'max_height' => '',
                        'max_size' => '',
                        'mime_types' => '',
                    ),
                    array (
                        'key' => 'field_57ed173d683ce',
                        'label' => 'Caption',
                        'name' => 'caption',
                        'type' => 'textarea',
                        'instructions' => 'Add a text caption for the image.',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                        'maxlength' => '',
                        'rows' => '',
                        'new_lines' => 'wpautop',
                        'readonly' => 0,
                        'disabled' => 0,
                    ),
                    array (
                        'key' => 'field_57ed174f683cf',
                        'label' => 'Link',
                        'name' => 'link',
                        'type' => 'url',
                        'instructions' => 'Add a URL for the image to link to.',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                    ),
                    array (
                        'key' => 'field_57ed1f2de1daa',
                        'label' => 'New',
                        'name' => 'new',
                        'type' => 'true_false',
                        'instructions' => 'Open the link in a new window?',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'message' => '',
                        'default_value' => 0,
                    ),
                ),
                'location' => array (
                    array (
                        array (
                            'param' => 'widget',
                            'operator' => '==',
                            'value' => 'image',
                        ),
                    ),
                ),
                'menu_order' => 0,
                'position' => 'normal',
                'style' => 'default',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
                'hide_on_screen' => '',
                'active' => 1,
                'description' => '',
            ));

        endif;
    }

}

register_widget('PressGang\Widget\Image');