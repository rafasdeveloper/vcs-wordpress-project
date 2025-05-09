<?php
namespace Automattic\WooCommerce\Blocks\Utils;

/**
 * Utility methods used for the Product Gallery block.
 * {@internal This class and its methods are not intended for public use.}
 */
class ProductGalleryUtils {
<<<<<<< HEAD
	/**
	 * Get all image IDs for the product.
	 *
	 * @param \WC_Product $product The product object.
	 * @return array An array of image IDs.
	 */
	public static function get_all_image_ids( $product ) {
		if ( ! $product instanceof \WC_Product ) {
			wc_doing_it_wrong( __FUNCTION__, __( 'Invalid product object.', 'woocommerce' ), '9.8.0' );
			return array();
		}

		$gallery_image_ids           = self::get_product_gallery_image_ids( $product );
		$product_variation_image_ids = self::get_product_variation_image_ids( $product );
		$all_image_ids               = array_values( array_map( 'intval', array_unique( array_merge( $gallery_image_ids, $product_variation_image_ids ) ) ) );

		if ( empty( $all_image_ids ) ) {
			return array();
		}

		return $all_image_ids;
	}

	/**
	 * Get the product gallery image data.
	 *
	 * @param \WC_Product $product The product object to retrieve the gallery images for.
	 * @param string      $size The size of the image to retrieve.
	 * @return array An array of image data for the product gallery.
	 */
	public static function get_product_gallery_image_data( $product, $size ) {
		$all_image_ids = self::get_all_image_ids( $product );
		return self::get_image_src_data( $all_image_ids, $size, $product->get_title() );
	}

	/**
	 * Get the product gallery image count.
	 *
	 * @param \WC_Product $product The product object to retrieve the gallery images for.
	 * @return int The number of images in the product gallery.
	 */
	public static function get_product_gallery_image_count( $product ) {
		$all_image_ids = self::get_all_image_ids( $product );
		return count( $all_image_ids );
	}

	/**
	 * Get the image source data.
	 *
	 * @param array  $image_ids The image IDs to retrieve the source data for.
	 * @param string $size The size of the image to retrieve.
	 * @param string $product_title The title of the product used for alt fallback.
	 * @return array An array of image source data.
	 */
	public static function get_image_src_data( $image_ids, $size, $product_title = '' ) {
		$image_src_data = array();

		foreach ( $image_ids as $index => $image_id ) {
			if ( 0 === $image_id ) {
				// Handle placeholder image.
				$image_src_data[] = array(
					'id'     => 0,
					'src'    => wc_placeholder_img_src(),
					'srcset' => '',
					'sizes'  => '',
					'alt'    => '',
				);
				continue;
			}

			// Get the image source.
			$full_src = wp_get_attachment_image_src( $image_id, $size );

			// Get srcset and sizes.
			$srcset = wp_get_attachment_image_srcset( $image_id, $size );
			$sizes  = wp_get_attachment_image_sizes( $image_id, $size );
			$alt    = get_post_meta( $image_id, '_wp_attachment_image_alt', true );

			$image_src_data[] = array(
				'id'     => $image_id,
				'src'    => $full_src ? $full_src[0] : '',
				'srcset' => $srcset ? $srcset : '',
				'sizes'  => $sizes ? $sizes : '',
				'alt'    => $alt ? $alt : sprintf(
					/* translators: 1: Product title 2: Image number */
					__( '%1$s - Image %2$d', 'woocommerce' ),
					$product_title,
					$index + 1
				),
			);
		}

		return $image_src_data;
	}

