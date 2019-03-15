<?php

Namespace Pressgang;

/**
 * Class CustomMenuItems
 *
 * Adds items to a given WordPress menu, taken from core/settings.php
 *
 *
 * @package Pressgang
 */
class CustomMenuItems
{

    /**
     * __construct
     *
     * settings:
     *
     * 'custom-menu-items' => array (
     *   'menu-slug' => array (
     *     'parent_object_id' => 0,
     *       'subitems' => array (
     *         array('text' => 'login', 'url' => '', 'classes'),
     *     ),
     *   )
     * )
     *
     */
    public function __construct()
    {
        if ($items = Config::get('custom-menu-items')) {

            foreach ($items as $slug => &$args) {
                self::add_subitems_to_menu($slug, isset($args['parent_object_id']) ? $args['parent_object_id'] : 0, $args['subitems']);
            }
        }

    }

    /**
     * add_subitems_to_menu
     *
     * Adds custom items to a navigation menu
     *
     * Based on: http://teleogistic.net/2013/02/dynamically-add-items-to-a-wp_nav_menu-list/
     *
     * - See: https://github.com/timber/timber/issues/200
     *
     * @param string    $menu_name          The name or slug of the navigation menu
     * @param int       $parent_object_id   The id of the post/page, which must be present
     *                                      in the menu, and to which we want to add subitems
     * @param array     $subitems           The sub-items to be added to the menu, as an
     *                                      array( array( 'text' => 'foo', 'url' => '/bar') )
     */
    public static function add_subitems_to_menu( $menu_name, $parent_object_id = 0, $subitems = array()) {

        // don't add anything in admin area
        if ( is_admin() ) {
            return;
        }

        // filter used by Timber to get WP menu items
        add_filter( 'wp_get_nav_menu_items', function( $items, $menu )
        use( $menu_name, $parent_object_id, $subitems ) {

            // return items if no menu found
            if ( $menu->name != $menu_name && $menu->slug != $menu_name ) {
                return $items;
            }

            // append sub menu if parent id present
            $parent_menu_item_id = 0;
            foreach ( $items as $item ) {
                if ( $parent_object_id == $item->object_id ) {
                    $parent_menu_item_id = $item->ID;
                    break;
                }
            }

            $menu_order = count( $items ) + 1;

            foreach ( $subitems as $subitem ) {
                // used by WP to create a menu item
                $items[] = (object) array(
                    'ID'                => $menu_order + 1000000000, // ID that WP won't use
                    'title'             => $subitem['text'],
                    'url'               => $subitem['url'],
                    'menu_item_parent'  => $parent_menu_item_id,
                    'menu_order'        => $menu_order,
                    'type'              => '',
                    'object'            => '',
                    'object_id'         => '',
                    'db_id'             => '',
                    'classes'           => isset($subitem['classes']) ? $subitem['classes'] : '',
                    'target'            => isset($subitem['target']) ? $subitem['target'] : '_blank',
                    'attr_title'        => $subitem['text'],
                    'description'       => isset($subitem['description']) ? $subitem['description'] : '',
                    'xfn'               => '',
                    'status'            => '',
                );
                $menu_order++;
            }
            return $items;
        }, 10, 2);
    }
}

new CustomMenuItems();