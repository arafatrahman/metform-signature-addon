<?php
/**
 * Plugin Name: MetForm Signature field Addon
 * Description: The MetForm Signature Addon is a powerful extension for the MetForm plugin that lets you effortlessly add signature fields to your forms.
 * Version: 1.0.0
 * Author: Arafat Rahman
 * Author URI: https://rrrplus.co.uk/
 */

if (!defined('ABSPATH')) exit; // Exit if accessed directly

// Define the plugin path
define('MFSA_PATH', plugin_dir_path(__FILE__));

// Autoload classes
spl_autoload_register(function ($class_name) {
    if (strpos($class_name, 'MFSA\\') === 0) {
        $file = MFSA_PATH . 'includes/' . str_replace(['MFSA\\', '\\'], ['', '/'], $class_name) . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }
});

new \MFSA\Controller\MFSA_Addon_Controller();