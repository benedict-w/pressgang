<?php
$context = Timber::get_context();
$context['title'] = __("Not Found", THEMENAME);
$context['content'] = __("Sorry, we couldn't find what you are looking for!", THEMENAME);
Timber::render('404.twig', $context);