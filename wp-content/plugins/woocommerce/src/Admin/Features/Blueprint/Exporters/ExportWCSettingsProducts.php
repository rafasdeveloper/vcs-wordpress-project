<?php

declare( strict_types = 1);

namespace Automattic\WooCommerce\Admin\Features\Blueprint\Exporters;

<<<<<<< HEAD
=======
use Automattic\WooCommerce\Admin\Features\Blueprint\SettingOptions;
use Automattic\WooCommerce\Blueprint\Exporters\HasAlias;
use Automattic\WooCommerce\Blueprint\Exporters\StepExporter;
use Automattic\WooCommerce\Blueprint\Steps\SetSiteOptions;
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
use Automattic\WooCommerce\Blueprint\UseWPFunctions;

/**
 * Class ExportWCSettingsProducts
 *
<<<<<<< HEAD
 * This class exports WooCommerce settings on the Products page.
=======
 * This class exports WooCommerce settings and implements the StepExporter and HasAlias interfaces.
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
 *
 * @package Automattic\WooCommerce\Admin\Features\Blueprint\Exporters
 */
class ExportWCSettingsProducts extends ExportWCSettings {
	use UseWPFunctions;

	/**
	 * Get the alias for this exporter.
	 *
	 * @return string
	 */
	public function get_alias() {
		return 'setWCSettingsProducts';
	}

	/**
	 * Return label used in the frontend.
	 *
	 * @return string
	 */
	public function get_label() {
		return __( 'Products', 'woocommerce' );
	}

	/**
	 * Return description used in the frontend.
	 *
	 * @return string
	 */
	public function get_description() {
<<<<<<< HEAD
		return __( 'Includes all settings in WooCommerce | Settings | Products.', 'woocommerce' );
=======
		return __( 'It includes all settings in WooCommerce | Settings | Products.', 'woocommerce' );
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
	}

	/**
	 * Get the page ID for the settings page.
	 *
	 * @return string
	 */
	protected function get_page_id(): string {
		return 'products';
	}
}
