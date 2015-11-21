<?php

$page_title = post_type_archive_title('', false);

if (is_category()) {
    $page_title = single_cat_title('', false);
}

// TODO more archive titles


$context = Timber::get_context();
$context['page_title'] = $page_title;
$context['posts'] = Timber::get_posts();
$context['pagination'] = Timber::get_pagination();
Timber::render('archive.twig', $context);