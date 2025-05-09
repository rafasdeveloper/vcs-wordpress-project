<?php
/**
<<<<<<< HEAD
 * This file is part of the WooCommerce Email Editor package.
 * Template canvas file to render the emails custom post type.
 *
 * @package Automattic\WooCommerce\EmailEditor
 */

// get the rendered post HTML content.
$template_html = apply_filters( 'woocommerce_email_editor_preview_post_template_html', get_post() );
=======
 * This file is part of the MailPoet Email Editor package.
 * Template canvas file to render the emails custom post type.
 *
 * @package MailPoet\EmailEditor
 */

// get the rendered post HTML content.
$template_html = apply_filters( 'mailpoet_email_editor_preview_post_template_html', get_post() );
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)

// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo $template_html;
