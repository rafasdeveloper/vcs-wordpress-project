<?php

declare( strict_types = 1 );

namespace Automattic\WooCommerce\Enums;

/**
 * Enum class for all the product tax statuses.
 */
class ProductTaxStatus {
	/**
	 * Tax status for products that are taxable.
	 *
	 * @var string
	 */
<<<<<<< HEAD
	public const TAXABLE = 'taxable';
=======
	const TAXABLE = 'taxable';
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)

	/**
	 * Indicates that only the shipping cost should be taxed, not the product itself.
	 *
	 * @var string
	 */
<<<<<<< HEAD
	public const SHIPPING = 'shipping';
=======
	const SHIPPING = 'shipping';
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)

	/**
	 * Tax status for products that are not taxable.
	 *
	 * @var string
	 */
<<<<<<< HEAD
	public const NONE = 'none';
=======
	const NONE = 'none';
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
}
