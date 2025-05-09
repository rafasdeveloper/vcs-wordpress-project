<?php

declare( strict_types = 1 );

namespace Automattic\WooCommerce\Enums;

/**
 * Enum class for all the catalog visibility values.
 */
final class CatalogVisibility {
	/**
	 * Product is visible on both shop and search results.
	 *
	 * @var string
	 */
<<<<<<< HEAD
	public const VISIBLE = 'visible';
=======
	const VISIBLE = 'visible';
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)

	/**
	 * Product is visible on the shop page only.
	 */
<<<<<<< HEAD
	public const CATALOG = 'catalog';
=======
	const CATALOG = 'catalog';
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)

	/**
	 * Product visible in the search results only.
	 */
<<<<<<< HEAD
	public const SEARCH = 'search';
=======
	const SEARCH = 'search';
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)

	/**
	 * Product is invisible on both shop and search results, but can still be accessed directly.
	 */
<<<<<<< HEAD
	public const HIDDEN = 'hidden';
=======
	const HIDDEN = 'hidden';
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
}
