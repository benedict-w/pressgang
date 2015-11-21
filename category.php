<?php
$context = Timber::get_context();
$context['page_title'] = single_cat_title('', false);
$context['posts'] = Timber::get_posts();
$context['pagination'] = Timber::get_pagination();
Timber::render('category.twig', $context);