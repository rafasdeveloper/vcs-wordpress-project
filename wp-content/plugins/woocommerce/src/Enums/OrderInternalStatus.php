<?php

declare( strict_types = 1 );

namespace Automattic\WooCommerce\Enums;

/**
 * Enum class for all the internal order statuses.
 * These statuses are used internally by WooCommerce to query database directly.
 */
final class OrderInternalStatus {
	/**
	 * The order is pending payment.
	 *
	 * @var string
	 */
<<<<<<< HEAD
	public const PENDING = 'wc-pending';
=======
	const PENDING = 'wc-pending';
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)

	/**
	 * The order is processing.
	 *
	 * @var string
	 */
<<<<<<< HEAD
	public const PROCESSING = 'wc-processing';
=======
	const PROCESSING = 'wc-processing';
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)

	/**
	 * The order is on hold.
	 *
	 * @var string
	 */
<<<<<<< HEAD
	public const ON_HOLD = 'wc-on-hold';
=======
	const ON_HOLD = 'wc-on-hold';
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)

	/**
	 * The order is completed.
	 *
	 * @var string
	 */
<<<<<<< HEAD
	public const COMPLETED = 'wc-completed';
=======
	const COMPLETED = 'wc-completed';
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)

	/**
	 * The order is cancelled.
	 *
	 * @var string
	 */
<<<<<<< HEAD
	public const CANCELLED = 'wc-cancelled';
=======
	const CANCELLED = 'wc-cancelled';
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)

	/**
	 * The order is refunded.
	 *
	 * @var string
	 */
<<<<<<< HEAD
	public const REFUNDED = 'wc-refunded';
=======
	const REFUNDED = 'wc-refunded';
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)

	/**
	 * The order is failed.
	 *
	 * @var string
	 */
<<<<<<< HEAD
	public const FAILED = 'wc-failed';
=======
	const FAILED = 'wc-failed';
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
}
