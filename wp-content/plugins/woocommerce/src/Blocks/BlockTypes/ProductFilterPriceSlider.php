<?php
/**
 * ProductFilterPriceSlider class.
 *
 * @package Automattic\WooCommerce\Blocks\BlockTypes
 */

declare( strict_types = 1 );

namespace Automattic\WooCommerce\Blocks\BlockTypes;

/**
 * ProductFilterPriceSlider class.
 */
class ProductFilterPriceSlider extends AbstractBlock {

<<<<<<< HEAD
	use EnableBlockJsonAssetsTrait;

=======
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
	/**
	 * Block name.
	 *
	 * @var string
	 */
	protected $block_name = 'product-filter-price-slider';

	/**
	 * Render the block.
	 *
	 * @param array    $attributes Block attributes.
	 * @param string   $content    Block content.
	 * @param WP_Block $block      Block instance.
	 * @return string Rendered block type output.
	 */
	protected function render( $attributes, $content, $block ) {
		if ( is_admin() || wp_doing_ajax() || empty( $block->context['filterData'] ) || empty( $block->context['filterData']['price'] ) ) {
			return '';
		}

<<<<<<< HEAD
=======
		wp_enqueue_script_module( $this->get_full_block_name() );

>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
		$price_data = $block->context['filterData']['price'];
		$min_price  = $price_data['minPrice'];
		$max_price  = $price_data['maxPrice'];
		$min_range  = $price_data['minRange'];
		$max_range  = $price_data['maxRange'];

		if ( $min_range === $max_range ) {
			return;
		}

		$classes = '';
		$style   = '';

		$tags = new \WP_HTML_Tag_Processor( $content );
		if ( $tags->next_tag( array( 'class_name' => 'wc-block-product-filter-price-slider' ) ) ) {
			$classes = $tags->get_attribute( 'class' );
			$style   = $tags->get_attribute( 'style' );
		}

<<<<<<< HEAD
=======
		$formatted_min_price = wc_price( $min_price, array( 'decimals' => 0 ) );
		$formatted_max_price = wc_price( $max_price, array( 'decimals' => 0 ) );

>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
		$show_input_fields = isset( $attributes['showInputFields'] ) ? $attributes['showInputFields'] : false;
		$inline_input      = isset( $attributes['inlineInput'] ) ? $attributes['inlineInput'] : false;

		$wrapper_attributes = get_block_wrapper_attributes(
			array(
<<<<<<< HEAD
				'data-wp-interactive' => 'woocommerce/product-filters',
				'data-wp-key'         => wp_unique_prefixed_id( $this->get_full_block_name() ),
				'class'               => esc_attr( $classes ),
				'style'               => esc_attr( $style ),
=======
				'class'               => esc_attr( $classes ),
				'style'               => esc_attr( $style ),
				'data-wp-interactive' => $this->get_full_block_name(),
				'data-wp-key'         => wp_unique_prefixed_id( $this->get_full_block_name() ),

>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
			)
		);

		$content_class = 'wc-block-product-filter-price-slider__content';
		if ( $inline_input && $show_input_fields ) {
			$content_class .= ' wc-block-product-filter-price-slider__content--inline';
		}

		// CSS variables for the range bar style.
		$__low       = 100 * ( $min_price - $min_range ) / ( $max_range - $min_range );
		$__high      = 100 * ( $max_price - $min_range ) / ( $max_range - $min_range );
		$range_style = "--low: $__low%; --high: $__high%";

<<<<<<< HEAD
		wp_interactivity_state(
			'woocommerce/product-filters',
			array(
				'rangeStyle' => $range_style,
			)
		);

		/**
		 * Accessibility: Assign the left input to a variable to conditionally
		 * render it based on the inline input setting. We do this to have the
		 * correct focus order of the input fields.
		 */
		ob_start();
		?>
		<div class="wc-block-product-filter-price-slider__left text">
			<?php if ( $show_input_fields ) : ?>
				<input
					class="min"
					type="text"
					data-wp-bind--value="state.formattedMinPrice"
					data-wp-on--focus="actions.selectInputContent"
					data-wp-on--input="actions.debounceSetMinPrice"
					aria-label="<?php esc_attr_e( 'Filter products by minimum price', 'woocommerce' ); ?>"
				/>
			<?php else : ?>
				<span data-wp-text="state.formattedMinPrice"></span>
			<?php endif; ?>
		</div>
		<?php
		$left_input = ob_get_clean();

=======
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
		ob_start();
		?>
		<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<div class="<?php echo esc_attr( $content_class ); ?>">
<<<<<<< HEAD
				<?php if ( $inline_input ) : ?>
					<?php echo $left_input; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php endif; ?>
				<div
					class="wc-block-product-filter-price-slider__range"
=======
				<div class="wc-block-product-filter-price-slider__left text">
					<?php if ( $show_input_fields ) : ?>
						<input
							class="min"
							type="text"
							data-wp-bind--value="woocommerce/product-filter-price::state.formattedMinPrice"
							data-wp-on--focus="actions.selectInputContent"
							data-wp-on--input="actions.debounceSetPrice"
							data-wp-on--change--set-price="woocommerce/product-filter-price::actions.setMinPrice"
							data-wp-on--change--navigate="woocommerce/product-filters::actions.navigate"
							aria-label="<?php esc_attr_e( 'Filter products by minimum price', 'woocommerce' ); ?>"
						/>
					<?php else : ?>
						<span data-wp-text="woocommerce/product-filter-price::state.formattedMinPrice"><?php echo wp_kses_post( $formatted_min_price ); ?></span>
					<?php endif; ?>
				</div>
				<div
					class="wc-block-product-filter-price-slider__range"
					style="<?php echo esc_attr( $range_style ); ?>"
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
					data-wp-bind--style="state.rangeStyle"
				>
					<div class="range-bar"></div>
					<input
						type="range"
						class="min"
						min="<?php echo esc_attr( $min_range ); ?>"
						max="<?php echo esc_attr( $max_range ); ?>"
<<<<<<< HEAD
						data-wp-bind--value="state.minPrice"
						data-wp-on--input="actions.setMinPrice"
						data-wp-on--mouseup="actions.navigate"
						data-wp-on--keyup="actions.navigate"
						data-wp-on--touchend="actions.navigate"
=======
						data-wp-bind--value="woocommerce/product-filter-price::state.minPrice"
						data-wp-bind--min="woocommerce/product-filter-price::context.minRange"
						data-wp-bind--max="woocommerce/product-filter-price::context.maxRange"
						data-wp-on--input--update-price="woocommerce/product-filter-price::actions.setMinPrice"
						data-wp-on--input--limit-range="actions.limitRange"
						data-wp-on--mouseup="woocommerce/product-filters::actions.navigate"
						data-wp-on--keyup="woocommerce/product-filters::actions.navigate"
						data-wp-on--touchend="woocommerce/product-filters::actions.navigate"
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
						aria-label="<?php esc_attr_e( 'Filter products by minimum price', 'woocommerce' ); ?>"
					/>
					<input
						type="range"
						class="max"
						min="<?php echo esc_attr( $min_range ); ?>"
						max="<?php echo esc_attr( $max_range ); ?>"
<<<<<<< HEAD
						data-wp-bind--value="state.maxPrice"
						data-wp-on--input="actions.setMaxPrice"
						data-wp-on--mouseup="actions.navigate"
						data-wp-on--keyup="actions.navigate"
						data-wp-on--touchend="actions.navigate"
						aria-label="<?php esc_attr_e( 'Filter products by maximum price', 'woocommerce' ); ?>"
					/>
				</div>
				<?php if ( ! $inline_input ) : ?>
					<?php echo $left_input; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php endif; ?>
=======
						data-wp-bind--value="woocommerce/product-filter-price::state.maxPrice"
						data-wp-bind--min="woocommerce/product-filter-price::context.minRange"
						data-wp-bind--max="woocommerce/product-filter-price::context.maxRange"
						data-wp-on--input--update-price="woocommerce/product-filter-price::actions.setMaxPrice"
						data-wp-on--input--limit-range="actions.limitRange"
						data-wp-on--mouseup="woocommerce/product-filters::actions.navigate"
						data-wp-on--keyup="woocommerce/product-filters::actions.navigate"
						data-wp-on--touchend="woocommerce/product-filters::actions.navigate"
						aria-label="<?php esc_attr_e( 'Filter products by maximum price', 'woocommerce' ); ?>"
					/>
				</div>
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
				<div class="wc-block-product-filter-price-slider__right text">
					<?php if ( $show_input_fields ) : ?>
						<input
							class="max"
							type="text"
<<<<<<< HEAD
							data-wp-bind--value="state.formattedMaxPrice"
							data-wp-on--focus="actions.selectInputContent"
							data-wp-on--input="actions.debounceSetMaxPrice"
							aria-label="<?php esc_attr_e( 'Filter products by maximum price', 'woocommerce' ); ?>"
						/>
					<?php else : ?>
					<span data-wp-text="state.formattedMaxPrice"></span>
=======
							data-wp-bind--value="woocommerce/product-filter-price::state.formattedMaxPrice"
							data-wp-on--focus="actions.selectInputContent"
							data-wp-on--input="actions.debounceSetPrice"
							data-wp-on--change--set-price="woocommerce/product-filter-price::actions.setMaxPrice"
							data-wp-on--change--navigate="woocommerce/product-filters::actions.navigate"
							aria-label="<?php esc_attr_e( 'Filter products by maximum price', 'woocommerce' ); ?>"
						/>
					<?php else : ?>
					<span data-wp-text="woocommerce/product-filter-price::state.formattedMaxPrice"><?php echo wp_kses_post( $formatted_max_price ); ?></span>
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
					<?php endif; ?>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
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
