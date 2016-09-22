<?php

namespace Pressgang;

/**
 * Class Authors
 *
 * @package Pressgang
 */
class Authors extends \Pressgang\Widget {

    /**
     * widget
     *
     * @param array $args
     * @param array $instance
     */
    public function widget($args, $instance) {

        $authors = get_users(array(
            'orderby' => 'post_count',
            'order' => 'DESC',
            'who' => 'authors',
        ));

        foreach ($authors as $i => &$author) {
            if (count_user_posts($author->ID)) {
                $author = new \TimberUser($author);
            }
            else {
                unset($authors[$i]);
            }
        }

        $instance['authors'] = $authors;

        parent::widget($args, $instance);
    }
}

register_widget('PressGang\Authors');