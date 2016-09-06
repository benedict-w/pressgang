<?php

namespace PressGang;

require_once 'config.php';
require_once __DIR__ . '/../classes/helper.php';

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

        $this->auto_loader();

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

    /**
     * Register autoloader for framework classes
     *
     */
    public function auto_loader() {
        spl_autoload_register(function ($class) {

            $folders = ['classes', 'controllers'];

            $class = substr($class, strrpos($class, '\\') + 1); // strip namespace
            $file = Helper::camel_to_hyphenated($class);

            foreach ($folders as &$folder) {
                $path = sprintf("%s/%s/%s.php", get_template_directory(), $folder,  $file);
                if (file_exists($path)) {
                    require_once($path);
                }
            }

        });
    }
}

new Loader();