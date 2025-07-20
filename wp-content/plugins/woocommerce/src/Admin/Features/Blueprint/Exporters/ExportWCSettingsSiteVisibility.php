<?php

declare( strict_types = 1);

namespace Automattic\WooCommerce\Admin\Features\Blueprint\Exporters;

<<<<<<< HEAD
=======
use Automattic\WooCommerce\Admin\Features\Blueprint\SettingOptions;
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
use Automattic\WooCommerce\Blueprint\Exporters\HasAlias;
use Automattic\WooCommerce\Blueprint\Exporters\StepExporter;
use Automattic\WooCommerce\Blueprint\Steps\SetSiteOptions;
use Automattic\WooCommerce\Blueprint\UseWPFunctions;

/**
<<<<<<< HEAD
 * Class ExportWCSettingsSiteVisibility
 *
 * This class exports WooCommerce settings on the Site Visibility page.
=======
 * Class ExportWCSettingsProducts
 *
 * This class exports WooCommerce settings and implements the StepExporter and HasAlias interfaces.
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
 *
 * @package Automattic\WooCommerce\Admin\Features\Blueprint\Exporters
 */
class ExportWCSettingsSiteVisibility implements StepExporter, HasAlias {
	use UseWPFunctions;

	/**
	 * Export Site Visibility settings.
	 *
	 * @return SetSiteOptions
	 */
	public function export() {
		return new SetSiteOptions(
			array(
				'woocommerce_coming_soon'      => $this->wp_get_option( 'woocommerce_coming_soon' ),
				'woocommerce_store_pages_only' => $this->wp_get_option( 'woocommerce_store_pages_only' ),
			)
		);
	}

	/**
	 * Get the alias for this exporter.
	 *
	 * @return string
	 */
	public function get_alias() {
		return 'setWCSettingsSiteVisibility';
	}

	/**
	 * Return label used in the frontend.
	 *
	 * @return string
	 */
	public function get_label() {
		return __( 'Site Visibility', 'woocommerce' );
	}

	/**
	 * Return description used in the frontend.
	 *
	 * @return string
	 */
	public function get_description() {
<<<<<<< HEAD
		return __( 'Includes all settings in WooCommerce | Settings | Visibility.', 'woocommerce' );
=======
		return __( 'It includes all settings in WooCommerce | Settings | Visibility.', 'woocommerce' );
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
	}

	/**
	 * Get the name of the step.
	 *
	 * @return string
	 */
	public function get_step_name() {
		return 'setSiteOptions';
	}
<<<<<<< HEAD

	/**
	 * Check if the current user has the required capabilities for this step.
	 *
	 * @return bool True if the user has the required capabilities. False otherwise.
	 */
	public function check_step_capabilities(): bool {
		return current_user_can( 'manage_woocommerce' );
	}
=======
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
}
