<?php
/**
 * Template Name: Single
 * Description: Single post template
 *
 * @package WordPress
 * @subpackage PressGang
 */

$context = \Timber::get_context();
$context['post'] = new \TimberPost();
$context['categories'] = get_the_category(', ');
\Timber::render('single.twig', $context);