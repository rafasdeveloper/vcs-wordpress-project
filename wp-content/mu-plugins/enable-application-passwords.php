<?php
/**
 * Plugin Name: Enable Application Passwords
 * Description: A simple plugin that enables application passwords.
 */

add_filter( 'wp_is_application_passwords_available', '__return_true' );
