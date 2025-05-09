<?php
/**
<<<<<<< HEAD
 * This file is part of the WooCommerce Email Editor package
 *
 * @package Automattic\WooCommerce\EmailEditor
 */

/**
=======
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
 * Plugin Name: Email Editor
 * Plugin URI: https://woocommerce.com/
 * Description: An empty email-editor definition file to setup wp-env test env.
 * Version: 0.0.1
 * Author: Automattic
 * Author URI: https://woocommerce.com
<<<<<<< HEAD
 * Requires at least: 6.7
 * Requires PHP: 7.4
 */

$autoload_entry_point = __DIR__ . '/vendor/autoload.php';
if ( file_exists( $autoload_entry_point ) ) {
	require_once $autoload_entry_point;
}
// When the package is distributed as part of WooCommerce core, it will provide autoloading of necessary dependencies.
=======
 * Requires at least: 6.6
 * Requires PHP: 7.4
 */

require_once __DIR__ . '/vendor/autoload.php';
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
