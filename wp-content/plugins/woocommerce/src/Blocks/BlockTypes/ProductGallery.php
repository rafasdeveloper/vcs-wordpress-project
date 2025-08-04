<?php
declare( strict_types=1 );
namespace Automattic\WooCommerce\Blocks\BlockTypes;

use Automattic\WooCommerce\Blocks\Utils\ProductGalleryUtils;
use Automattic\WooCommerce\Blocks\Utils\StyleAttributesUtils;
use Automattic\WooCommerce\Enums\ProductType;

/**
 * ProductGallery class.
 */
class ProductGallery extends AbstractBlock {
<<<<<<< HEAD

	use EnableBlockJsonAssetsTrait;

=======
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
	/**
	 * Block name.
	 *
	 * @var string
	 */
	protected $block_name = 'product-gallery';

	/**
	 *  Register the context
	 *
	 * @return string[]
	 */
	protected function get_block_type_uses_context() {
		return [ 'postId' ];
	}

	/**
	 * Return the dialog content.
	 *
<<<<<<< HEAD
	 * @param array $images An array of all images of the product.
	 * @return string
	 */
	protected function render_dialog( $images ) {
		$images_html = '';
		foreach ( $images as $index => $image ) {
			$id           = $image['id'];
			$src          = $image['src'];
			$srcset       = $image['srcset'];
			$sizes        = $image['sizes'];
			$alt          = $image['alt'];
			$loading      = 0 === $index ? 'fetchpriority="high"' : 'loading="lazy"';
			$images_html .= "<img data-image-id='{$id}' src='{$src}' srcset='{$srcset}' sizes='{$sizes}' loading='{$loading}' decoding='async' alt='{$alt}' />";
		}
		ob_start();
		?>
			<dialog
				data-wp-bind--open="context.isDialogOpen"
				data-wp-bind--inert="!context.isDialogOpen"
=======
	 * @param array $product_gallery_full_images The full images of the product gallery.
	 * @return string
	 */
	protected function render_dialog( $product_gallery_full_images ) {
		$images_html = '';
		foreach ( $product_gallery_full_images as $index => $image ) {
			$image_number = $index + 1;
			$images_html .= str_replace( '<img', '<img tabindex="0" data-image-index="' . $image_number . '"', $image );
		}

		return sprintf(
			'<dialog
				data-wp-ref
				data-wp-bind--open="context.isDialogOpen"
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
				data-wp-on--close="actions.closeDialog"
				data-wp-on--keydown="actions.onDialogKeyDown"
				data-wp-watch="callbacks.dialogStateChange"
				class="wc-block-product-gallery-dialog"
				role="dialog"
				aria-modal="true"
<<<<<<< HEAD
				aria-label="Product Gallery">
				<div class="wc-block-product-gallery-dialog__content">
					<button class="wc-block-product-gallery-dialog__close-button" data-wp-on--click="actions.closeDialog" aria-label="<?php echo esc_attr__( 'Close dialog', 'woocommerce' ); ?>">
=======
				tabindex="-1"
				aria-label="Product Gallery">
				<div class="wc-block-product-gallery-dialog__content">
					<button class="wc-block-product-gallery-dialog__close-button" data-wp-on--click="actions.closeDialog" aria-label="%s">
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false">
							<path d="M13 11.8l6.1-6.3-1-1-6.1 6.2-6.1-6.2-1 1 6.1 6.3-6.5 6.7 1 1 6.5-6.6 6.5 6.6 1-1z"></path>
						</svg>
					</button>
					<div class="wc-block-product-gallery-dialog__images-container">
						<div class="wc-block-product-gallery-dialog__images">
<<<<<<< HEAD
							<?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Output is already escaped by WooCommerce. ?>
							<?php echo $images_html; ?>
						</div>
					</div>
				</div>
			</dialog>
		<?php
		return ob_get_clean();
=======
							%s
						</div>
					</div>
				</div>
			</dialog>',
			esc_attr__( 'Close dialog', 'woocommerce' ),
			$images_html
		);
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
	}

	/**
	 * Inject dialog into the product gallery HTML.
	 *
	 * @param string $gallery_html The gallery HTML.
	 * @param string $dialog_html  The dialog HTML.
	 *
	 * @return string
	 */
	protected function inject_dialog( $gallery_html, $dialog_html ) {

		// Find the position of the last </div>.
		$pos = strrpos( $gallery_html, '</div>' );

		if ( false !== $pos ) {
			// Inject the dialog_html at the correct position.
			$html = substr_replace( $gallery_html, $dialog_html, $pos, 0 );

			return $html;
		}
	}

	/**
	 * Include and render the block.
	 *
	 * @param array    $attributes Block attributes. Default empty array.
	 * @param string   $content    Block content. Default empty string.
	 * @param WP_Block $block      Block instance.
	 * @return string Rendered block type output.
	 */
	protected function render( $attributes, $content, $block ) {
		$post_id = $block->context['postId'] ?? '';
		$product = wc_get_product( $post_id );

		if ( ! $product instanceof \WC_Product ) {
			return '';
		}

<<<<<<< HEAD
		$image_ids              = ProductGalleryUtils::get_all_image_ids( $product );
		$classname              = StyleAttributesUtils::get_classes_by_attributes( $attributes, array( 'extra_classes' ) );
		$initial_image_id       = count( $image_ids ) > 0 ? $image_ids[0] : -1;
		$classname_single_image = count( $image_ids ) < 2 ? 'is-single-product-gallery-image' : '';
		$product_id             = strval( $product->get_id() );
		$fullsize_image_data    = ProductGalleryUtils::get_image_src_data( $image_ids, 'full', $product->get_title() );
		$gallery_with_dialog    = $this->inject_dialog( $content, $this->render_dialog( $fullsize_image_data ) );
		$p                      = new \WP_HTML_Tag_Processor( $gallery_with_dialog );
=======
		wp_enqueue_script_module( $this->get_full_block_name() );

		$product_gallery_thumbnail_images = ProductGalleryUtils::get_product_gallery_images( $post_id, 'thumbnail', array() );
		$product_gallery_full_images      = ProductGalleryUtils::get_product_gallery_images( $post_id, 'full', array() );
		$classname_single_image           = '';

		if ( count( $product_gallery_thumbnail_images ) < 2 ) {
			// The gallery consists of a single image.
			$classname_single_image = 'is-single-product-gallery-image';
		}

		$classname           = StyleAttributesUtils::get_classes_by_attributes( $attributes, array( 'extra_classes' ) );
		$product_id          = strval( $product->get_id() );
		$gallery_with_dialog = $this->inject_dialog( $content, $this->render_dialog( $product_gallery_full_images ) );
		$p                   = new \WP_HTML_Tag_Processor( $gallery_with_dialog );
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)

		if ( $p->next_tag() ) {
			$p->set_attribute( 'data-wp-interactive', $this->get_full_block_name() );
			$p->set_attribute(
				'data-wp-context',
				wp_json_encode(
					array(
<<<<<<< HEAD
						'imageData'          => $image_ids,
						'isDialogOpen'       => false,
						'disableLeft'        => true,
						'disableRight'       => false,
						'isDragging'         => false,
						'touchStartX'        => 0,
						'touchCurrentX'      => 0,
						'productId'          => $product_id,
						'selectedImageId'    => $initial_image_id,
						'thumbnailsOverflow' => [
							'top'    => false,
							'bottom' => false,
							'left'   => false,
							'right'  => false,
						],
=======
						'selectedImageNumber' => 1,
						'isDialogOpen'        => false,
						'disableLeft'         => true,
						'disableRight'        => false,
						'isDragging'          => false,
						'touchStartX'         => 0,
						'touchCurrentX'       => 0,
						'productId'           => $product_id,
						'imageIds'            => ProductGalleryUtils::get_product_gallery_image_ids( $product, null, false ),
            'styles'                 => array(
							'transform'        => 'scale(1.0)',
							'transform-origin' => '',
						),
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
					),
					JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP
				)
			);

			if ( $product->is_type( ProductType::VARIABLE ) ) {
				$p->set_attribute( 'data-wp-init--watch-changes-on-add-to-cart-form', 'callbacks.watchForChangesOnAddToCartForm' );
			}

			$p->add_class( $classname );
			$p->add_class( $classname_single_image );
			$html = $p->get_updated_html();
		}

		return $html;
	}
<<<<<<< HEAD
=======

	/**
	 * Disable the block type script, this uses script modules.
	 *
	 * @param string|null $key The key.
	 *
	 * @return null
	 */
	protected function get_block_type_script( $key = null ) {
		return null;
	}
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
}
