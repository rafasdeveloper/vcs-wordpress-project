<?php

declare( strict_types=1 );

namespace Automattic\WooCommerce\Internal\EmailEditor;

<<<<<<< HEAD
use Automattic\WooCommerce\EmailEditor\Engine\PersonalizationTags\Personalization_Tags_Registry;
use Automattic\WooCommerce\Internal\EmailEditor\PersonalizationTags\CustomerTagsProvider;
use Automattic\WooCommerce\Internal\EmailEditor\PersonalizationTags\OrderTagsProvider;
use Automattic\WooCommerce\Internal\EmailEditor\PersonalizationTags\SiteTagsProvider;
use Automattic\WooCommerce\Internal\EmailEditor\PersonalizationTags\StoreTagsProvider;
=======
use MailPoet\EmailEditor\Engine\PersonalizationTags\Personalization_Tag;
use MailPoet\EmailEditor\Engine\PersonalizationTags\Personalization_Tags_Registry;
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)

defined( 'ABSPATH' ) || exit;

/**
 * Manages personalization tags for WooCommerce emails.
 *
 * @internal
 */
class PersonalizationTagManager {

	/**
<<<<<<< HEAD
	 * The customer related tags provider.
	 *
	 * @var CustomerTagsProvider
	 */
	private $customer_tags_provider;

	/**
	 * The order related tags provider.
	 *
	 * @var OrderTagsProvider
	 */
	private $order_tags_provider;

	/**
	 * The site related tags provider.
	 *
	 * @var SiteTagsProvider
	 */
	private $site_tags_provider;

	/**
	 * The store related tags provider.
	 *
	 * @var StoreTagsProvider
	 */
	private $store_tags_provider;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->customer_tags_provider = new CustomerTagsProvider();
		$this->order_tags_provider    = new OrderTagsProvider();
		$this->site_tags_provider     = new SiteTagsProvider();
		$this->store_tags_provider    = new StoreTagsProvider();
	}

	/**
=======
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
	 * Initialize the personalization tag manager.
	 *
	 * @internal
	 * @return void
	 */
	final public function init(): void {
<<<<<<< HEAD
		add_filter( 'woocommerce_email_editor_register_personalization_tags', array( $this, 'register_personalization_tags' ) );
=======
		add_filter( 'mailpoet_email_editor_register_personalization_tags', array( $this, 'register_personalization_tags' ) );
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
	}

	/**
	 * Register WooCommerce personalization tags with the registry.
	 *
	 * @param Personalization_Tags_Registry $registry The personalization tags registry.
	 * @return Personalization_Tags_Registry
	 */
	public function register_personalization_tags( Personalization_Tags_Registry $registry ) {
<<<<<<< HEAD
		$this->customer_tags_provider->register_tags( $registry );
		$this->order_tags_provider->register_tags( $registry );
		$this->site_tags_provider->register_tags( $registry );
		$this->store_tags_provider->register_tags( $registry );

=======
		$registry->register(
			new Personalization_Tag(
				__( 'Shopper Email', 'woocommerce' ),
				'woocommerce/shopper-email',
				__( 'Shopper', 'woocommerce' ),
				function ( array $context ): string {
					return $context['recipient_email'] ?? '';
				},
			)
		);

		// Site Personalization Tags.
		$registry->register(
			new Personalization_Tag(
				__( 'Site Title', 'woocommerce' ),
				'woocommerce/site-title',
				__( 'Site', 'woocommerce' ),
				function (): string {
					return htmlspecialchars_decode( get_bloginfo( 'name' ) );
				},
			)
		);
		$registry->register(
			new Personalization_Tag(
				__( 'Homepage URL', 'woocommerce' ),
				'woocommerce/site-homepage-url',
				__( 'Site', 'woocommerce' ),
				function (): string {
					return get_bloginfo( 'url' );
				},
			)
		);
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
		return $registry;
	}
}
