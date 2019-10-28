<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package   halo8-full-screen-slider
 * @author    Michele Bertuccioli <michele@bertuccioli.me>
 * @license   GPL-2.0+
 * @link      michele.bertuccioli.me
 * @copyright 4-16-2015 Michele Bertuccioli
 */

// If uninstall, not called from WordPress, then exit
if (!defined("WP_UNINSTALL_PLUGIN")) {
	exit;
}

// TODO: Define uninstall functionality here