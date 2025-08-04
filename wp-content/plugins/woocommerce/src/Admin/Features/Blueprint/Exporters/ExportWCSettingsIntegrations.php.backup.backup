<?php

declare( strict_types = 1);

namespace Automattic\WooCommerce\Admin\Features\Blueprint\Exporters;

<<<<<<< HEAD
=======
use Automattic\WooCommerce\Admin\Features\Blueprint\SettingOptions;
use Automattic\WooCommerce\Blueprint\Exporters\HasAlias;
use Automattic\WooCommerce\Blueprint\Exporters\StepExporter;
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
use Automattic\WooCommerce\Blueprint\Steps\SetSiteOptions;
use Automattic\WooCommerce\Blueprint\UseWPFunctions;

/**
<<<<<<< HEAD
 * Class ExportWCSettingsIntegrations
 *
 * This class exports WooCommerce settings on the Integrations page.
=======
 * Class ExportWCSettingsProducts
 *
 * This class exports WooCommerce settings and implements the StepExporter and HasAlias interfaces.
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
 *
 * @package Automattic\WooCommerce\Admin\Features\Blueprint\Exporters
 */
class ExportWCSettingsIntegrations extends ExportWCSettings {
	use UseWPFunctions;

	/**
	 * Get the alias for this exporter.
	 *
	 * @return string
	 */
	public function get_alias() {
		return 'setWCSettingsIntegrations';
	}

	/**
	 * Return label used in the frontend.
	 *
	 * @return string
	 */
	public function get_label() {
		return __( 'Integrations', 'woocommerce' );
	}

	/**
<<<<<<< HEAD
	 * Export WooCommerce settings.
	 *
	 * @return SetSiteOptions
	 */
	public function export() {
		if ( ! isset( WC()->integrations ) ) {
			return new SetSiteOptions( array() );
		}

		$integrations = WC()->integrations->get_integrations();

		$settings = array();
		foreach ( $integrations as $integration ) {
			$option_key              = $integration->get_option_key();
			$settings[ $option_key ] = get_option( $option_key, null );
		}

		return new SetSiteOptions( $settings );
	}


	/**
=======
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
	 * Return description used in the frontend.
	 *
	 * @return string
	 */
	public function get_description() {
<<<<<<< HEAD
		return __( 'Includes all settings in WooCommerce | Settings | Integrations.', 'woocommerce' );
=======
		return __( 'It includes all settings in WooCommerce | Settings | Integrations.', 'woocommerce' );
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
	}

	/**
	 * Get the page ID for the settings page.
	 *
	 * @return string
	 */
	protected function get_page_id(): string {
		return 'integration';
	}
}
