<?php
/**
<<<<<<< HEAD
 * This file is part of the WooCommerce Email Editor package.
 *
 * @package Automattic\WooCommerce\EmailEditor
=======
 * This file is part of the MailPoet Email Editor package.
 *
 * @package MailPoet\EmailEditor
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
 */

declare(strict_types = 1);

// phpcs:disable Generic.Files.InlineHTML.Found
// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
/**
 * Template file to render the current 'wp_template', specifcally for emails.
 *
 * Variables passed to this template:
 *
<<<<<<< HEAD
 * @var string $subject The email subject
 * @var string $pre_header The email pre-header text
 * @var string $template_html The email template HTML content
 * @var string $meta_robots Meta robots tag content
 * @var array{contentSize: string} $layout Layout configuration
=======
 * @var $subject string
 * @var $pre_header string
 * @var $template_html string
 * @var $meta_robots string
 * @var $layout array{contentSize: string}
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<<<<<<< HEAD
	<title><?php echo esc_html( $subject ); ?></title>
=======
	<title><?php echo esc_html( $subject ); // @phpstan-ignore-line ?></title>
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="format-detection" content="telephone=no" />
<<<<<<< HEAD
	<?php echo $meta_robots; ?>
	<!-- Forced Styles -->
</head>
<body>
	<!--[if mso | IE]><table align="center" role="presentation" border="0" cellpadding="0" cellspacing="0" width="<?php echo esc_attr( $layout['contentSize'] ); ?>" style="width:<?php echo esc_attr( $layout['contentSize'] ); ?>"><tr><td><![endif]-->
	<div class="email_layout_wrapper" style="max-width: <?php echo esc_attr( $layout['contentSize'] ); ?>">
=======
	<?php echo $meta_robots; // @phpstan-ignore-line HTML defined by MailPoet--do not escape. ?>
	<!-- Forced Styles -->
</head>
<body>
	<!--[if mso | IE]><table align="center" role="presentation" border="0" cellpadding="0" cellspacing="0" width="<?php echo esc_attr( $layout['contentSize'] ); // @phpstan-ignore-line ?>" style="width:<?php echo esc_attr( $layout['contentSize'] ); // @phpstan-ignore-line ?>"><tr><td><![endif]-->
	<div class="email_layout_wrapper" style="max-width: <?php echo esc_attr( $layout['contentSize'] ); // @phpstan-ignore-line ?>">
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
		<table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation">
			<tbody>
			<tr>
				<td class="email_preheader" height="1">
<<<<<<< HEAD
				<?php echo esc_html( wp_strip_all_tags( $pre_header ) ); ?>
=======
				<?php echo esc_html( wp_strip_all_tags( $pre_header ) ); // @phpstan-ignore-line ?>
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
				</td>
			</tr>
			<tr>
				<td class="email_content_wrapper">
<<<<<<< HEAD
				<?php echo $template_html; ?>
=======
				<?php echo $template_html; // @phpstan-ignore-line ?>
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
				</td>
			</tr>
			</tbody>
		</table>
	</div>
	<!--[if mso | IE]></td></tr></table><![endif]-->
</body>
</html>
