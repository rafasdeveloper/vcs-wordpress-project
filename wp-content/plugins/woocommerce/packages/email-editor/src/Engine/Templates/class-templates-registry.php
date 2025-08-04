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

<<<<<<< HEAD
namespace Automattic\WooCommerce\EmailEditor\Engine\Templates;
=======
namespace MailPoet\EmailEditor\Engine\Templates;
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)

/**
 * Registry for email templates.
 */
class Templates_Registry {
	/**
	 * List of registered templates.
	 *
	 * @var Template[]
	 */
	private $templates = array();

	/**
	 * Initialize the template registry.
	 * This method should be called only once.
	 *
	 * @return void
	 */
	public function initialize(): void {
<<<<<<< HEAD
		apply_filters( 'woocommerce_email_editor_register_templates', $this );
=======
		apply_filters( 'mailpoet_email_editor_register_templates', $this );
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
	}

	/**
	 * Register a template instance in the registry.
	 *
	 * @param Template $template The template to register.
	 * @return void
	 */
	public function register( Template $template ): void {
<<<<<<< HEAD
		if ( ! \WP_Block_Templates_Registry::get_instance()->is_registered( $template->get_name() ) ) {
			// skip registration if the template was already registered.
			$result                                   = register_block_template(
=======
		// The function was added in WordPress 6.7. We can remove this check after we drop support for WordPress 6.6.
		if ( ! function_exists( 'register_block_template' ) ) {
			return;
		}

		if ( ! \WP_Block_Templates_Registry::get_instance()->is_registered( $template->get_name() ) ) {
			// skip registration if the template was already registered.
			register_block_template(
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
				$template->get_name(),
				array(
					'title'       => $template->get_title(),
					'description' => $template->get_description(),
					'content'     => $template->get_content(),
					'post_types'  => $template->get_post_types(),
				)
			);
			$this->templates[ $template->get_name() ] = $template;
		}
	}

	/**
	 * Retrieve a template by its name.
<<<<<<< HEAD
	 * Example: get_by_name( 'woocommerce//email-general' ) will return the instance of Template with identical name.
=======
	 * Example: get_by_name( 'mailpoet//email-general' ) will return the instance of Template with identical name.
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
	 *
	 * @param string $name The name of the template.
	 * @return Template|null The template object or null if not found.
	 */
	public function get_by_name( string $name ): ?Template {
		return $this->templates[ $name ] ?? null;
	}

	/**
	 * Retrieve a template by its slug.
	 * Example: get_by_slug( 'email-general' ) will return the instance of Template with identical slug.
	 *
	 * @param string $slug The slug of the template.
	 * @return Template|null The template object or null if not found.
	 */
	public function get_by_slug( string $slug ): ?Template {
		foreach ( $this->templates as $template ) {
			if ( $template->get_slug() === $slug ) {
				return $template;
			}
		}
		return null;
	}

	/**
	 * Retrieve all registered templates.
	 *
	 * @return array List of all registered templates.
	 */
	public function get_all() {
		return $this->templates;
	}
}
