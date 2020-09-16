<?php

namespace Pressgang;

/**
 * Class AcfWcProductAttributes
 *
 * See - https://www.advancedcustomfields.com/resources/custom-location-rules/
 *
 * @package Pressgang
 */
class AcfWcProductAttributes {

    /**
     * AcfWcProductAttributes constructor.
     */
    public function __construct()
    {
        add_filter( 'acf/location/rule_types', array($this, 'acf_location_rules_operators'));
        add_filter( 'acf/location/rule_values/wc_prod_attr', array($this, 'acf_location_rule_values'));
        add_filter( 'acf/location/rule_values/wc_prod_attr', array($this, 'acf_location_rule_match'), 10, 4);
    }

    /**
     * acf_location_rules_operators
     *
     * @param $choices
     * @return mixed
     */
    public function acf_location_rules_operators( $choices ){
        $choices[ __("Other",'acf') ]['wc_prod_attr'] = "WC Product Attribute";
        return $choices;
    }

    /**
     * add_custom_rule_values
     *
     * @param $choices
     * @return mixed
     */
    public function acf_location_rule_values( $choices ){
        foreach ( wc_get_attribute_taxonomies() as $attr ) {
            $pa_name = wc_attribute_taxonomy_name( $attr->attribute_name );
            $choices[$pa_name] = $attr->attribute_label;
        }
        return $choices;
    }

    /**
     * match_custom_rule
     *
     * @param $match
     * @param $rule
     * @param $options
     * @return bool
     */
    public function acf_location_rule_match( $match, $rule, $options = [], $field_group = '' )
    {
        if (isset($options['taxonomy'])) {

            switch($rule['operator']) {
                case '==' :
                    $match = $rule['value'] === $options['taxonomy'];
                    break;

                case '!=' :
                    $match = $rule['value'] !== $options['taxonomy'];
                    break;
            }
        }

        return $match;
    }
}

new AcfWcProductAttributes();