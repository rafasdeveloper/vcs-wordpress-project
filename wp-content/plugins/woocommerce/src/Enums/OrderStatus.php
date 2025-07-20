<?php

declare( strict_types = 1 );

namespace Automattic\WooCommerce\Enums;

/**
 * Enum class for all the order statuses.
 *
 * For a full documentation on the public order statuses, please refer to the following link:
 * https://woocommerce.com/document/managing-orders/order-statuses/
 */
final class OrderStatus {
	/**
	 * The order has been received, but no payment has been made.
	 *
	 * @var string
	 */
<<<<<<< HEAD
	public const PENDING = 'pending';
=======
	const PENDING = 'pending';
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)

	/**
	 * The customer’s payment failed or was declined, and no payment has been successfully made.
	 *
	 * @var string
	 */
<<<<<<< HEAD
	public const FAILED = 'failed';
=======
	const FAILED = 'failed';
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)

	/**
	 * The order is awaiting payment confirmation.
	 *
	 * @var string
	 */
<<<<<<< HEAD
	public const ON_HOLD = 'on-hold';
=======
	const ON_HOLD = 'on-hold';
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)

	/**
	 * Order fulfilled and complete.
	 *
	 * @var string
	 */
<<<<<<< HEAD
	public const COMPLETED = 'completed';
=======
	const COMPLETED = 'completed';
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)

	/**
	 * Payment has been received (paid), and the stock has been reduced.
	 *
	 * @var string
	 */
<<<<<<< HEAD
	public const PROCESSING = 'processing';
=======
	const PROCESSING = 'processing';
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)

	/**
	 * Orders are automatically put in the Refunded status when an admin or shop manager has fully refunded the order’s value after payment.
	 *
	 * @var string
	 */
<<<<<<< HEAD
	public const REFUNDED = 'refunded';
=======
	const REFUNDED = 'refunded';
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)

	/**
	 * The order was canceled by an admin or the customer.
	 *
	 * @var string
	 */
<<<<<<< HEAD
	public const CANCELLED = 'cancelled';
=======
	const CANCELLED = 'cancelled';
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)

	/**
	 * The order is in the trash.
	 *
	 * @var string
	 */
<<<<<<< HEAD
	public const TRASH = 'trash';
=======
	const TRASH = 'trash';
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)

	/**
	 * The order is a draft (legacy status).
	 *
	 * @var string
	 */
<<<<<<< HEAD
	public const NEW = 'new';
=======
	const NEW = 'new';
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)

	/**
	 * The order is an automatically generated draft.
	 *
	 * @var string
	 */
<<<<<<< HEAD
	public const AUTO_DRAFT = 'auto-draft';
=======
	const AUTO_DRAFT = 'auto-draft';
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)

	/**
	 * Draft orders are created when customers start the checkout process while the block version of the checkout is in place.
	 *
	 * @var string
	 */
<<<<<<< HEAD
	public const DRAFT = 'draft';
=======
	const DRAFT = 'draft';
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
}
