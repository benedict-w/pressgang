<?php

namespace Pressgang\Widget;

/**
 * Class ContactDetails
 *
 * @package Pressgang
 */
class ContactDetails extends \Pressgang\Widget {

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
    private function add_acf_fields()
    {

        if( function_exists('acf_add_local_field_group') ):

            acf_add_local_field_group(array (
                'key' => 'group_58790415a0a93',
                'title' => 'Contact Details Widget Fields',
                'fields' => array (
                    array (
                        'default_value' => '',
                        'maxlength' => '',
                        'placeholder' => 'Name',
                        'prepend' => '',
                        'append' => '',
                        'key' => 'field_58790425a1e07',
                        'label' => 'Name',
                        'name' => 'name',
                        'type' => 'text',
                        'instructions' => 'Enter the name of the company, person, or organisation.',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                    ),
                    array (
                        'sub_fields' => array (
                            array (
                                'default_value' => '',
                                'maxlength' => '',
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                                'key' => 'field_58790472a1e09',
                                'label' => 'Address Line',
                                'name' => 'line',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 1,
                                'conditional_logic' => 0,
                                'wrapper' => array (
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                            ),
                        ),
                        'min' => 0,
                        'max' => 0,
                        'layout' => 'block',
                        'button_label' => 'Add Line',
                        'collapsed' => 'field_58790472a1e09',
                        'key' => 'field_58790448a1e08',
                        'label' => 'Address',
                        'name' => 'address',
                        'type' => 'repeater',
                        'instructions' => 'Add the lines of the address.',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                    ),
                    array (
                        'sub_fields' => array (
                            array (
                                'default_value' => '',
                                'maxlength' => '',
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                                'key' => 'field_58790504a1e0d',
                                'label' => 'Number',
                                'name' => 'number',
                                'type' => 'text',
                                'instructions' => 'Enter the Contact Number.',
                                'required' => 1,
                                'conditional_logic' => 0,
                                'wrapper' => array (
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                            ),
                            array (
                                'default_value' => "Phone",
                                'maxlength' => '',
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                                'key' => 'field_523115d7c4906',
                                'label' => 'Label',
                                'name' => 'label',
                                'type' => 'text',
                                'instructions' => 'Label used to identify the number',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array (
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                            ),
                            array (
                                'default_value' => '',
                                'maxlength' => '',
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                                'key' => 'field_58790517a1e0e',
                                'label' => 'Call To',
                                'name' => 'callto',
                                'type' => 'text',
                                'instructions' => 'This should be a number for the \'callto\' link attribute (use international dialling code, no brackets).',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array (
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                            ),
                            array (
                                'multiple' => 0,
                                'allow_null' => 0,
                                'choices' => array (
                                    'Phone' => 'Phone',
                                    'Mobile' => 'Mobile',
                                    'Home' => 'Home',
                                    'Fax' => 'Fax',
                                ),
                                'default_value' => array (
                                ),
                                'ui' => 0,
                                'ajax' => 0,
                                'placeholder' => '',
                                'return_format' => 'value',
                                'key' => 'field_58790551a1e0f',
                                'label' => 'Type',
                                'name' => 'type',
                                'type' => 'select',
                                'instructions' => 'Use if you want to specify the type of number.',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array (
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                            ),
                        ),
                        'min' => 0,
                        'max' => 0,
                        'layout' => 'block',
                        'button_label' => 'Add Contact Number',
                        'collapsed' => 'field_58790504a1e0d',
                        'key' => 'field_5879048ba1e0a',
                        'label' => 'Contact Numbers',
                        'name' => 'contact_numbers',
                        'type' => 'repeater',
                        'instructions' => 'Add contact numbers.',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                    ),
                    array (
                        'sub_fields' => array (
                            array (
                                'default_value' => '',
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                                'key' => 'field_587905b4a1e10',
                                'label' => 'Email',
                                'name' => 'email',
                                'type' => 'email',
                                'instructions' => '',
                                'required' => 1,
                                'conditional_logic' => 0,
                                'wrapper' => array (
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                            ),
                            array (
                                'default_value' => '',
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                                'key' => 'field_5v3cwgr81lruy',
                                'label' => 'Name',
                                'name' => 'name',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array (
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                            ),
                        ),
                        'min' => 0,
                        'max' => 0,
                        'layout' => 'row',
                        'button_label' => 'Add Email',
                        'collapsed' => '',
                        'key' => 'field_587904aba1e0c',
                        'label' => 'Email Addresses',
                        'name' => 'email_addresses',
                        'type' => 'repeater',
                        'instructions' => 'Enter any email addresses',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                    ),
                ),
                'location' => array (
                    array (
                        array (
                            'param' => 'widget',
                            'operator' => '==',
                            'value' => 'contact-details',
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

register_widget('PressGang\Widget\ContactDetails');