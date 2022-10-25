<?php

namespace PressGang;

/**
 * Class WooCommerceAcfAttribute
 *
 * Adds Product Attributes to ACF
 *
 * @package PressGang
 */
class WooCommerceAcfAttribute
{

    /**
     * constructor.
     *
     */
    public function __construct()
    {
        add_filter( 'acf/location/rule_types', array($this, 'add_attribute_to_acf'));
        add_filter( 'acf/location/rule_values/wc_prod_attr', array($this, 'add_attribute_rules'));
        add_filter( 'acf/location/rule_match/wc_prod_attr', array($this, 'match_acf_rules'));
    }

    public function add_attribute_to_acf( $choices )
    {
        $choices[__("Other", 'acf')]['wc_prod_attr'] = 'WC Product Attribute';
        return $choices;
    }

    /**
     * add_attribute_rules
     *
     * Add custom rules to ACF for product attributes
     *
     * @return mixed
     */
    public function add_attribute_rules() {
        foreach ( wc_get_attribute_taxonomies() as $attr ) {
            $pa_name = wc_attribute_taxonomy_name( $attr->attribute_name );
            $choices[ $pa_name ] = $attr->attribute_label;
        }
        return $choices;
    }

    /**
     * match_acf_rules
     *
     * Match the correct custom rule
     *
     * @param $match
     * @param $rule
     * @param $options
     * @return bool
     */
    public function match_acf_rules( $match, $rule, $options ){
        if ( isset( $options['taxonomy'] ) ) {
            if ( '==' === $rule['operator'] ) {
                $match = $rule['value'] === $options['taxonomy'];
            } elseif ( '!=' === $rule['operator'] ) {
                $match = $rule['value'] !== $options['taxonomy'];
            }
        }
        return $match;
    }

}

new WooCommerceAcfAttribute();