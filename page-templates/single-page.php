<?php
/**
 * Template Name: Single Page Layout
 *
 * Add a single page layout by displaying children of a parent with this template on a single page
 *
 * @package WordPress
 * @subpackage Pressgang
 */

$context = \Timber::get_context();
$post = \Timber::get_post();
$args = array(
    'post_parent' => $post->ID,
    'post_type'   => 'page',
    'numberposts' => -1,
    'post_status' => 'publish',
    'order' => 'ASC',
);

$pages = array($post);

foreach(get_children($args) as $child) {
    $pages[] = new TimberPost($child);
}
$context['pages'] = $pages;
\Timber::render('single-page.twig', $context);