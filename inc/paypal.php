<?php

namespace Pressgang;

class Paypal {

    /**
     * __construct
     *
     * Paypal constructor
     *
     */
    public function __construct($post_types = array()) {

        Config::$settings['custom-post-types']['paypal_item'] = array(
            'public' => false,
            'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => false,
            'capability_type' => 'post',
            'has_archive' => false,
            'hierarchical' => false,
            'menu_position' => null,
            'supports' => array('title', 'editor', 'thumbnail'),
        );

        if(function_exists('acf_add_options_page')) {
            acf_add_options_page("Paypal");
            $this->add_acf_paypal_account_options_fields();
            $this->add_acf_paypal_item_fields($post_types);
        }

        add_shortcode('paypal-item', array($this, 'paypal_item_shortcode'));
    }


    /**
     * add_acf_paypal_item_fields
     *
     */
    private function add_acf_paypal_item_fields () {

        if( function_exists('acf_add_local_field_group') ):

            acf_add_local_field_group(array (
                'key' => 'group_581767d53a5e3',
                'title' => 'Paypal Item Fields',
                'fields' => array (
                    array (
                        'key' => 'field_581768097feb4',
                        'label' => 'Item Amount',
                        'name' => 'paypal_item_amount',
                        'type' => 'number',
                        'instructions' => 'Enter a price for the item (Prices use the account currency).',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '10.00',
                        'prepend' => '',
                        'append' => '',
                        'min' => 0,
                        'max' => '',
                        'step' => '0.01',
                        'readonly' => 0,
                        'disabled' => 0,
                    ),
                ),
                'location' => array (
                    array (
                        array (
                            'param' => 'post_type',
                            'operator' => '==',
                            'value' => 'paypal_item',
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

    /**
     * add_acf_options_fields
     *
     */
    private function add_acf_paypal_account_options_fields() {

        if( function_exists('acf_add_local_field_group') ) :

            acf_add_local_field_group(array (
                'key' => 'group_581760338a920',
                'title' => 'Paypal Fields',
                'fields' => array (
                    array (
                        'key' => 'field_58176041ac5e5',
                        'label' => 'Email',
                        'name' => 'paypal_email',
                        'type' => 'email',
                        'instructions' => 'Enter the email address for the Paypal account.',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => 'email',
                        'prepend' => '',
                        'append' => '',
                    ),
                    array (
                        'key' => 'field_5817619a4f6b6',
                        'label' => 'Location',
                        'name' => 'paypal_location',
                        'type' => 'select',
                        'instructions' => 'Select the location for the Paypal store.',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'choices' => array (
                            'GB' => 'UK',
                            'DE' => 'Germany',
                        ),
                        'default_value' => array (
                            0 => 'GB',
                        ),
                        'allow_null' => 0,
                        'multiple' => 0,
                        'ui' => 0,
                        'ajax' => 0,
                        'placeholder' => '',
                        'disabled' => 0,
                        'readonly' => 0,
                    ),
                    array (
                        'key' => 'field_5817625c4f6b7',
                        'label' => 'Currency',
                        'name' => 'paypal_currency',
                        'type' => 'select',
                        'instructions' => 'Select the Currency of the Paypal store.',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'choices' => array (
                            'USD' => 'USD',
                            'AUD' => 'AUD',
                            'BRL' => 'BRL',
                            'GBP' => 'GBP',
                            'CAD' => 'CAD',
                            'CZK' => 'CZK',
                            'DKK' => 'DKK',
                            'EUR' => 'EUR',
                            'HKD' => 'HKD',
                            'HUF' => 'HUF',
                            'ILS' => 'ILS',
                            'JPY' => 'JPY',
                            'MXN' => 'MXN',
                            'TWD' => 'TWD',
                            'NZD' => 'NZD',
                            'NOK' => 'NOK',
                            'PHP' => 'PHP',
                            'PLN' => 'PLN',
                            'RUB' => 'RUB',
                            'SGD' => 'SGD',
                            'SEK' => 'SEK',
                            'CHF' => 'CHF',
                            'THB' => 'THB',
                        ),
                        'default_value' => array (
                        ),
                        'allow_null' => 0,
                        'multiple' => 0,
                        'ui' => 0,
                        'ajax' => 0,
                        'placeholder' => '',
                        'disabled' => 0,
                        'readonly' => 0,
                    ),
                ),
                'location' => array (
                    array (
                        array (
                            'param' => 'options_page',
                            'operator' => '==',
                            'value' => 'acf-options-paypal',
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

    /**
     * paypal_item_shortcode
     *
     */
    public function paypal_item_shortcode($atts) {
        $atts = shortcode_atts( array(
            'id' => null,
        ), $atts);

        if ($atts['id']) {

            $item = \Timber::get_post($atts['id']);

            if ($item->post_type === 'paypal_item') {

                $account = array(
                    'email' => get_field('paypal_email', 'option'),
                    'currency' => get_field('paypal_currency', 'option'),
                    'location' => get_field('paypal_location', 'option'),
                );

                return \Timber::compile('paypal-item.twig', array(
                    'paypal_item' => $item,
                    'paypal_account' => $account,
                ));
            }
        }
    }

}

new Paypal();