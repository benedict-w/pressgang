<?php

namespace PressGang;

class AutoTaxonomyMenu {

    /**
     * __construct
     *
     */
    public function __construct () {
        $this->add_acf_fields();
        add_filter('acf/load_field/name=taxonomy_sub_menu', array($this, 'taxonomy_select'));
        add_filter('wp_get_nav_menu_items', array($this, 'add_sub_menu_items'), 10, 3);
    }

    /**
     * taxonomy_select
     *
     */
    public function taxonomy_select($field) {

        $field['choices'] = array();

        $taxonomies = get_taxonomies();
        foreach ( $taxonomies as &$taxonomy ) {
            $taxonomy =  get_taxonomy($taxonomy);
            $field['choices'][$taxonomy->name] = $taxonomy->label;
        }

        return $field;
    }

    /**
     * add_sub_menu_items
     *
     * @return array
     */
    public function add_sub_menu_items($items, $menu, $args) {

        if (!is_admin()) {

            foreach ($items as &$item) {

                if ($taxonomy = get_field('taxonomy_sub_menu', $item)) {

                    $terms = get_terms(array(
                        'taxonomy' => $taxonomy,
                        'hide_empty' => true,
                        'orderby' => 'name',
                        'order' => 'ASC',
                    ));

                    foreach ($terms as $i => &$term) {
                        $items[] = $this->get_nav_menu_item($term, $item->ID, $i * 10);
                    }
                }

            }
        }

        return $items;
    }

    /**
     * get_nav_menu_item
     *
     * @param $term
     * @param int $parent_id
     * @param int $order
     * @return \stdClass
     */
    protected function get_nav_menu_item($term, $parent_id = 0, $order = 0){
        $item = new \stdClass();
        $item->ID = $term->term_id;
        $item->db_id = 1000000 + $order + $parent_id;
        $item->title = $term->name;
        $item->url = get_term_link($term);
        $item->menu_order = $order;
        $item->menu_item_parent = $parent_id;
        $item->type = '';
        $item->type_label = '';
        $item->object = '';
        $item->object_id = '';
        $item->classes = array();
        $item->target = '';
        $item->attr_title = '';
        $item->description = '';
        $item->xfn = '';
        $item->status = '';
        return $item;
    }

    /**
     * add_acf_fields
     *
     */
    private function add_acf_fields() {
        if( function_exists('acf_add_local_field_group') ):

            acf_add_local_field_group(array(
                'key' => 'group_5a4647ba28148',
                'title' => 'Auto Populate Tax Menu',
                'fields' => array(
                    array(
                        'key' => 'field_5a4647c64131b',
                        'label' => 'Taxonomy Sub Menu',
                        'name' => 'taxonomy_sub_menu',
                        'type' => 'select',
                        'instructions' => 'Select if this item should auto populate a submenu using the given taxonomy.',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'choices' => array(
                            'post_tag' => 'Tag',
                            'category' => 'Category',
                        ),
                        'default_value' => array(
                        ),
                        'allow_null' => 1,
                        'multiple' => 0,
                        'ui' => 0,
                        'ajax' => 0,
                        'return_format' => 'value',
                        'placeholder' => '',
                    ),
                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'nav_menu_item',
                            'operator' => '==',
                            'value' => 'all',
                        ),
                    ),
                ),
                'menu_order' => 0,
                'position' => 'normal',
                'style' => 'seamless',
                'label_placement' => 'left',
                'instruction_placement' => 'label',
                'hide_on_screen' => '',
                'active' => 1,
                'description' => 'Used by Pressgang include "auto-taxonomy-menu.php"',
            ));

        endif;
    }
}

new AutoTaxonomyMenu();