<?php
/**
 * Halo8 Full Screen Slider
 *
 * Permette la creazione di temi con slideshow full screen o con immagini di background full scren
 *
 * @package   halo8-full-screen-slider
 * @author    Michele Bertuccioli <michele@bertuccioli.me>
 * @license   GPL-2.0+
 * @link      michele.bertuccioli.me
 * @copyright 4-16-2015 Michele Bertuccioli
 *
 * @wordpress-plugin
 * Plugin Name: Halo8
 * Plugin URI:  michele.bertuccioli.me
 * Description: Permette la creazione di temi con slideshow full screen o con immagini di background full scren
 * Version:     1.0.0
 * Author:      Michele Bertuccioli
 * Author URI:  michele.bertuccioli.me
 * Text Domain: halo8-full-screen-slider-locale
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /lang
 */

// error_reporting(E_ALL);
// ini_set("display_errors", 1);

// If this file is called directly, abort.
if (!defined("WPINC")) {
	die;
}

require_once(plugin_dir_path(__FILE__) . "includes/factory/interface-halo8-factory.php");
require_once(plugin_dir_path(__FILE__) . "includes/factory/halo8-factory.php");

$factory = Halo8Factory::getInstance(plugin_dir_path(__FILE__), plugins_url()."/".dirname(plugin_basename(__FILE__))."/");

$factory->createController('plugin_controller');
