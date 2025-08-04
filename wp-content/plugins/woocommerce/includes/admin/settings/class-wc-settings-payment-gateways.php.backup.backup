<<<<<<< HEAD
<?php
=======
<?php // @codingStandardsIgnoreLine.
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
/**
 * WooCommerce Checkout Settings
 *
 * @package WooCommerce\Admin
 */

<<<<<<< HEAD
declare( strict_types = 1 );

use Automattic\WooCommerce\Internal\Admin\Loader;
=======
use Automattic\WooCommerce\Admin\Features\OnboardingTasks\Tasks\WooCommercePayments;
use Automattic\WooCommerce\Admin\Features\PaymentGatewaySuggestions\Init;
use Automattic\WooCommerce\Admin\PluginsHelper;
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)

defined( 'ABSPATH' ) || exit;

if ( class_exists( 'WC_Settings_Payment_Gateways', false ) ) {
	return new WC_Settings_Payment_Gateways();
}

/**
 * WC_Settings_Payment_Gateways.
 */
class WC_Settings_Payment_Gateways extends WC_Settings_Page {

<<<<<<< HEAD
	const TAB_NAME = 'checkout';

	const MAIN_SECTION_NAME    = 'main';
	const OFFLINE_SECTION_NAME = 'offline';
	const COD_SECTION_NAME     = 'cod';  // Cash on delivery.
	const BACS_SECTION_NAME    = 'bacs';  // Direct bank transfer.
	const CHEQUE_SECTION_NAME  = 'cheque';  // Cheque payments.

	/**
	 * Get the whitelist of sections to render using React.
	 *
	 * @return array List of section identifiers.
	 */
	private function get_reactify_render_sections() {
		$sections = array(
			self::MAIN_SECTION_NAME,
			self::OFFLINE_SECTION_NAME,
			self::COD_SECTION_NAME,
			self::BACS_SECTION_NAME,
			self::CHEQUE_SECTION_NAME,
		);

		/**
		 * Filters the list of payment settings sections to be rendered using React.
		 *
		 * @since 9.3.0
		 *
		 * @param array $sections List of section identifiers.
		 */
		return apply_filters( 'experimental_woocommerce_admin_payment_reactify_render_sections', $sections );
	}

	/**
	 * Standardize the current section name.
	 *
	 * @param string $section The section name to standardize.
	 *
	 * @return string The standardized section name.
	 */
	private function standardize_section_name( string $section ): string {
		// If the section is empty, we are on the main settings page/section. Use a standardized name.
		if ( '' === $section ) {
			return self::MAIN_SECTION_NAME;
		}

		return $section;
	}

=======
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
	/**
	 * Constructor.
	 */
	public function __construct() {
<<<<<<< HEAD
		$this->id    = self::TAB_NAME;
		$this->label = esc_html_x( 'Payments', 'Settings tab label', 'woocommerce' );

		// Add filters and actions.
		add_action( 'admin_head', array( $this, 'hide_help_tabs' ) );
		// Hook in as late as possible - `in_admin_header` is the last action before the `admin_notices` action is fired.
		// It is too risky to hook into `admin_notices` with a low priority because the callbacks might be cached.
		add_action( 'in_admin_header', array( $this, 'suppress_admin_notices' ), PHP_INT_MAX );

		// Do not show any store alerts (WC admin notes with type: 'error,update' and status: 'unactioned')
		// on the WooCommerce Payments settings page and Reactified sections.
		add_filter( 'woocommerce_admin_features', array( $this, 'suppress_store_alerts' ), PHP_INT_MAX );

=======
		$this->id    = 'checkout'; // @todo In future versions this may make more sense as 'payment' however to avoid breakage lets leave this alone until we refactor settings APIs in general.
		$this->label = _x( 'Payments', 'Settings tab label', 'woocommerce' );

		add_action( 'woocommerce_admin_field_payment_gateways_banner', array( $this, 'payment_gateways_banner' ) );
		add_action( 'woocommerce_admin_field_payment_gateways', array( $this, 'payment_gateways_setting' ) );
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
		parent::__construct();
	}

	/**
	 * Setting page icon.
	 *
	 * @var string
	 */
	public $icon = 'payment';

