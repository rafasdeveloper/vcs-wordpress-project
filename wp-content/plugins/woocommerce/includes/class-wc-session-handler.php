<<<<<<< HEAD
<?php // phpcs:ignore Generic.PHP.RequireStrictTypes.MissingDeclaration
=======
<?php
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
/**
 * Handle data for the current customers session.
 * Implements the WC_Session abstract class.
 *
 * From 2.5 this uses a custom table for session storage. Based on https://github.com/kloon/woocommerce-large-sessions.
 *
 * @class    WC_Session_Handler
<<<<<<< HEAD
 * @package  WooCommerce\Classes
 * @internal "Missing required strict_types declaration" rule has been ignored to prevent errors with `StringUtil::starts_with` when used on a nonce action which could be -1 rather than a string.
 */

use Automattic\Jetpack\Constants;
use Automattic\WooCommerce\Utilities\StringUtil;
use Automattic\WooCommerce\StoreApi\Utilities\CartTokenUtils;
=======
 * @version  2.5.0
 * @package  WooCommerce\Classes
 */

use Automattic\Jetpack\Constants;
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)

defined( 'ABSPATH' ) || exit;

/**
 * Session handler class.
 */
class WC_Session_Handler extends WC_Session {

	/**
	 * Cookie name used for the session.
	 *
	 * @var string cookie name
	 */
<<<<<<< HEAD
	protected $_cookie; // phpcs:ignore PSR2.Classes.PropertyDeclaration.Underscore
=======
	protected $_cookie;
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)

	/**
	 * Stores session expiry.
	 *
	 * @var string session due to expire timestamp
	 */
<<<<<<< HEAD
	protected $_session_expiring; // phpcs:ignore PSR2.Classes.PropertyDeclaration.Underscore
=======
	protected $_session_expiring;
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)

	/**
	 * Stores session due to expire timestamp.
	 *
	 * @var string session expiration timestamp
	 */
<<<<<<< HEAD
	protected $_session_expiration; // phpcs:ignore PSR2.Classes.PropertyDeclaration.Underscore
=======
	protected $_session_expiration;
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)

	/**
	 * True when the cookie exists.
	 *
	 * @var bool Based on whether a cookie exists.
	 */
<<<<<<< HEAD
	protected $_has_cookie = false; // phpcs:ignore PSR2.Classes.PropertyDeclaration.Underscore
=======
	protected $_has_cookie = false;
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)

	/**
	 * Table name for session data.
	 *
	 * @var string Custom session table name
	 */
<<<<<<< HEAD
	protected $_table; // phpcs:ignore PSR2.Classes.PropertyDeclaration.Underscore
