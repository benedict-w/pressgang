<?php

namespace PressGang;

require_once __DIR__ . '/config.php';
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

        // load inc, shortcodes, widgets files
        foreach (array('inc', 'shortcodes', 'widgets') as &$folder) {
            if ($config = Config::get($folder)) {
                foreach ($config as &$file) {
                    $inc = preg_match('/.php/', $file) ? "{$folder}/{$file}" : "{$folder}/{$file}.php";
                    locate_template($inc, true, true);
                }
            }
        }
    }

    /**
     * Register autoloader for framework classes
     *
     */
    public function auto_loader() {

        spl_autoload_register(function ($class) {

            $folders = ['classes', 'controllers'];

            // strip namespace
            if (preg_match('@\\\\([\w]+)$@', $class, $matches)) {
                $class = $matches[1];
            }

            $file = Helper::camel_to_hyphenated($class);

            foreach ($folders as &$folder) {
                // try pressgang folders
                if (!$this->require_theme_file(get_template_directory(), $folder, $file)) {
                    // and search child theme
                    $this->require_theme_file(get_stylesheet_directory(), $folder, $file);
                }
            }

        });
    }

    /**
     * require_theme_file
     *
     * @param $theme_dir
     * @param $folder
     * @param $file
     * @return bool|mixed
     */
    private function require_theme_file($theme_dir, $folder, $file) {

        $path = sprintf("%s/%s/%s.php", $theme_dir, $folder,  $file);
        if (file_exists($path)) {
            return require_once($path);
        }

        return false;
    }
}

new Loader();