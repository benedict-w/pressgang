<?php

$page_title = post_type_archive_title('', false);
// TODO archive title


$context = Timber::get_context();
$context['page_title'] = $page_title;
$context['posts'] = Timber::get_posts();
$context['pagination'] = Timber::get_pagination();
Timber::render('archive.twig', $context);