=======
	protected $_table;
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)

	/**
	 * Constructor for the session class.
	 */
	public function __construct() {
<<<<<<< HEAD
		/**
		 * Filter the cookie name.
		 *
		 * @since 3.6.0
		 *
		 * @param string $cookie Cookie name.
		 */
		$this->_cookie = apply_filters( 'woocommerce_cookie', 'wp_woocommerce_session_' . COOKIEHASH );
		$this->_table  = $GLOBALS['wpdb']->prefix . 'woocommerce_sessions';
		$this->set_session_expiration();
=======
		$this->_cookie = apply_filters( 'woocommerce_cookie', 'wp_woocommerce_session_' . COOKIEHASH );
		$this->_table  = $GLOBALS['wpdb']->prefix . 'woocommerce_sessions';
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
	}

	/**
	 * Init hooks and session data.
	 *
	 * @since 3.3.0
	 */
	public function init() {
<<<<<<< HEAD
		$this->init_hooks();
		$this->init_session();
	}

	/**
	 * Initialize the hooks.
	 */
	protected function init_hooks() {
=======
		$this->init_session_cookie();

>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
		add_action( 'woocommerce_set_cart_cookies', array( $this, 'set_customer_session_cookie' ), 10 );
		add_action( 'wp', array( $this, 'maybe_set_customer_session_cookie' ), 99 );
		add_action( 'shutdown', array( $this, 'save_data' ), 20 );
		add_action( 'wp_logout', array( $this, 'destroy_session' ) );

		if ( ! is_user_logged_in() ) {
			add_filter( 'nonce_user_logged_out', array( $this, 'maybe_update_nonce_user_logged_out' ), 10, 2 );
		}
	}

	/**
<<<<<<< HEAD
	 * Initialize the session from either the request or the cookie.
	 */
	private function init_session() {
		if ( ! $this->init_session_from_request() ) {
			$this->init_session_cookie();
		}
	}

	/**
	 * Initialize the session from the query string parameter.
	 *
	 * If the current user is logged in, the token session will replace the current user's session.
	 * If the current user is logged out, the token session will be cloned to a new session.
	 *
	 * Only guest sessions are restored, hence the check for the t_ prefix on the customer ID.
	 *
	 * @return bool
	 */
	private function init_session_from_request() {
		$session_token = wc_clean( wp_unslash( $_GET['session'] ?? '' ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		if ( empty( $session_token ) || ! CartTokenUtils::validate_cart_token( $session_token ) ) {
			return false;
		}

		$payload = CartTokenUtils::get_cart_token_payload( $session_token );

		if ( ! $this->is_customer_guest( $payload['user_id'] ) || ! $this->session_exists( $payload['user_id'] ) ) {
			return false;
		}

		// Check to see if the current user has a session before proceeding with token handling.
		$cookie = $this->get_session_cookie();

		if ( $cookie ) {
			// User owns this token. Return and use cookie session.
			if ( $cookie[0] === $payload['user_id'] ) {
				return false;
			}

			$cookie_session_data = $this->get_session( $cookie[0] );

			// Cookie session was originally created via this token. Return and use cookie session to prevent creating a new clone.
			if ( isset( $cookie_session_data['previous_customer_id'] ) && $cookie_session_data['previous_customer_id'] === $payload['user_id'] ) {
				return false;
			}
		}

		// Generate new customer ID for the new session before cloning the data.
		$this->_customer_id = $this->generate_customer_id();
		$this->set_customer_session_cookie( true );
		$this->clone_session_data( $payload['user_id'] );

		return true;
	}

	/**
=======
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
	 * Setup cookie and customer ID.
	 *
	 * @since 3.6.0
	 */
	public function init_session_cookie() {
		$cookie = $this->get_session_cookie();

<<<<<<< HEAD
		if ( ! $cookie ) {
			// If there is no cookie, generate a new session/customer ID.
			$this->_customer_id = $this->generate_customer_id();
			$this->_data        = $this->get_session_data();
			return;
		}

		// Customer ID will be an MD5 hash id this is a guest session.
		$this->_customer_id        = $cookie[0];
		$this->_session_expiration = $cookie[1];
		$this->_session_expiring   = $cookie[2];
		$this->_has_cookie         = true;

		$this->restore_session_data();

		/**
		 * This clears the session if the cookie is invalid.
		 *
		 * Previously this also cleared the session when $this->_data was empty, and the cart was not yet initialised,
		 * however this caused a conflict with WooCommerce Payments session handler which overrides this class.
		 *
		 * Ref: https://github.com/woocommerce/woocommerce/pull/57652
		 * See also: https://github.com/woocommerce/woocommerce/pull/59530
		 */
		if ( ! $this->is_session_cookie_valid() ) {
			$this->destroy_session();
		}

		// If the user logs in, update session.
		if ( is_user_logged_in() && strval( get_current_user_id() ) !== $this->_customer_id ) {
			$this->migrate_guest_session_to_user_session( get_current_user_id() );
		}

		// Update session if its close to expiring.
		if ( $this->is_session_expiring() ) {
			$this->set_session_expiration();
			$this->update_session_timestamp( $this->_customer_id, $this->_session_expiration );
		}
	}

	/**
	 * Clones a session to the current session. Exclude customer details for privacy reasons.
	 *
	 * @param string $clone_from_customer_id The customer ID to clone from.
	 */
	private function clone_session_data( string $clone_from_customer_id ) {
		$session_data                         = $this->get_session( $clone_from_customer_id, array() );
		$session_data['previous_customer_id'] = $clone_from_customer_id;
		$session_data                         = array_diff_key( $session_data, array( 'customer' => true ) );
		$this->_data                          = $session_data;
		$this->_dirty                         = true;
		$this->save_data();
	}

	/**
	 * Migrates a guest session to a user session.
	 *
	 * @param int $user_id The user ID to migrate to.
	 */
	private function migrate_guest_session_to_user_session( int $user_id ) {
		$guest_session_id   = $this->_customer_id;
		$this->_customer_id = strval( $user_id );
		$this->set_customer_session_cookie( true );

		$this->_data  = $this->get_session( $guest_session_id, array() );
		$this->_dirty = true;
		$this->save_data( $guest_session_id );

		/**
		 * Fires after a customer has logged in, and their guest session id has been
		 * deleted with its data migrated to a customer id.
		 *
		 * This hook gives extensions the chance to connect the old session id to the
		 * customer id, if the key is being used externally.
		 *
		 * @since 8.8.0
		 *
		 * @param string $guest_session_id The former session ID, as generated by `::generate_customer_id()`.
		 * @param string $user_session_id The Customer ID that the former session was converted to.
		 */
		do_action( 'woocommerce_guest_session_to_user_id', $guest_session_id, $this->_customer_id );
	}

	/**
	 * Restore the session data from the database.
	 *
	 * @since 10.0.0
	 */
	private function restore_session_data() {
		$session_data = $this->get_session_data();

		/**
		 * Filters the session data when restoring from storage during initialization.
		 *
		 * This filter allows you to:
		 * 1. Modify the session data before it's loaded, including adding or removing specific session data entries
		 * 2. Clear the entire session by returning an empty array
		 *
		 * Note: If the filtered data is empty, the session will be destroyed and the
		 * guest's session cookie will be removed. This can be useful for high-traffic
		 * sites that prioritize page caching over maintaining all session data.
		 *
		 * @since 9.9.0
		 *
		 * @param array $session_data The session data loaded from storage.
		 * @return array Modified session data to be used for initialization.
		 */
		$this->_data = apply_filters( 'woocommerce_restored_session_data', $session_data );
=======
		if ( $cookie ) {
			// Customer ID will be an MD5 hash id this is a guest session.
			$this->_customer_id        = $cookie[0];
			$this->_session_expiration = $cookie[1];
			$this->_session_expiring   = $cookie[2];
			$this->_has_cookie         = true;
			$this->_data               = $this->get_session_data();

			if ( ! $this->is_session_cookie_valid() ) {
				$this->destroy_session();
				$this->set_session_expiration();
			}

			// If the user logs in, update session.
			if ( is_user_logged_in() && strval( get_current_user_id() ) !== $this->_customer_id ) {
				$guest_session_id   = $this->_customer_id;
				$this->_customer_id = strval( get_current_user_id() );
				$this->_dirty       = true;
				$this->save_data( $guest_session_id );
				$this->set_customer_session_cookie( true );

				/**
				 * Fires after a customer has logged in, and their guest session id has been
				 * deleted with its data migrated to a customer id.
				 *
				 * This hook gives extensions the chance to connect the old session id to the
				 * customer id, if the key is being used externally.
				 *
				 * @since 8.8.0
				 *
				 * @param string $guest_session_id The former session ID, as generated by `::generate_customer_id()`.
				 * @param int    $customer_id      The Customer ID that the former session was converted to.
				 */
				do_action( 'woocommerce_guest_session_to_user_id', $guest_session_id, $this->_customer_id );
			}

			// Update session if its close to expiring.
			if ( time() > $this->_session_expiring ) {
				$this->set_session_expiration();
				$this->update_session_timestamp( $this->_customer_id, $this->_session_expiration );
			}
		} else {
			$this->set_session_expiration();
			$this->_customer_id = $this->generate_customer_id();
			$this->_data        = $this->get_session_data();
		}
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
	}

	/**
	 * Checks if session cookie is expired, or belongs to a logged out user.
	 *
	 * @return bool Whether session cookie is valid.
	 */
	private function is_session_cookie_valid() {
		// If session is expired, session cookie is invalid.
		if ( time() > $this->_session_expiration ) {
			return false;
		}

		// If user has logged out, session cookie is invalid.
		if ( ! is_user_logged_in() && ! $this->is_customer_guest( $this->_customer_id ) ) {
			return false;
		}

		// Session from a different user is not valid. (Although from a guest user will be valid).
		if ( is_user_logged_in() && ! $this->is_customer_guest( $this->_customer_id ) && strval( get_current_user_id() ) !== $this->_customer_id ) {
			return false;
		}

		return true;
	}

	/**
	 * Hooks into the wp action to maybe set the session cookie if the user is on a certain page e.g. a checkout endpoint.
	 *
	 * Certain gateways may rely on sessions and this ensures a session is present even if the customer does not have a
	 * cart.
	 */
	public function maybe_set_customer_session_cookie() {
		if ( is_wc_endpoint_url( 'order-pay' ) ) {
			$this->set_customer_session_cookie( true );
		}
	}

	/**
<<<<<<< HEAD
	 * Hash a value using wp_fast_hash (from WP 6.8 onwards).
	 *
	 * This method can be removed when the minimum version supported is 6.8.
	 *
	 * @param string $message Value to hash.
	 * @return string Hashed value.
	 */
	private function hash( $message ) {
		if ( function_exists( 'wp_fast_hash' ) ) {
			return wp_fast_hash( $message );
		}
		return hash_hmac( 'md5', $message, wp_hash( $message ) );
	}

	/**
	 * Verify a hash using wp_verify_fast_hash (from WP 6.8 onwards).
	 *
	 * This method can be removed when the minimum version supported is 6.8.
	 *
	 * @param string $message Message to verify.
	 * @param string $hash Hash to verify.
	 * @return bool Whether the hash is valid.
	 */
	private function verify_hash( $message, $hash ) {
		if ( function_exists( 'wp_verify_fast_hash' ) ) {
			return wp_verify_fast_hash( $message, $hash );
		}
		return hash_equals( hash_hmac( 'md5', $message, wp_hash( $message ) ), $hash );
	}

	/**
=======
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
	 * Sets the session cookie on-demand (usually after adding an item to the cart).
	 *
	 * Since the cookie name (as of 2.1) is prepended with wp, cache systems like batcache will not cache pages when set.
	 *
	 * Warning: Cookies will only be set if this is called before the headers are sent.
	 *
	 * @param bool $set Should the session cookie be set.
	 */
	public function set_customer_session_cookie( $set ) {
		if ( $set ) {
<<<<<<< HEAD
			$cookie_hash  = $this->hash( $this->_customer_id . '|' . $this->_session_expiration );
			$cookie_value = $this->_customer_id . '|' . $this->_session_expiration . '|' . $this->_session_expiring . '|' . $cookie_hash;
=======
			$to_hash           = $this->_customer_id . '|' . $this->_session_expiration;
			$cookie_hash       = hash_hmac( 'md5', $to_hash, wp_hash( $to_hash ) );
			$cookie_value      = $this->_customer_id . '||' . $this->_session_expiration . '||' . $this->_session_expiring . '||' . $cookie_hash;
			$this->_has_cookie = true;
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)

			if ( ! isset( $_COOKIE[ $this->_cookie ] ) || $_COOKIE[ $this->_cookie ] !== $cookie_value ) {
				wc_setcookie( $this->_cookie, $cookie_value, $this->_session_expiration, $this->use_secure_cookie(), true );
			}
<<<<<<< HEAD

			$this->_has_cookie = true;
=======
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
		}
	}

	/**
	 * Should the session cookie be secure?
	 *
	 * @since 3.6.0
	 * @return bool
	 */
	protected function use_secure_cookie() {
<<<<<<< HEAD
		/**
		 * Filter whether to use a secure cookie.
		 *
		 * @since 3.6.0
		 *
		 * @param bool $use_secure_cookie Whether to use a secure cookie.
		 */
=======
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
		return apply_filters( 'wc_session_use_secure_cookie', wc_site_is_https() && is_ssl() );
	}

	/**
	 * Return true if the current user has an active session, i.e. a cookie to retrieve values.
	 *
	 * @return bool
	 */
	public function has_session() {
<<<<<<< HEAD
		return isset( $_COOKIE[ $this->_cookie ] ) || $this->_has_cookie || is_user_logged_in();
	}

	/**
	 * Checks if the session is expiring.
	 *
	 * @return bool Whether session is expiring.
	 */
	private function is_session_expiring() {
		return time() > $this->_session_expiring;
=======
		return isset( $_COOKIE[ $this->_cookie ] ) || $this->_has_cookie || is_user_logged_in(); // @codingStandardsIgnoreLine.
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
	}

	/**
	 * Set session expiration.
	 */
	public function set_session_expiration() {
<<<<<<< HEAD
		$expiring_seconds   = DAY_IN_SECONDS;
		$expiration_seconds = 2 * DAY_IN_SECONDS;

		/**
		 * Filters the session expiration.
		 *
		 * @since 5.0.0
		 * @param int $expiration_seconds The expiration time in seconds.
		 */
		$this->_session_expiring = time() + intval( apply_filters( 'wc_session_expiring', $expiring_seconds ) );

		/**
		 * Filters the session expiration.
		 *
		 * @since 5.0.0
		 * @param int $expiration_seconds The expiration time in seconds.
		 */
		$this->_session_expiration = time() + intval( apply_filters( 'wc_session_expiration', $expiration_seconds ) );
=======
		$this->_session_expiring   = time() + intval( apply_filters( 'wc_session_expiring', 60 * 60 * 47 ) ); // 47 Hours.
		$this->_session_expiration = time() + intval( apply_filters( 'wc_session_expiration', 60 * 60 * 48 ) ); // 48 Hours.
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
	}

	/**
	 * Generate a unique customer ID for guests, or return user ID if logged in.
	 *
<<<<<<< HEAD
	 * @return string
	 */
	public function generate_customer_id() {
		return is_user_logged_in() ? strval( get_current_user_id() ) : wc_rand_hash( 't_', 30 );
=======
	 * Uses Portable PHP password hashing framework to generate a unique cryptographically strong ID.
	 *
	 * @return string
	 */
	public function generate_customer_id() {
		$customer_id = '';

		if ( is_user_logged_in() ) {
			$customer_id = strval( get_current_user_id() );
		}

		if ( empty( $customer_id ) ) {
			require_once ABSPATH . 'wp-includes/class-phpass.php';
			$hasher      = new PasswordHash( 8, false );
			$customer_id = 't_' . substr( md5( $hasher->get_random_bytes( 32 ) ), 2 );
		}

		return $customer_id;
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
	}

	/**
	 * Checks if this is an auto-generated customer ID.
	 *
<<<<<<< HEAD
	 * @param string $customer_id Customer ID to check.
	 * @return bool Whether customer ID is randomly generated.
	 */
	private function is_customer_guest( $customer_id ) {
		return empty( $customer_id ) || 't_' === substr( $customer_id, 0, 2 );
=======
	 * @param string|int $customer_id Customer ID to check.
	 *
	 * @return bool Whether customer ID is randomly generated.
	 */
	private function is_customer_guest( $customer_id ) {
		$customer_id = strval( $customer_id );

		if ( empty( $customer_id ) ) {
			return true;
		}

		if ( 't_' === substr( $customer_id, 0, 2 ) ) {
			return true;
		}

		/**
		 * Legacy checks. This is to handle sessions that were created from a previous release.
		 * Maybe we can get rid of them after a few releases.
		 */

		// Almost all random $customer_ids will have some letters in it, while all actual ids will be integers.
		if ( strval( (int) $customer_id ) !== $customer_id ) {
			return true;
		}

		// Performance hack to potentially save a DB query, when same user as $customer_id is logged in.
		if ( is_user_logged_in() && strval( get_current_user_id() ) === $customer_id ) {
			return false;
		} else {
			$customer = new WC_Customer( $customer_id );

			if ( 0 === $customer->get_id() ) {
				return true;
			}
		}

		return false;
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
	}

	/**
	 * Get session unique ID for requests if session is initialized or user ID if logged in.
	 * Introduced to help with unit tests.
	 *
	 * @since 5.3.0
	 * @return string
	 */
	public function get_customer_unique_id() {
		$customer_id = '';

		if ( $this->has_session() && $this->_customer_id ) {
			$customer_id = $this->_customer_id;
		} elseif ( is_user_logged_in() ) {
			$customer_id = (string) get_current_user_id();
		}

		return $customer_id;
	}

	/**
	 * Get the session cookie, if set. Otherwise return false.
	 *
	 * Session cookies without a customer ID are invalid.
	 *
	 * @return bool|array
	 */
	public function get_session_cookie() {
<<<<<<< HEAD
		$cookie_value = isset( $_COOKIE[ $this->_cookie ] ) ? wc_clean( wp_unslash( (string) $_COOKIE[ $this->_cookie ] ) ) : '';

		if ( empty( $cookie_value ) ) {
			return false;
		}

		// Check if the cookie value contains '||' instead of '|' to support older versions of the cookie. This can be removed in WC 11.0.0.
		if ( strpos( $cookie_value, '||' ) !== false ) {
			$parsed_cookie = explode( '||', $cookie_value );
		} else {
			$parsed_cookie = explode( '|', $cookie_value );
		}

		if ( count( $parsed_cookie ) !== 4 ) {
=======
		$cookie_value = isset( $_COOKIE[ $this->_cookie ] ) ? wp_unslash( $_COOKIE[ $this->_cookie ] ) : false; // @codingStandardsIgnoreLine.

		if ( empty( $cookie_value ) || ! is_string( $cookie_value ) ) {
			return false;
		}

		$parsed_cookie = explode( '||', $cookie_value );

		if ( count( $parsed_cookie ) < 4 ) {
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
			return false;
		}

		list( $customer_id, $session_expiration, $session_expiring, $cookie_hash ) = $parsed_cookie;

		if ( empty( $customer_id ) ) {
			return false;
		}

<<<<<<< HEAD
		$verify_hash = $this->verify_hash( $customer_id . '|' . $session_expiration, $cookie_hash );

		if ( ! $verify_hash ) {
=======
		// Validate hash.
		$to_hash = $customer_id . '|' . $session_expiration;
		$hash    = hash_hmac( 'md5', $to_hash, wp_hash( $to_hash ) );

		if ( empty( $cookie_hash ) || ! hash_equals( $hash, $cookie_hash ) ) {
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
			return false;
		}

		return array( $customer_id, $session_expiration, $session_expiring, $cookie_hash );
	}

	/**
	 * Get session data.
	 *
	 * @return array
	 */
	public function get_session_data() {
		return $this->has_session() ? (array) $this->get_session( $this->_customer_id, array() ) : array();
	}

	/**
	 * Gets a cache prefix. This is used in session names so the entire cache can be invalidated with 1 function call.
	 *
	 * @return string
	 */
	private function get_cache_prefix() {
		return WC_Cache_Helper::get_cache_prefix( WC_SESSION_CACHE_GROUP );
	}

	/**
	 * Save data and delete guest session.
	 *
<<<<<<< HEAD
	 * @param string|mixed $old_session_key Optional session ID prior to user log-in.  If $old_session_key is not tied
	 *                                      to a user, the session will be deleted with the assumption that it was migrated
	 *                                      to the current session being saved.
	 */
	public function save_data( $old_session_key = '' ) {
=======
	 * @param int $old_session_key session ID before user logs in.
	 */
	public function save_data( $old_session_key = 0 ) {
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
		// Dirty if something changed - prevents saving nothing new.
		if ( $this->_dirty && $this->has_session() ) {
			global $wpdb;

			$wpdb->query(
				$wpdb->prepare(
<<<<<<< HEAD
					'INSERT INTO %i (`session_key`, `session_value`, `session_expiry`) VALUES (%s, %s, %d)
 					ON DUPLICATE KEY UPDATE `session_value` = VALUES(`session_value`), `session_expiry` = VALUES(`session_expiry`)',
					$this->_table,
=======
					// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
					"INSERT INTO $this->_table (`session_key`, `session_value`, `session_expiry`) VALUES (%s, %s, %d)
 					ON DUPLICATE KEY UPDATE `session_value` = VALUES(`session_value`), `session_expiry` = VALUES(`session_expiry`)",
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
					$this->_customer_id,
					maybe_serialize( $this->_data ),
					$this->_session_expiration
				)
			);
<<<<<<< HEAD
			wp_cache_set( $this->get_cache_prefix() . $this->_customer_id, $this->_data, WC_SESSION_CACHE_GROUP, $this->_session_expiration - time() );
			$this->_dirty = false;

			/**
			 * Ideally, the removal of guest session data migrated to a logged-in user would occur within
			 * self::init_session_cookie() upon user login detection initially occurs. However, since some third-party
			 * extensions override this method, relocating this logic could break backward compatibility.
			 */
			if ( ! empty( $old_session_key ) && $this->_customer_id !== $old_session_key && ! is_object( get_user_by( 'id', $old_session_key ) ) ) {
=======

			wp_cache_set( $this->get_cache_prefix() . $this->_customer_id, $this->_data, WC_SESSION_CACHE_GROUP, $this->_session_expiration - time() );
			$this->_dirty = false;
			if ( get_current_user_id() != $old_session_key && ! is_object( get_user_by( 'id', $old_session_key ) ) ) {
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
				$this->delete_session( $old_session_key );
			}
		}
	}

	/**
	 * Destroy all session data.
	 */
	public function destroy_session() {
		$this->delete_session( $this->_customer_id );
		$this->forget_session();
<<<<<<< HEAD
		$this->set_session_expiration();
=======
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
	}

	/**
	 * Forget all session data without destroying it.
	 */
	public function forget_session() {
		wc_setcookie( $this->_cookie, '', time() - YEAR_IN_SECONDS, $this->use_secure_cookie(), true );

		if ( ! is_admin() ) {
			include_once WC_ABSPATH . 'includes/wc-cart-functions.php';
<<<<<<< HEAD
=======

>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
			wc_empty_cart();
		}

		$this->_data        = array();
		$this->_dirty       = false;
		$this->_customer_id = $this->generate_customer_id();
<<<<<<< HEAD
		$this->_has_cookie  = false;
=======
	}

	/**
	 * When a user is logged out, ensure they have a unique nonce by using the customer/session ID.
	 *
	 * @deprecated 5.3.0
	 * @param int $uid User ID.
	 * @return int|string
	 */
	public function nonce_user_logged_out( $uid ) {
		wc_deprecated_function( 'WC_Session_Handler::nonce_user_logged_out', '5.3', 'WC_Session_Handler::maybe_update_nonce_user_logged_out' );

		return $this->has_session() && $this->_customer_id ? $this->_customer_id : $uid;
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
	}

	/**
	 * When a user is logged out, ensure they have a unique nonce to manage cart and more using the customer/session ID.
	 * This filter runs everything `wp_verify_nonce()` and `wp_create_nonce()` gets called.
	 *
	 * @since 5.3.0
	 * @param int    $uid    User ID.
	 * @param string $action The nonce action.
	 * @return int|string
	 */
	public function maybe_update_nonce_user_logged_out( $uid, $action ) {
<<<<<<< HEAD
		if ( StringUtil::starts_with( $action, 'woocommerce' ) ) {
			return $this->has_session() && $this->_customer_id ? $this->_customer_id : $uid;
		}
=======
		if ( Automattic\WooCommerce\Utilities\StringUtil::starts_with( $action, 'woocommerce' ) ) {
			return $this->has_session() && $this->_customer_id ? $this->_customer_id : $uid;
		}

>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
		return $uid;
	}

	/**
	 * Cleanup session data from the database and clear caches.
	 */
	public function cleanup_sessions() {
		global $wpdb;

<<<<<<< HEAD
		$wpdb->query( $wpdb->prepare( 'DELETE FROM %i WHERE session_expiry < %d', $this->_table, time() ) );
=======
		$wpdb->query( $wpdb->prepare( "DELETE FROM $this->_table WHERE session_expiry < %d", time() ) ); // @codingStandardsIgnoreLine.
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)

		if ( class_exists( 'WC_Cache_Helper' ) ) {
			WC_Cache_Helper::invalidate_cache_group( WC_SESSION_CACHE_GROUP );
		}
	}

	/**
	 * Returns the session.
	 *
	 * @param string $customer_id Customer ID.
<<<<<<< HEAD
	 * @param mixed  $default_value Default session value.
	 * @return string|array
	 */
	public function get_session( $customer_id, $default_value = false ) {
=======
	 * @param mixed  $default Default session value.
	 * @return string|array
	 */
	public function get_session( $customer_id, $default = false ) {
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
		global $wpdb;

		if ( Constants::is_defined( 'WP_SETUP_CONFIG' ) ) {
			return false;
		}

		// Try to get it from the cache, it will return false if not present or if object cache not in use.
		$value = wp_cache_get( $this->get_cache_prefix() . $customer_id, WC_SESSION_CACHE_GROUP );

		if ( false === $value ) {
<<<<<<< HEAD
			$value = $wpdb->get_var( $wpdb->prepare( 'SELECT session_value FROM %i WHERE session_key = %s', $this->_table, $customer_id ) );

			if ( is_null( $value ) ) {
				$value = $default_value;
=======
			$value = $wpdb->get_var( $wpdb->prepare( "SELECT session_value FROM $this->_table WHERE session_key = %s", $customer_id ) ); // @codingStandardsIgnoreLine.

			if ( is_null( $value ) ) {
				$value = $default;
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
			}

			$cache_duration = $this->_session_expiration - time();
			if ( 0 < $cache_duration ) {
				wp_cache_add( $this->get_cache_prefix() . $customer_id, $value, WC_SESSION_CACHE_GROUP, $cache_duration );
			}
		}

		return maybe_unserialize( $value );
	}

	/**
	 * Delete the session from the cache and database.
	 *
	 * @param int $customer_id Customer ID.
	 */
	public function delete_session( $customer_id ) {
<<<<<<< HEAD
		$GLOBALS['wpdb']->delete( $this->_table, array( 'session_key' => $customer_id ) );
		wp_cache_delete( $this->get_cache_prefix() . $customer_id, WC_SESSION_CACHE_GROUP );
=======
		global $wpdb;

		wp_cache_delete( $this->get_cache_prefix() . $customer_id, WC_SESSION_CACHE_GROUP );

		$wpdb->delete(
			$this->_table,
			array(
				'session_key' => $customer_id,
			)
		);
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
	}

	/**
	 * Update the session expiry timestamp.
	 *
	 * @param string $customer_id Customer ID.
	 * @param int    $timestamp Timestamp to expire the cookie.
	 */
	public function update_session_timestamp( $customer_id, $timestamp ) {
<<<<<<< HEAD
		$GLOBALS['wpdb']->update( $this->_table, array( 'session_expiry' => $timestamp ), array( 'session_key' => $customer_id ), array( '%d' ) );
	}

	/**
	 * Check if a session exists in the database.
	 *
	 * @param string $customer_id Customer ID.
	 * @return bool
	 */
	private function session_exists( $customer_id ) {
		return null !== $GLOBALS['wpdb']->get_var( $GLOBALS['wpdb']->prepare( 'SELECT session_key FROM %i WHERE session_key = %s', $this->_table, $customer_id ) );
=======
		global $wpdb;

		$wpdb->update(
			$this->_table,
			array(
				'session_expiry' => $timestamp,
			),
			array(
				'session_key' => $customer_id,
			),
			array(
				'%d',
			)
		);
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
	}
}