	/**
<<<<<<< HEAD
	 * Output the settings.
	 */
	public function output() {
		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		global $current_section;

		// We don't want to output anything from the action for now. So we buffer it and discard it.
		ob_start();
		/**
		 * Fires before the payment gateways settings fields are rendered.
		 *
		 * @since 1.5.7
		 */
		do_action( 'woocommerce_admin_field_payment_gateways' );
		ob_end_clean();

		if ( $this->should_render_react_section( $current_section ) ) {
			$this->render_react_section( $current_section );
		} elseif ( $current_section ) {
			// Load gateways so we can show any global options they may have.
			$payment_gateways = WC()->payment_gateways()->payment_gateways;
			$this->render_classic_gateway_settings_page( $payment_gateways, $current_section );
		} else {
			$this->render_react_section( self::MAIN_SECTION_NAME );
=======
	 * Get own sections.
	 *
	 * @return array
	 */
	protected function get_own_sections() {
		return array(
			'' => __( 'Payment methods', 'woocommerce' ),
		);
	}

	/**
	 * Get settings array.
	 *
	 * @return array
	 */
	protected function get_settings_for_default_section() {
		$settings =
			array(
				array(
					'type' => 'title', // this is needed as <table> tag is generated by this element, even if it has no other content.
				),
				array( 'type' => 'payment_gateways_banner' ), // React mount point for embedded banner slotfill.
				array(
					'type' => 'payment_gateways',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'payment_gateways_options',
				),
			);

		return apply_filters( 'woocommerce_payment_gateways_settings', $settings );
	}

	/**
	 * Output the settings.
	 */
	public function output() {
		//phpcs:disable WordPress.Security.NonceVerification.Recommended
		global $current_section;

		// Load gateways so we can show any global options they may have.
		$payment_gateways = WC()->payment_gateways->payment_gateways();

		if ( $current_section ) {
			foreach ( $payment_gateways as $gateway ) {
				if ( in_array( $current_section, array( $gateway->id, sanitize_title( get_class( $gateway ) ) ), true ) ) {
					if ( isset( $_GET['toggle_enabled'] ) ) {
						$enabled = $gateway->get_option( 'enabled' );

						if ( $enabled ) {
							$gateway->settings['enabled'] = wc_string_to_bool( $enabled ) ? 'no' : 'yes';
						}
					}
					$this->run_gateway_admin_options( $gateway );
					break;
				}
			}
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
		}

		parent::output();
		//phpcs:enable
	}

	/**
<<<<<<< HEAD
	 * Get settings array.
	 *
	 * This is just for backward compatibility with the rest of the codebase (primarily API responses).
	 *
	 * @return array
	 */
	protected function get_settings_for_default_section() {
		return array(
			array(
				'type' => 'title',
				// this is needed as <table> tag is generated by this element, even if it has no other content.
			),
			array(
				'type' => 'sectionend',
				'id'   => 'payment_gateways_options',
			),
		);
	}

	/**
	 * Check if the given section should be rendered using React.
	 *
	 * @param string $section The section to check.
	 * @return bool Whether the section should be rendered using React.
	 */
	private function should_render_react_section( $section ) {
		return in_array( $section, $this->get_reactify_render_sections(), true );
	}

	/**
	 * Render the React section.
	 *
	 * @param string $section The section to render.
	 */
	private function render_react_section( string $section ) {
		global $hide_save_button;
		$hide_save_button = true;
		echo '<div id="experimental_wc_settings_payments_' . esc_attr( $section ) . '"></div>';
	}

	/**
	 * Render the classic gateway settings page.
	 *
	 * @param array  $payment_gateways The payment gateways.
	 * @param string $current_section  The current section.
	 */
	private function render_classic_gateway_settings_page( array $payment_gateways, string $current_section ) {
		foreach ( $payment_gateways as $gateway ) {
			if ( in_array( $current_section, array( $gateway->id, sanitize_title( get_class( $gateway ) ) ), true ) ) {
				if ( isset( $_GET['toggle_enabled'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
					$enabled = $gateway->get_option( 'enabled' );

					if ( $enabled ) {
						$gateway->settings['enabled'] = wc_string_to_bool( $enabled ) ? 'no' : 'yes';
					}
				}
				$this->run_gateway_admin_options( $gateway );
				break;
			}
		}
	}

	/**
	 * Run the 'admin_options' method on a given gateway.
	 *
	 * This method exists to help with unit testing.
=======
	 * Run the 'admin_options' method on a given gateway.
	 * This method exists to easy unit testing.
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
	 *
	 * @param object $gateway The gateway object to run the method on.
	 */
	protected function run_gateway_admin_options( $gateway ) {
		$gateway->admin_options();
	}

	/**
<<<<<<< HEAD
	 * Get all sections for the current page.
	 *
	 * Reactified section pages won't have any sections.
	 * The rest of the settings pages will get the default/own section and those added via
	 * the `woocommerce_get_sections_checkout` filter.
	 *
	 * @return array The sections for this settings page.
	 */
	public function get_sections() {
		global $current_tab, $current_section;

		// We only want to prevent sections on the main WooCommerce Payments settings page and Reactified sections.
		if ( self::TAB_NAME === $current_tab && $this->should_render_react_section( $this->standardize_section_name( $current_section ) ) ) {
			return array();
		}

		return parent::get_sections();
=======
	 * Creates the React mount point for the embedded banner.
	 */
	public function payment_gateways_banner() {
		?>
		<div id="wc_payments_settings_slotfill"> </div>
		<?php
	}

	/**
	 * Output payment gateway settings.
	 */
	public function payment_gateways_setting() {
		?>
		<tr valign="top">
		<td class="wc_payment_gateways_wrapper" colspan="2">
			<table class="wc_gateways widefat" cellspacing="0" aria-describedby="payment_gateways_options-description">
				<thead>
					<tr>
						<?php
						$default_columns = array(
							'sort'        => '',
							'name'        => __( 'Method', 'woocommerce' ),
							'status'      => __( 'Enabled', 'woocommerce' ),
							'description' => __( 'Description', 'woocommerce' ),
							'action'      => '',
						);

						$columns = apply_filters( 'woocommerce_payment_gateways_setting_columns', $default_columns );

						foreach ( $columns as $key => $column ) {
							echo '<th class="' . esc_attr( $key ) . '">' . esc_html( $column ) . '</th>';
						}
						?>
						</tr>
					</thead>
					<tbody>
						<?php
						$payment_gateways = WC()->payment_gateways->payment_gateways();
						foreach ( $payment_gateways as $gateway ) {

							echo '<tr data-gateway_id="' . esc_attr( $gateway->id ) . '">';

							foreach ( $columns as $key => $column ) {
								if ( ! array_key_exists( $key, $default_columns ) ) {
									do_action( 'woocommerce_payment_gateways_setting_column_' . $key, $gateway );
									continue;
								}

								$width = '';

								if ( in_array( $key, array( 'sort', 'status', 'action' ), true ) ) {
									$width = '1%';
								}

								$method_title = $gateway->get_method_title() ? $gateway->get_method_title() : $gateway->get_title();
								$custom_title = $gateway->get_title();

								echo '<td class="' . esc_attr( $key ) . '" width="' . esc_attr( $width ) . '">';

								switch ( $key ) {
									case 'sort':
										?>
										<div class="wc-item-reorder-nav">
											<button type="button" class="wc-move-up" tabindex="0" aria-hidden="false" aria-label="<?php /* Translators: %s Payment gateway name. */ echo esc_attr( sprintf( __( 'Move the "%s" payment method up', 'woocommerce' ), $method_title ) ); ?>"><?php esc_html_e( 'Move up', 'woocommerce' ); ?></button>
											<button type="button" class="wc-move-down" tabindex="0" aria-hidden="false" aria-label="<?php /* Translators: %s Payment gateway name. */ echo esc_attr( sprintf( __( 'Move the "%s" payment method down', 'woocommerce' ), $method_title ) ); ?>"><?php esc_html_e( 'Move down', 'woocommerce' ); ?></button>
											<input type="hidden" name="gateway_order[]" value="<?php echo esc_attr( $gateway->id ); ?>" />
										</div>
										<?php
										break;
									case 'name':
										echo '<a href="' . esc_url( admin_url( 'admin.php?page=wc-settings&tab=checkout&section=' . strtolower( $gateway->id ) ) ) . '" class="wc-payment-gateway-method-title">' . wp_kses_post( $method_title ) . '</a>';

										if ( $method_title !== $custom_title ) {
											echo '<span class="wc-payment-gateway-method-name">&nbsp;&ndash;&nbsp;' . wp_kses_post( $custom_title ) . '</span>';
										}
										break;
									case 'description':
										echo wp_kses_post( $gateway->get_method_description() );
										break;
									case 'action':
										$setup_url = admin_url( 'admin.php?page=wc-settings&tab=checkout&section=' . strtolower( $gateway->id ) );
										// Override the behaviour for the WooPayments plugin.
										if (
											// Keep old brand name for backwards compatibility.
											( 'WooCommerce Payments' === $method_title || 'WooPayments' === $method_title ) &&
											class_exists( 'WC_Payments_Account' )
										) {
											if ( ! WooCommercePayments::is_connected() || WooCommercePayments::is_account_partially_onboarded() ) {
												// The CTA text and label is "Finish setup" if the account is not connected or not completely onboarded.
												// Plugin will handle the redirection to the connect page or directly to the provider (e.g. Stripe).
												$setup_url = WC_Payments_Account::get_connect_url();
												// Add the `from` parameter to the URL, so we know where the user came from.
												$setup_url = add_query_arg( 'from', 'WCADMIN_PAYMENT_SETTINGS', $setup_url );
												/* Translators: %s Payment gateway name. */
												echo '<a class="button alignright" aria-label="' . esc_attr( sprintf( __( 'Set up the "%s" payment method', 'woocommerce' ), $method_title ) ) . '" href="' . esc_url( $setup_url ) . '">' . esc_html__( 'Finish setup', 'woocommerce' ) . '</a>';
											} else {
												// If the account is fully onboarded, the CTA text and label is "Manage" regardless gateway is enabled or not.
												/* Translators: %s Payment gateway name. */
												echo '<a class="button alignright" aria-label="' . esc_attr( sprintf( __( 'Manage the "%s" payment method', 'woocommerce' ), $method_title ) ) . '" href="' . esc_url( $setup_url ) . '">' . esc_html__( 'Manage', 'woocommerce' ) . '</a>';
											}
										} elseif ( wc_string_to_bool( $gateway->enabled ) ) {
											/* Translators: %s Payment gateway name. */
											echo '<a class="button alignright" aria-label="' . esc_attr( sprintf( __( 'Manage the "%s" payment method', 'woocommerce' ), $method_title ) ) . '" href="' . esc_url( $setup_url ) . '">' . esc_html__( 'Manage', 'woocommerce' ) . '</a>';
										} else {
											/* Translators: %s Payment gateway name. */
											echo '<a class="button alignright" aria-label="' . esc_attr( sprintf( __( 'Set up the "%s" payment method', 'woocommerce' ), $method_title ) ) . '" href="' . esc_url( $setup_url ) . '">' . esc_html__( 'Finish setup', 'woocommerce' ) . '</a>';
										}
										break;
									case 'status':
										echo '<a class="wc-payment-gateway-method-toggle-enabled" href="' . esc_url( admin_url( 'admin.php?page=wc-settings&tab=checkout&section=' . strtolower( $gateway->id ) ) ) . '">';
										if ( wc_string_to_bool( $gateway->enabled ) ) {
											/* Translators: %s Payment gateway name. */
											echo '<span class="woocommerce-input-toggle woocommerce-input-toggle--enabled" aria-label="' . esc_attr( sprintf( __( 'The "%s" payment method is currently enabled', 'woocommerce' ), $method_title ) ) . '">' . esc_attr__( 'Yes', 'woocommerce' ) . '</span>';
										} else {
											/* Translators: %s Payment gateway name. */
											echo '<span class="woocommerce-input-toggle woocommerce-input-toggle--disabled" aria-label="' . esc_attr( sprintf( __( 'The "%s" payment method is currently disabled', 'woocommerce' ), $method_title ) ) . '">' . esc_attr__( 'No', 'woocommerce' ) . '</span>';
										}
										echo '</a>';
										break;
								}

								echo '</td>';
							}

							echo '</tr>';
						}
						/**
						 * Add "Other payment methods" link in WooCommerce -> Settings -> Payments
						 * When the store is in WC Payments eligible country.
						 * See https://github.com/woocommerce/woocommerce/issues/32130 for more details.
						 */
						if ( WooCommercePayments::is_supported() ) {
							$wcpay_setup        = isset( $payment_gateways['woocommerce_payments'] ) && ! $payment_gateways['woocommerce_payments']->needs_setup();
							$country            = wc_get_base_location()['country'];
							$plugin_suggestions = Init::get_suggestions();
							$active_plugins     = PluginsHelper::get_active_plugin_slugs();

							if ( $wcpay_setup ) {
								$filter_by = 'category_additional';
							} else {
								$filter_by = 'category_other';
							}

							$marketplace_cta_allowed_html = array(
								'a' => array(
									'href'  => array(),
									'id'    => array(),
									'style' => array(),
								),
							);

							$marketplace_cta = sprintf(
								wp_kses(
									/* translators: %s: URL to WooCommerce marketplace */
									__( 'Visit the <a href="%s" id="settings-other-payment-methods" style="text-decoration: underline;">Official WooCommerce Marketplace</a> to find additional payment providers.', 'woocommerce' ),
									$marketplace_cta_allowed_html
								),
								esc_url( admin_url( 'admin.php?page=wc-admin&tab=extensions&path=/extensions&category=payment-gateways' ) )
							);

							$plugin_suggestions = array_filter(
								$plugin_suggestions,
								function( $plugin ) use ( $country, $filter_by, $active_plugins ) {
									if ( ! isset( $plugin->{$filter_by} ) || ! isset( $plugin->image_72x72 ) || ! isset( $plugin->plugins[0] ) || in_array( $plugin->plugins[0], $active_plugins, true ) ) {
										return false;
									}
									return in_array( $country, $plugin->{$filter_by}, true );
								}
							);

							$columns_count = count( $columns );

							echo '<tr>';
							// phpcs:ignore -- ignoring the error since the value is harded.
							echo "<td style='font-size: 13px; border-top: 1px solid #c3c4c7; background-color: #fff' colspan='{$columns_count}'>";
							echo '<span style="margin-right: 10px;">';
							echo wp_kses( $marketplace_cta, $marketplace_cta_allowed_html );
							echo '</span>';
							if ( count( $plugin_suggestions ) ) {
								foreach ( $plugin_suggestions as $plugin_suggestion ) {
									$alt = str_replace( '.png', '', basename( $plugin_suggestion->image_72x72 ) );
									// phpcs:ignore
									echo "<img src='{$plugin_suggestion->image_72x72}' alt='{$alt}' width='24' height='24' style='vertical-align: middle; margin-right: 8px;'/>";
								}
								echo '& more.';
							}
							echo '</td>';
							echo '</tr>';
						}
						?>
					</tbody>
				</table>
			</td>
		</tr>
		<?php
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
	}

	/**
	 * Save settings.
	 */
	public function save() {
		global $current_section;

<<<<<<< HEAD
		$standardized_section = $this->standardize_section_name( $current_section );

=======
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
		$wc_payment_gateways = WC_Payment_Gateways::instance();

		$this->save_settings_for_current_section();

<<<<<<< HEAD
		if ( self::MAIN_SECTION_NAME === $standardized_section ) {
			// This makes sure 'gateway ordering' is saved.
			$wc_payment_gateways->process_admin_options();
			$wc_payment_gateways->init();
		} else {
			// This may be a gateway or some custom section.
			foreach ( $wc_payment_gateways->payment_gateways() as $gateway ) {
				// If the section is that of a gateway, we need to run the gateway actions and init.
				if ( in_array( $standardized_section, array( $gateway->id, sanitize_title( get_class( $gateway ) ) ), true ) ) {
					/**
					 * Fires update actions for payment gateways.
					 *
					 * @since 3.4.0
					 *
					 * @param int $gateway->id Gateway ID.
					 */
					do_action( 'woocommerce_update_options_payment_gateways_' . $gateway->id );
					$wc_payment_gateways->init();

					// There is no need to run the action and gateways init again
					// since we can't be on the section page of multiple gateways at once.
					break;
=======
		if ( ! $current_section ) {
			// If section is empty, we're on the main settings page. This makes sure 'gateway ordering' is saved.
			$wc_payment_gateways->process_admin_options();
			$wc_payment_gateways->init();
		} else {
			// There is a section - this may be a gateway or custom section.
			foreach ( $wc_payment_gateways->payment_gateways() as $gateway ) {
				if ( in_array( $current_section, array( $gateway->id, sanitize_title( get_class( $gateway ) ) ), true ) ) {
					do_action( 'woocommerce_update_options_payment_gateways_' . $gateway->id );
					$wc_payment_gateways->init();
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
				}
			}

			$this->do_update_options_action();
		}
	}
<<<<<<< HEAD

	/**
	 * Hide the help tabs.
	 */
	public function hide_help_tabs() {
		$screen = get_current_screen();

		if ( ! $screen instanceof WP_Screen || 'woocommerce_page_wc-settings' !== $screen->id ) {
			return;
		}

		global $current_tab, $current_section;
		// We only want to hide the help tabs on the main WooCommerce Payments settings page and Reactified sections.
		if ( ! ( self::TAB_NAME === $current_tab && $this->should_render_react_section( $this->standardize_section_name( $current_section ) ) ) ) {
			return;
		}

		$screen->remove_help_tabs();
	}

	/**
	 * Suppress WP admin notices on the WooCommerce Payments settings page.
	 */
	public function suppress_admin_notices() {
		global $wp_filter;

		$screen = get_current_screen();

		if ( ! $screen instanceof WP_Screen || 'woocommerce_page_wc-settings' !== $screen->id ) {
			return;
		}

		global $current_tab, $current_section;
		// We only want to suppress notices on the main WooCommerce Payments settings page and Reactified sections.
		if ( ! ( self::TAB_NAME === $current_tab && $this->should_render_react_section( $this->standardize_section_name( $current_section ) ) ) ) {
			return;
		}

		// Generic admin notices are definitely not needed.
		remove_all_actions( 'all_admin_notices' );

		// WooCommerce uses the 'admin_notices' hook for its own notices.
		// We will only allow WooCommerce core notices to be displayed.
		$wp_admin_notices_hook = $wp_filter['admin_notices'] ?? null;
		if ( ! $wp_admin_notices_hook || ! $wp_admin_notices_hook->has_filters() ) {
			// Nothing to do if there are no actions hooked into `admin_notices`.
			return;
		}

		$wc_admin_notices = WC_Admin_Notices::get_notices();
		if ( empty( $wc_admin_notices ) ) {
			// If there are no WooCommerce core notices, we can remove all actions hooked into `admin_notices`.
			remove_all_actions( 'admin_notices' );
			return;
		}

		// Go through the callbacks hooked into `admin_notices` and
		// remove any that are NOT from the WooCommerce core (i.e. from the `WC_Admin_Notices` class).
		foreach ( $wp_admin_notices_hook->callbacks as $priority => $callbacks ) {
			if ( ! is_array( $callbacks ) ) {
				continue;
			}

			foreach ( $callbacks as $callback ) {
				// Ignore malformed callbacks.
				if ( ! is_array( $callback ) ) {
					continue;
				}
				// WooCommerce doesn't use closures to handle notices.
				// WooCommerce core notices are handled by `WC_Admin_Notices` class methods.
				// Remove plain functions or closures.
				if ( ! is_array( $callback['function'] ) ) {
					remove_action( 'admin_notices', $callback['function'], $priority );
					continue;
				}

				$class_or_object = $callback['function'][0] ?? null;
				// We need to allow Automattic\WooCommerce\Internal\Admin\Loader methods callbacks
				// because they are used to wrap notices.
				// @see Automattic\WooCommerce\Internal\Admin\Loader::inject_before_notices().
				// @see Automattic\WooCommerce\Internal\Admin\Loader::inject_after_notices().
				if (
					(
						// We have a class name.
						is_string( $class_or_object ) &&
						! ( WC_Admin_Notices::class === $class_or_object || Loader::class === $class_or_object )
					) ||
					(
						// We have a class instance.
						is_object( $class_or_object ) &&
						! ( $class_or_object instanceof WC_Admin_Notices || $class_or_object instanceof Loader )
					)
				) {
					remove_action( 'admin_notices', $callback['function'], $priority );
				}
			}
		}
	}

	/**
	 * Suppress the store-alerts WCAdmin feature on the WooCommerce Payments settings page and Reactified sections.
	 *
	 * @param mixed $features The WCAdmin features list.
	 *
	 * @return mixed The modified features list.
	 */
	public function suppress_store_alerts( $features ) {
		global $current_tab, $current_section;

		$feature_name = 'store-alerts';

		if ( is_array( $features ) &&
			in_array( $feature_name, $features, true ) &&
			self::TAB_NAME === $current_tab &&
			$this->should_render_react_section( $this->standardize_section_name( $current_section ) ) ) {

			unset( $features[ array_search( $feature_name, $features, true ) ] );
		}

		return $features;
	}
=======
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
}

return new WC_Settings_Payment_Gateways();
