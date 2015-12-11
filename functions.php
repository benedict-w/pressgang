<?php

/**
 * General Theme Settings
 *
 */
// define('THEMENAME', 'PressGang');

/**
 * Localization
 *
 */
load_theme_textdomain(THEMENAME, 'lang');

/**
 * Error if Timber not installed
 */
if (!class_exists('\TimberTheme')) {
    throw new Exception("Pressgang requires that the Timber plugin is installed and activated, see: https://wordpress.org/plugins/timber-library/");
}

/**
 * Go!
 *
 */
require_once('core/site.php');