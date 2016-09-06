<?php

namespace Pressgang;

class Helper {

    public static function camel_to_hyphenated($name) {
        return strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1-', $name));
    }
}