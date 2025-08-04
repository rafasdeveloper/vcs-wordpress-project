<?php

declare( strict_types = 1 );

namespace Automattic\WooCommerce\Admin\Features\Blueprint;

<<<<<<< HEAD
=======
use Automattic\WooCommerce\Admin\Features\Blueprint\Exporters\ExportWCCoreProfilerOptions;
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
use Automattic\WooCommerce\Admin\Features\Blueprint\Exporters\ExportWCPaymentGateways;
use Automattic\WooCommerce\Admin\Features\Blueprint\Exporters\ExportWCSettingsAccount;
use Automattic\WooCommerce\Admin\Features\Blueprint\Exporters\ExportWCSettingsAdvanced;
use Automattic\WooCommerce\Admin\Features\Blueprint\Exporters\ExportWCSettingsEmails;
use Automattic\WooCommerce\Admin\Features\Blueprint\Exporters\ExportWCSettingsGeneral;
<<<<<<< HEAD
use Automattic\WooCommerce\Admin\Features\Blueprint\Exporters\ExportWCSettingsTax;
use Automattic\WooCommerce\Admin\Features\Blueprint\Exporters\ExportWCSettingsIntegrations;
use Automattic\WooCommerce\Admin\Features\Blueprint\Exporters\ExportWCSettingsProducts;
use Automattic\WooCommerce\Admin\Features\Blueprint\Exporters\ExportWCSettingsSiteVisibility;
use Automattic\WooCommerce\Admin\Features\Blueprint\Exporters\ExportWCSettingsShipping;
use Automattic\WooCommerce\Admin\PageController;
use Automattic\WooCommerce\Blueprint\Exporters\HasAlias;
use Automattic\WooCommerce\Blueprint\Exporters\StepExporter;
=======
use Automattic\WooCommerce\Admin\Features\Blueprint\Exporters\ExportWCSettingsIntegrations;
use Automattic\WooCommerce\Admin\Features\Blueprint\Exporters\ExportWCSettingsProducts;
use Automattic\WooCommerce\Admin\Features\Blueprint\Exporters\ExportWCSettingsSiteVisibility;
use Automattic\WooCommerce\Admin\Features\Blueprint\Exporters\ExportWCShipping;
use Automattic\WooCommerce\Admin\Features\Blueprint\Exporters\ExportWCTaskOptions;
use Automattic\WooCommerce\Admin\Features\Blueprint\Exporters\ExportWCTaxRates;
use Automattic\WooCommerce\Admin\Features\Blueprint\Importers\ImportSetWCPaymentGateways;
use Automattic\WooCommerce\Admin\Features\Blueprint\Importers\ImportSetWCShipping;
use Automattic\WooCommerce\Admin\Features\Blueprint\Importers\ImportSetWCTaxRates;
use Automattic\WooCommerce\Admin\PageController;
use Automattic\WooCommerce\Blueprint\Exporters\HasAlias;
use Automattic\WooCommerce\Blueprint\Exporters\StepExporter;
use Automattic\WooCommerce\Blueprint\StepProcessor;
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
use Automattic\WooCommerce\Blueprint\UseWPFunctions;

/**
 * Class Init
 *
 * This class initializes the Blueprint feature for WooCommerce.
 */
class Init {
	use UseWPFunctions;

<<<<<<< HEAD
	const INSTALLED_WP_ORG_PLUGINS_TRANSIENT = 'woocommerce_blueprint_installed_wp_org_plugins';
=======
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
	/**
	 * Array of initialized exporters.
	 *
	 * @var StepExporter[]
	 */
	private array $initialized_exporters = array();

	/**
	 * Init constructor.
	 */
	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'init_rest_api' ) );
		add_filter( 'woocommerce_admin_shared_settings', array( $this, 'add_js_vars' ) );

		add_filter(
			'wooblueprint_export_landingpage',
			function () {
				return '/wp-admin/admin.php?page=wc-admin';
			}
		);

		add_filter( 'wooblueprint_exporters', array( $this, 'add_woo_exporters' ) );
<<<<<<< HEAD

		add_action( 'upgrader_process_complete', array( $this, 'clear_installed_wp_org_plugins_transient' ), 10, 2 );
		add_action( 'deleted_plugin', array( $this, 'clear_installed_wp_org_plugins_transient' ), 10, 2 );
