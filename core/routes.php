<?php

namespace PressGang;

class Routes {

    /**
     * __construct
     *
     */
    public function __construct() {

        foreach (Config::get('routes') as $route => $template) {
            \Routes::map($route, function($params) use ($template) {
                \Routes::load($template, $params, 200);
            });
        }

    }
}

new Routes();