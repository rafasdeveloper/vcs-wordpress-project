<?php
declare(strict_types=1);

namespace Automattic\WooCommerce\Blocks\Templates;

/**
 * VariableProductAddToCartWithOptionsTemplate class.
 *
 * @internal
 */
class VariableProductAddToCartWithOptionsTemplate extends AbstractTemplatePart {

	/**
	 * The slug of the template.
	 *
	 * @var string
	 */
	const SLUG = 'variable-product-add-to-cart-with-options';

	/**
	 * The template part area where the template part belongs.
	 *
	 * @var string
	 */
	public $template_area = 'add-to-cart-with-options';

	/**
	 * Initialization method.
	 */
	public function init() {
	}

	/**
	 * Returns the title of the template.
	 *
	 * @return string
	 */
	public function get_template_title() {
<<<<<<< HEAD
		return _x( 'Variable Product Add to Cart + Options', 'Template name', 'woocommerce' );
=======
		return _x( 'Variable Product Add to Cart with Options', 'Template name', 'woocommerce' );
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
	}

	/**
	 * Returns the description of the template.
	 *
	 * @return string
	 */
	public function get_template_description() {
<<<<<<< HEAD
		return __( 'Template used to display the Add to Cart + Options form for Variable Products.', 'woocommerce' );
=======
		return __( 'Template used to display the Add to Cart with Options form for Variable Products.', 'woocommerce' );
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
	}
}
