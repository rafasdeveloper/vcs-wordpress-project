<?php
<<<<<<< HEAD
declare(strict_types=1);
=======
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)

namespace Automattic\WooCommerce\StoreApi;

use Automattic\Jetpack\Constants;
<<<<<<< HEAD
use Automattic\WooCommerce\StoreApi\Utilities\CartTokenUtils;
=======
use Automattic\WooCommerce\StoreApi\Utilities\JsonWebToken;
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
use WC_Session;

defined( 'ABSPATH' ) || exit;

/**
 * SessionHandler class
 */
final class SessionHandler extends WC_Session {
	/**
	 * Token from HTTP headers.
	 *
	 * @var string
	 */
	protected $token;

	/**
	 * Table name for session data.
	 *
	 * @var string Custom session table name
	 */
	protected $table;

	/**
	 * Expiration timestamp.
	 *
	 * @var int
	 */
	protected $session_expiration;

	/**
	 * Constructor for the session class.
	 */
	public function __construct() {
		$this->token = wc_clean( wp_unslash( $_SERVER['HTTP_CART_TOKEN'] ?? '' ) );
		$this->table = $GLOBALS['wpdb']->prefix . 'woocommerce_sessions';
	}

	/**
	 * Init hooks and session data.
	 */
	public function init() {
		$this->init_session_from_token();
		add_action( 'shutdown', array( $this, 'save_data' ), 20 );
	}

	/**
	 * Process the token header to load the correct session.
	 */
	protected function init_session_from_token() {
<<<<<<< HEAD
		$payload = CartTokenUtils::get_cart_token_payload( $this->token );

		$this->_customer_id       = $payload['user_id'];
		$this->session_expiration = $payload['exp'];
=======
		$payload = JsonWebToken::get_parts( $this->token )->payload;

		$this->_customer_id       = $payload->user_id;
		$this->session_expiration = $payload->exp;
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
		$this->_data              = (array) $this->get_session( $this->_customer_id, array() );
	}

	/**
	 * Returns the session.
	 *
	 * @param string $customer_id Customer ID.
<<<<<<< HEAD
	 * @param mixed  $default_value Default session value.
	 *
	 * @return string|array|bool
	 */
	public function get_session( $customer_id, $default_value = false ) {
=======
	 * @param mixed  $default Default session value.
	 *
	 * @return string|array|bool
	 */
	public function get_session( $customer_id, $default = false ) {
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
		global $wpdb;

		// This mimics behaviour from default WC_Session_Handler class. There will be no sessions retrieved while WP setup is due.
		if ( Constants::is_defined( 'WP_SETUP_CONFIG' ) ) {
			return false;
		}

		$value = $wpdb->get_var(
			$wpdb->prepare(
<<<<<<< HEAD
				'SELECT session_value FROM %i WHERE session_key = %s',
				$this->table,
=======
				"SELECT session_value FROM $this->table WHERE session_key = %s", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
				$customer_id
			)
		);

		if ( is_null( $value ) ) {
<<<<<<< HEAD
			$value = $default_value;
=======
			$value = $default;
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
		}

		return maybe_unserialize( $value );
	}

	/**
	 * Save data and delete user session.
	 */
	public function save_data() {
		// Dirty if something changed - prevents saving nothing new.
		if ( $this->_dirty ) {
			global $wpdb;

			$wpdb->query(
				$wpdb->prepare(
<<<<<<< HEAD
					'INSERT INTO %i (`session_key`, `session_value`, `session_expiry`) VALUES (%s, %s, %d) ON DUPLICATE KEY UPDATE `session_value` = VALUES(`session_value`), `session_expiry` = VALUES(`session_expiry`)',
					$this->table,
=======
					"INSERT INTO $this->table (`session_key`, `session_value`, `session_expiry`) VALUES (%s, %s, %d) ON DUPLICATE KEY UPDATE `session_value` = VALUES(`session_value`), `session_expiry` = VALUES(`session_expiry`)", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
					$this->_customer_id,
					maybe_serialize( $this->_data ),
					$this->session_expiration
				)
			);

			$this->_dirty = false;
		}
	}
}