	/**
	 * Get the product variation image data.
	 *
	 * @param \WC_Product $product The product object to retrieve the variation images for.
	 * @return array An array of image data for the product variation images.
	 */
	public static function get_product_variation_image_ids( $product ) {
		$variation_image_ids = array();

		if ( ! $product instanceof \WC_Product ) {
			wc_doing_it_wrong( __FUNCTION__, __( 'Invalid product object.', 'woocommerce' ), '9.8.0' );
			return $variation_image_ids;
		}

		try {
			if ( $product->is_type( 'variable' ) ) {
				$variations = $product->get_children();
				foreach ( $variations as $variation_id ) {
					$variation = wc_get_product( $variation_id );
					if ( $variation ) {
						$variation_image_id = $variation->get_image_id();
						if ( ! empty( $variation_image_id ) && ! in_array( strval( $variation_image_id ), $variation_image_ids, true ) ) {
							$variation_image_ids[] = strval( $variation_image_id );
						}
					}
				}
			}
		} catch ( \Exception $e ) {
			// Log the error but continue execution.
			error_log( 'Error getting product variation image IDs: ' . $e->getMessage() );
		}

		return $variation_image_ids;
=======
	const CROP_IMAGE_SIZE_NAME = '_woo_blocks_product_gallery_crop_full';

	/**
	 * When requesting a full-size image, this function may return an array with a single image.
	 * However, when requesting a non-full-size image, it will always return an array with multiple images.
	 * This distinction is based on the image size needed for rendering purposes:
	 * - "Full" size is used for the main product featured image.
	 * - Non-full sizes are used for rendering thumbnails.
	 *
	 * @param int    $post_id Post ID.
	 * @param string $size Image size.
	 * @param array  $attributes Attributes.
	 * @param string $wrapper_class Wrapper class.
	 * @param bool   $crop_images Whether to crop images.
	 * @return array
	 */
	public static function get_product_gallery_images( $post_id, $size = 'full', $attributes = array(), $wrapper_class = '', $crop_images = false ) {
		$product_gallery_images = array();
		$product                = wc_get_product( $post_id );

		if ( $product instanceof \WC_Product ) {
			$all_product_gallery_image_ids = self::get_product_gallery_image_ids( $product );

			if ( 'full' === $size || 'full' !== $size && count( $all_product_gallery_image_ids ) > 1 ) {
				$size = $crop_images ? self::CROP_IMAGE_SIZE_NAME : $size;

				foreach ( $all_product_gallery_image_ids as $product_gallery_image_id ) {
					if ( '0' !== $product_gallery_image_id ) {
						if ( $crop_images ) {
							self::maybe_generate_intermediate_image( $product_gallery_image_id, self::CROP_IMAGE_SIZE_NAME );
						}

						$product_image_html = wp_get_attachment_image(
							$product_gallery_image_id,
							$size,
							false,
							$attributes
						);
					} else {
						$product_image_html = self::get_product_image_placeholder_html( $size, $attributes, $crop_images );
					}

					if ( $wrapper_class ) {
						$product_image_html = '<div class="' . $wrapper_class . '">' . $product_image_html . '</div>';
					}

					$product_image_html_processor = new \WP_HTML_Tag_Processor( $product_image_html );
					$product_image_html_processor->next_tag( 'img' );
					$product_image_html_processor->set_attribute(
						'data-wp-context',
						wp_json_encode(
							array(
								'imageId' => $product_gallery_image_id,
							),
							JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP
						)
					);

					if ( wp_is_mobile() ) {
						$product_image_html_processor->set_attribute( 'loading', 'eager' );
					}

					$product_gallery_images[] = $product_image_html_processor->get_updated_html();
				}
			}
		}

		return $product_gallery_images;
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
	}

	/**
	 * Get the product gallery image IDs.
	 *
<<<<<<< HEAD
	 * @param \WC_Product $product The product object to retrieve the gallery images for.
	 * @return array An array of unique image IDs for the product gallery.
	 */
	public static function get_product_gallery_image_ids( $product ) {
		$product_image_ids = array();

		// Main product featured image.
		$featured_image_id = $product->get_image_id();

		if ( $featured_image_id ) {
			$product_image_ids[] = $featured_image_id;
		}

		// All other product gallery images.
		$product_gallery_image_ids = $product->get_gallery_image_ids();

		if ( ! empty( $product_gallery_image_ids ) ) {
			// We don't want to show the same image twice, so we have to remove the featured image from the gallery if it's there.
			$product_image_ids = array_unique( array_merge( $product_image_ids, $product_gallery_image_ids ) );
		}

		// If the Product image is not set and there are no gallery images, we need to set it to a placeholder image.
		if ( ! $featured_image_id && empty( $product_gallery_image_ids ) ) {
			$product_image_ids[] = '0';
		}

		foreach ( $product_image_ids as $key => $image_id ) {
			$product_image_ids[ $key ] = strval( $image_id );
		}

		// Reindex array.
		$product_image_ids = array_values( $product_image_ids );

		return $product_image_ids;
=======
	 * @param \WC_Product $product                      The product object to retrieve the gallery images for.
	 * @param int         $max_number_of_visible_images The maximum number of visible images to return. Defaults to 8.
	 * @param bool        $only_visible                 Whether to return only the visible images. Defaults to false.
	 * @return array An array of unique image IDs for the product gallery.
	 */
	public static function get_product_gallery_image_ids( $product, $max_number_of_visible_images = 8, $only_visible = false ) {
		// Main product featured image.
		$featured_image_id = $product->get_image_id();
		// All other product gallery images.
		$product_gallery_image_ids = $product->get_gallery_image_ids();

		// If the Product image is not set, we need to set it to a placeholder image.
		if ( '' === $featured_image_id ) {
			$featured_image_id = '0';
		}

		// We don't want to show the same image twice, so we have to remove the featured image from the gallery if it's there.
		$unique_image_ids = array_unique(
			array_merge(
				array( $featured_image_id ),
				$product_gallery_image_ids
			)
		);

		foreach ( $unique_image_ids as $key => $image_id ) {
			$unique_image_ids[ $key ] = strval( $image_id );
		}

		if ( count( $unique_image_ids ) > $max_number_of_visible_images && $only_visible ) {
			$unique_image_ids = array_slice( $unique_image_ids, 0, $max_number_of_visible_images );
		}

		// Reindex array.
		$unique_image_ids = array_values( $unique_image_ids );

		return $unique_image_ids;
	}

	/**
	 * Generates the intermediate image sizes only when needed.
	 *
	 * @param int    $attachment_id Attachment ID.
	 * @param string $size Image size.
	 * @return void
	 */
	public static function maybe_generate_intermediate_image( $attachment_id, $size ) {
		$metadata   = image_get_intermediate_size( $attachment_id, $size );
		$upload_dir = wp_upload_dir();
		$image_path = '';

		if ( $metadata ) {
			$image_path = $upload_dir['basedir'] . '/' . $metadata['path'];
		}

		/*
		 * We need to check both if the size metadata exists and if the file exists.
		 * Sometimes we can have orphaned image file and no metadata or vice versa.
		 */
		if ( $metadata && file_exists( $image_path ) ) {
			return;
		}

		$image_path     = wp_get_original_image_path( $attachment_id );
		$image_metadata = wp_get_attachment_metadata( $attachment_id );

		// If image sizes are not available. Bail.
		if ( ! isset( $image_metadata['width'], $image_metadata['height'] ) ) {
			return;
		}

		/*
		 * We want to take the minimum dimension of the image and
		 * use that size as the crop size for the new image.
		 */
		$min_size                         = min( $image_metadata['width'], $image_metadata['height'] );
		$new_image_metadata               = image_make_intermediate_size( $image_path, $min_size, $min_size, true );
		$image_metadata['sizes'][ $size ] = $new_image_metadata;

		wp_update_attachment_metadata( $attachment_id, $image_metadata );
	}

	/**
	 * Get the product image placeholder HTML.
	 *
	 * @param string $size Image size.
	 * @param array  $attributes Attributes.
	 * @param bool   $crop_images Whether to crop images.
	 * @return string
	 */
	public static function get_product_image_placeholder_html( $size, $attributes, $crop_images ) {
		$placeholder_image_id = get_option( 'woocommerce_placeholder_image', 0 );

		if ( ! $placeholder_image_id ) {

			// Return default fallback WooCommerce placeholder image.
			return wc_placeholder_img( array( '', '' ), $attributes );
		}

		if ( $crop_images ) {
			self::maybe_generate_intermediate_image( $placeholder_image_id, self::CROP_IMAGE_SIZE_NAME );
		}

		return wp_get_attachment_image( $placeholder_image_id, $size, false, $attributes );
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
	}
}
