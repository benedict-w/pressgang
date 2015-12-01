<?php
/**
 * Description: Search Page
 *
 */

$templates = array('search.twig', 'archive.twig', 'index.twig');
$context = Timber::get_context();
$context['page_title'] = sprintf(__("Search results for '%s'", THEMENAME), get_search_query());
$context['posts'] = Timber::get_posts();
$context['pagination'] = Timber::get_pagination();
Timber::render($templates, $context);