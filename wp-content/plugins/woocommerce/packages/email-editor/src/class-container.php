<?php
/**
<<<<<<< HEAD
 * This file is part of the WooCommerce Email Editor package
 *
 * @package Automattic\WooCommerce\EmailEditor
 */

declare( strict_types = 1 );
namespace Automattic\WooCommerce\EmailEditor;
=======
 * This file is part of the MailPoet plugin.
 *
 * @package MailPoet\EmailEditor
 */

declare( strict_types = 1 );
namespace MailPoet\EmailEditor;
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)

/**
 * Class Container is a simple dependency injection container.
 *
<<<<<<< HEAD
 * @package Automattic\WooCommerce\EmailEditor
=======
 * @package MailPoet\EmailEditor
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
 */
class Container {
	/**
	 * A list of registered services
	 *
<<<<<<< HEAD
	 * @var array<string, callable> $services
=======
	 * @var array $services
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
	 */
	protected array $services = array();

	/**
	 * A list of created instances
	 *
<<<<<<< HEAD
	 * @var array<string, object> $instances
=======
	 * @var array
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
	 */
	protected array $instances = array();

	/**
<<<<<<< HEAD
	 * The method for registering a new service.
	 *
	 * @param string   $name     The name of the service.
	 * @param callable $callback The callable that will be used to create the service.
	 * @return void
	 * @phpstan-template T of object
	 * @phpstan-param class-string<T> $name
=======
	 * The method for registering a new service
	 *
	 * @param string   $name    The name of the service.
	 * @param callable $callback The callable that will be used to create the service.
	 * @return void
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
	 */
	public function set( string $name, callable $callback ): void {
		$this->services[ $name ] = $callback;
	}

	/**
<<<<<<< HEAD
	 * Method for getting a registered service.
	 *
	 * @param string $name The name of the service.
	 * @return object The service instance.
	 * @throws \Exception If the service is not found.
	 * @phpstan-template T of object
	 * @phpstan-param class-string<T> $name
	 * @phpstan-return T
	 */
	public function get( string $name ): object {
		// Check if the service is already instantiated.
		if ( isset( $this->instances[ $name ] ) ) {
			/**
			 * Instance.
			 *
			 * @var T $instance Instance of requested service.
			 */
			$instance = $this->instances[ $name ];
			return $instance;
=======
	 * Method for getting a registered service
	 *
	 * @template T
	 * @param class-string<T> $name The name of the service.
	 * @return T
	 * @throws \Exception If the service is not found.
	 */
	public function get( $name ) {
		// Check if the service is already instantiated.
		if ( isset( $this->instances[ $name ] ) ) {
			return $this->instances[ $name ];
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
		}

		// Check if the service is registered.
		if ( ! isset( $this->services[ $name ] ) ) {
			throw new \Exception( esc_html( "Service not found: $name" ) );
		}

<<<<<<< HEAD
		/**
		 * Instance.
		 *
		 * @var T $instance Instance of requested service.
		 */
		$instance                 = $this->services[ $name ]( $this );
		$this->instances[ $name ] = $instance;

		return $instance;
=======
		$this->instances[ $name ] = $this->services[ $name ]( $this );

		return $this->instances[ $name ];
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
	}
}
