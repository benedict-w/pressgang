<?php

$posts_controller = new \PressGang\PostsController('search.twig');
$posts_controller->context['page_title'] = sprintf(__("Search results for '%s'", THEMENAME), get_search_query());
$posts_controller->render();