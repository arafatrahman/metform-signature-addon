<?php
/**
 * Plugin Name:       MetForm Digital Signature Addon
 * Plugin URI:        https://rrrplus.co.uk/metform-signature-addon/
 * Description:       Add digital signature functionality to MetForm for collecting e-signatures in your forms.
 * Version:           1.0.0
 * Author:            RRR Plus
 * Author URI:        https://rrrplus.co.uk/
 * License:           GPLv2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       metformsa
 * Domain Path:       /languages
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