=======
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
	}

	/**
	 * Register REST API routes.
	 *
	 * @return void
	 */
	public function init_rest_api() {
		( new RestApi() )->register_routes();
	}

	/**
	 * Return Woo Exporter classnames.
	 *
	 * @return StepExporter[]
	 */
	public function get_woo_exporters() {
		$classnames = array(
			ExportWCSettingsGeneral::class,
			ExportWCSettingsProducts::class,
<<<<<<< HEAD
			ExportWCSettingsTax::class,
			ExportWCSettingsShipping::class,
=======
			ExportWCTaxRates::class,
			ExportWCShipping::class,
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
			ExportWCPaymentGateways::class,
			ExportWCSettingsAccount::class,
			ExportWCSettingsEmails::class,
			ExportWCSettingsIntegrations::class,
			ExportWCSettingsSiteVisibility::class,
			ExportWCSettingsAdvanced::class,
		);

		$exporters = array();
		foreach ( $classnames as $classname ) {
			$exporters[ $classname ]                   = $this->initialized_exporters[ $classname ] ?? new $classname();
			$this->initialized_exporters[ $classname ] = $exporters[ $classname ];
		}

		return array_values( $exporters );
	}

	/**
	 * Add Woo Specific Exporters.
	 *
	 * @param StepExporter[] $exporters Array of step exporters.
	 *
	 * @return StepExporter[]
	 */
	public function add_woo_exporters( array $exporters ) {
		return array_merge(
			$exporters,
			$this->get_woo_exporters()
		);
	}

	/**
<<<<<<< HEAD
	 * Get plugins for export group.
	 *
	 * @return array|array[] $plugins
	 */
	public function get_plugins_for_export_group() {
		$plugins = $this->get_installed_wp_org_plugins();

		// Get active plugins from WordPress options and transform plugins array into export format.
		$active_plugins = $this->wp_get_option( 'active_plugins', array() );
		$plugins        = array_map(
			function ( $key, $plugin ) use ( $active_plugins ) {
				return array(
					'id'      => $key,
					'label'   => $plugin['Name'],
					'checked' => in_array( $key, $active_plugins, true ),
				);
			},
			array_keys( $plugins ),
			$plugins
		);

		usort(
			$plugins,
			function ( $a, $b ) {
				return $b['checked'] <=> $a['checked'];
			}
		);
		return $plugins;
	}

	/**
	 * Clear the installed WordPress.org plugins transient.
	 */
	public function clear_installed_wp_org_plugins_transient() {
		delete_transient( self::INSTALLED_WP_ORG_PLUGINS_TRANSIENT );
	}

	/**
	 * Get themes for export group.
	 *
	 * @return array $themes
	 */
	public function get_themes_for_export_group() {
		$themes       = $this->wp_get_themes();
		$active_theme = $this->wp_get_theme();

		$themes = array_map(
			function ( $theme ) use ( $active_theme ) {
				return array(
					'id'      => $theme->get_stylesheet(),
					'label'   => $theme->get( 'Name' ),
					'checked' => $theme->get_stylesheet() === $active_theme->get_stylesheet(),
				);
			},
			$themes
		);

		usort(
			$themes,
			function ( $a, $b ) {
				return $b['checked'] <=> $a['checked'];
			}
		);

		return array_values( $themes );
	}

	/**
=======
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
	 * Return step groups for JS.
	 *
	 * This is used to populate exportable items on the blueprint settings page.
	 *
	 * @return array
	 */
	public function get_step_groups_for_js() {
<<<<<<< HEAD
		return array(
			array(
				'id'          => 'settings',
				'description' => __( 'Includes all the items featured in WooCommerce | Settings.', 'woocommerce' ),
=======
		$all_plugins    = $this->wp_get_plugins();
		$active_plugins = array_intersect_key( $all_plugins, array_flip( get_option( 'active_plugins', array() ) ) );
		$active_theme   = $this->wp_get_theme();

		return array(
			array(
				'id'          => 'settings',
				'description' => __( 'It includes all the items featured in WooCommerce | Settings.', 'woocommerce' ),
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
				'label'       => __( 'WooCommerce Settings', 'woocommerce' ),
				'icon'        => 'settings',
				'items'       => array_map(
					function ( $exporter ) {
						return array(
							'id'          => $exporter instanceof HasAlias ? $exporter->get_alias() : $exporter->get_step_name(),
							'label'       => $exporter->get_label(),
							'description' => $exporter->get_description(),
<<<<<<< HEAD
							'checked'     => true,
=======
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
						);
					},
					$this->get_woo_exporters()
				),
			),
			array(
				'id'          => 'plugins',
<<<<<<< HEAD
				'description' => __( 'Includes all the installed plugins.', 'woocommerce' ),
				'label'       => __( 'Plugins', 'woocommerce' ),
				'icon'        => 'plugins',
				'items'       => $this->get_plugins_for_export_group(),
			),
			array(
				'id'          => 'themes',
				'description' => __( 'Includes all the installed themes.', 'woocommerce' ),
				'label'       => __( 'Themes', 'woocommerce' ),
				'icon'        => 'layout',
				'items'       => $this->get_themes_for_export_group(),
=======
				'description' => __( 'It includes all the installed plugins and extensions.', 'woocommerce' ),
				'label'       => __( 'Plugins and extensions', 'woocommerce' ),
				'icon'        => 'plugins',
				'items'       => array_map(
					function ( $key, $plugin ) {
						return array(
							'id'    => $key,
							'label' => $plugin['Name'],
						);
					},
					array_keys( $active_plugins ),
					$active_plugins
				),
			),
			array(
				'id'          => 'themes',
				'description' => __( 'It includes all the installed themes.', 'woocommerce' ),
				'label'       => __( 'Themes', 'woocommerce' ),
				'icon'        => 'brush',
				'items'       => array(
					array(
						'id'    => $active_theme->get_stylesheet(),
						'label' => $active_theme->get( 'Name' ),
					),
				),
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
			),
		);
	}

	/**
	 * Add shared JS vars.
	 *
	 * @param array $settings shared settings.
	 *
	 * @return mixed
	 */
	public function add_js_vars( $settings ) {
		if ( ! is_admin() ) {
			return $settings;
		}

<<<<<<< HEAD
		if ( 'woocommerce_page_wc-settings-advanced-blueprint' === PageController::get_instance()->get_current_screen_id() ) {
			// Used on the settings page.
			// wcSettings.admin.blueprint_step_groups.
			$settings['blueprint_step_groups']         = $this->get_step_groups_for_js();
=======
		$screen_id     = PageController::get_instance()->get_current_screen_id();
		$advanced_page = strpos( $screen_id, 'woocommerce_page_wc-settings-advanced' ) !== false;
		if ( 'woocommerce_page_wc-admin' === $screen_id || $advanced_page ) {
			// Add upload nonce to global JS settings. The value can be accessed at wcSettings.admin.blueprint_upload_nonce.
			$settings['blueprint_upload_nonce'] = wp_create_nonce( 'blueprint_upload_nonce' );
		}

		if ( $advanced_page ) {
			// Used on the settings page.
			// wcSettings.admin.blueprint_step_groups.
			$settings['blueprint_step_groups'] = $this->get_step_groups_for_js();
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
			$settings['blueprint_max_step_size_bytes'] = RestApi::MAX_FILE_SIZE;
		}

		return $settings;
	}
<<<<<<< HEAD

	/**
	 * Get all installed WordPress.org plugins.
	 *
	 * @return array
	 */
	private function get_installed_wp_org_plugins() {
		// Try to get cached plugin list.
		$wp_org_plugins = get_transient( self::INSTALLED_WP_ORG_PLUGINS_TRANSIENT );
		if ( is_array( $wp_org_plugins ) ) {
			return $wp_org_plugins;
		}

		// Get all installed plugins.
		$all_plugins  = $this->wp_get_plugins();
		$plugin_slugs = array();

		// Build a map of plugin file => slug.
		foreach ( $all_plugins as $key => $plugin ) {
			$slug = dirname( $key );
			/**
			 * Apply the WP Core "wp_plugin_dependencies_slug" filter to get the correct plugin slug.
			 */
			$slug = apply_filters( 'wp_plugin_dependencies_slug', $slug ); // phpcs:ignore WooCommerce.Commenting.CommentHooks.MissingSinceComment

			$plugin_slugs[]              = $slug;
			$all_plugins[ $key ]['slug'] = $slug;
		}

		$api_response = $this->wp_plugins_api(
			'plugin_information',
			array(
				'fields' => array(
					'short_description' => false,
					'sections'          => false,
					'description'       => false,
					'tested'            => false,
					'requires'          => false,
					'rating'            => false,
					'ratings'           => false,
					'downloaded'        => false,
					'downloadlink'      => false,
					'last_updated'      => false,
					'added'             => false,
					'tags'              => false,
					'compatibility'     => false,
					'homepage'          => false,
					'versions'          => false,
					'donate_link'       => false,
					'reviews'           => false,
					'banners'           => false,
					'icons'             => false,
					'active_installs'   => false,
				),
				'slugs'  => $plugin_slugs,
			)
		);

		// If API fails, return all plugins.
		if ( is_wp_error( $api_response ) ) {
			return $all_plugins;
		}

		// Filter plugins: only keep those with a valid API response (no 'error' for their slug).
		$wp_org_plugins = array_filter(
			$all_plugins,
			function ( $plugin ) use ( $api_response ) {
				$slug = $plugin['slug'];
				return isset( $api_response->{$slug} ) && ! isset( $api_response->{$slug}['error'] );
			}
		);

		set_transient( self::INSTALLED_WP_ORG_PLUGINS_TRANSIENT, $wp_org_plugins );
		return $wp_org_plugins;
	}
=======
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
}
