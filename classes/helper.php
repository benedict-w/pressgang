<?php

namespace Pressgang;

class Helper {

    public static function camel_to_hyphenated($name) {
        return self::camel_to_delimited($name, '-');
    }

    public static function camel_to_underscored($name) {
        return self::camel_to_delimited($name, '_');
    }

    protected static function camel_to_delimited($name, $delimiter = '-') {
        return strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', "$1{$delimiter}", $name));
    }

}