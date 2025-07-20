<?php

declare( strict_types = 1);

namespace Automattic\WooCommerce\Admin\Features\Blueprint\Exporters;

<<<<<<< HEAD
=======
use Automattic\WooCommerce\Blueprint\Exporters\ExportsStep;
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
use Automattic\WooCommerce\Blueprint\Exporters\HasAlias;
use Automattic\WooCommerce\Blueprint\Exporters\StepExporter;
use Automattic\WooCommerce\Blueprint\Steps\SetSiteOptions;
use Automattic\WooCommerce\Blueprint\UseWPFunctions;

/**
 * Class ExportWCTaskOptions
 *
<<<<<<< HEAD
 * This class exports WooCommerce task options.
=======
 * This class exports WooCommerce task options and implements the StepExporter and HasAlias interfaces.
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
 *
 * @package Automattic\WooCommerce\Admin\Features\Blueprint\Exporters
 */
class ExportWCTaskOptions implements StepExporter, HasAlias {
	use UseWPFunctions;

	/**
	 * Export WooCommerce task options.
	 *
	 * @return SetSiteOptions
	 */
	public function export() {
		return new SetSiteOptions(
			array(
				'woocommerce_admin_customize_store_completed' => $this->wp_get_option( 'woocommerce_admin_customize_store_completed', 'no' ),
				'woocommerce_task_list_tracked_completed_actions' => $this->wp_get_option( 'woocommerce_task_list_tracked_completed_actions', array() ),
			)
		);
	}

	/**
	 * Get the name of the step.
	 *
	 * @return string
	 */
	public function get_step_name() {
		return 'setOptions';
	}

	/**
	 * Get the alias for this exporter.
	 *
	 * @return string
	 */
	public function get_alias() {
		return 'setWCTaskOptions';
	}

	/**
	 * Return label used in the frontend.
	 *
	 * @return string
	 */
	public function get_label() {
		return __( 'Task Configurations', 'woocommerce' );
	}

	/**
	 * Return description used in the frontend.
	 *
	 * @return string
	 */
	public function get_description() {
<<<<<<< HEAD
		return __( 'Includes the task configurations for WooCommerce.', 'woocommerce' );
	}

	/**
	 * Check if the current user has the required capabilities for this step.
	 *
	 * @return bool True if the user has the required capabilities. False otherwise.
	 */
	public function check_step_capabilities(): bool {
		return current_user_can( 'manage_woocommerce' );
=======
		return __( 'It includes the task configurations for WooCommerce.', 'woocommerce' );
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
	}
}
