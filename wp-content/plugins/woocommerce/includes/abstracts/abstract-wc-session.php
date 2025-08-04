<?php
/**
 * Handle data for the current customers session
 *
 * @class       WC_Session
 * @version     2.0.0
 * @package     WooCommerce\Abstracts
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WC_Session
 */
abstract class WC_Session {

	/**
	 * Customer ID.
	 *
<<<<<<< HEAD
	 * @var string $_customer_id Customer ID.
=======
	 * @var int $_customer_id Customer ID.
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
	 */
	protected $_customer_id;

	/**
	 * Session Data.
	 *
	 * @var array $_data Data array.
	 */
	protected $_data = array();

	/**
	 * Dirty when the session needs saving.
	 *
	 * @var bool $_dirty When something changes
	 */
	protected $_dirty = false;

	/**
	 * Init hooks and session data. Extended by child classes.
	 *
	 * @since 3.3.0
	 */
	public function init() {}

	/**
	 * Cleanup session data. Extended by child classes.
	 */
	public function cleanup_sessions() {}

	/**
	 * Magic get method.
	 *
	 * @param mixed $key Key to get.
	 * @return mixed
	 */
	public function __get( $key ) {
		return $this->get( $key );
	}

	/**
	 * Magic set method.
	 *
	 * @param mixed $key Key to set.
	 * @param mixed $value Value to set.
	 */
	public function __set( $key, $value ) {
		$this->set( $key, $value );
	}

	/**
	 * Magic isset method.
	 *
	 * @param mixed $key Key to check.
	 * @return bool
	 */
	public function __isset( $key ) {
		return isset( $this->_data[ sanitize_title( $key ) ] );
	}

	/**
	 * Magic unset method.
	 *
	 * @param mixed $key Key to unset.
	 */
	public function __unset( $key ) {
<<<<<<< HEAD
		$key = sanitize_key( $key );
=======
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
		if ( isset( $this->_data[ $key ] ) ) {
			unset( $this->_data[ $key ] );
			$this->_dirty = true;
		}
	}

	/**
	 * Get a session variable.
	 *
	 * @param string $key Key to get.
	 * @param mixed  $default used if the session variable isn't set.
	 * @return array|string value of session variable
	 */
	public function get( $key, $default = null ) {
		$key = sanitize_key( $key );
		return isset( $this->_data[ $key ] ) ? maybe_unserialize( $this->_data[ $key ] ) : $default;
	}

	/**
	 * Set a session variable.
	 *
	 * @param string $key Key to set.
	 * @param mixed  $value Value to set.
	 */
	public function set( $key, $value ) {
<<<<<<< HEAD
		if ( null === $value ) {
			$this->__unset( $key );

			return;
		}

		$key                       = sanitize_key( $key );
		$serialized_original_value = $this->_data[ $key ] ?? null;
		$serialized_value          = maybe_serialize( $value );

		if ( $serialized_original_value === $serialized_value || maybe_unserialize( $serialized_original_value ) === $value ) {
			return;
		}

		$this->_dirty        = true;
		$this->_data[ $key ] = $serialized_value;
=======
		if ( $value !== $this->get( $key ) ) {
			$this->_data[ sanitize_key( $key ) ] = maybe_serialize( $value );
			$this->_dirty                        = true;
		}
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
	}

	/**
	 * Get customer ID.
	 *
<<<<<<< HEAD
	 * @return string
=======
	 * @return int
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
	 */
	public function get_customer_id() {
		return $this->_customer_id;
	}
}
