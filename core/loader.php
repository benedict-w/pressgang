<?php

namespace PressGang;

require_once 'config.php';

/**
 * Class Loader
 *
 * @package PressGang
 */
class Loader {

    public static $config = array();

    /**
     * init
     *
     * Require the necessary files
     *
     */
    public function __construct() {

        // load core files on settings keys
        foreach (Config::get() as $key => &$file) {
            locate_template("core/{$key}.php", true, true);
        }

        // load inc files
        foreach (Config::get('inc') as $key=>&$file) {
            $inc = preg_match('/.php/', $file) ? "inc/{$file}" : "inc/{$file}.php";
            locate_template($inc, true, true);
        }
    }
}

new Loader();