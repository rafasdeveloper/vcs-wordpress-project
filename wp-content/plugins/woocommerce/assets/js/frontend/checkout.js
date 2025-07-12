/* global wc_checkout_params */
<<<<<<< HEAD
jQuery( function ( $ ) {
=======
jQuery( function( $ ) {

>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
	// wc_checkout_params is required to continue, ensure the object exists
	if ( typeof wc_checkout_params === 'undefined' ) {
		return false;
	}

	$.blockUI.defaults.overlayCSS.cursor = 'default';

	var wc_checkout_form = {
		updateTimer: false,
		dirtyInput: false,
		selectedPaymentMethod: false,
		xhr: false,
		$order_review: $( '#order_review' ),
		$checkout_form: $( 'form.checkout' ),
<<<<<<< HEAD
		init: function () {
=======
		init: function() {
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
			$( document.body ).on( 'update_checkout', this.update_checkout );
			$( document.body ).on( 'init_checkout', this.init_checkout );

			// Payment methods
<<<<<<< HEAD
			this.$checkout_form.on(
				'click',
				'input[name="payment_method"]',
				this.payment_method_selected
			);

			if ( $( document.body ).hasClass( 'woocommerce-order-pay' ) ) {
				this.$order_review.on(
					'click',
					'input[name="payment_method"]',
					this.payment_method_selected
				);
=======
			this.$checkout_form.on( 'click', 'input[name="payment_method"]', this.payment_method_selected );

			if ( $( document.body ).hasClass( 'woocommerce-order-pay' ) ) {
				this.$order_review.on( 'click', 'input[name="payment_method"]', this.payment_method_selected );
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
				this.$order_review.on( 'submit', this.submitOrder );
				this.$order_review.attr( 'novalidate', 'novalidate' );
			}

			// Prevent HTML5 validation which can conflict.
			this.$checkout_form.attr( 'novalidate', 'novalidate' );

			// Form submission
			this.$checkout_form.on( 'submit', this.submit );

			// Inline validation
<<<<<<< HEAD
			this.$checkout_form.on(
				'input validate change focusout',
				'.input-text, select, input:checkbox',
				this.validate_field
			);
=======
			this.$checkout_form.on( 'input validate change focusout', '.input-text, select, input:checkbox', this.validate_field );
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)

			// Manual trigger
			this.$checkout_form.on( 'update', this.trigger_update_checkout );

			// Inputs/selects which update totals
<<<<<<< HEAD
			this.$checkout_form.on(
				'change',
				// eslint-disable-next-line max-len
				'select.shipping_method, input[name^="shipping_method"], #ship-to-different-address input, .update_totals_on_change select, .update_totals_on_change input[type="radio"], .update_totals_on_change input[type="checkbox"]',
				this.trigger_update_checkout
			);
			this.$checkout_form.on(
				'change',
				'.address-field select',
				this.input_changed
			);
			this.$checkout_form.on(
				'change',
				'.address-field input.input-text, .update_totals_on_change input.input-text',
				this.maybe_input_changed
			);
			this.$checkout_form.on(
				'keydown',
				'.address-field input.input-text, .update_totals_on_change input.input-text',
				this.queue_update_checkout
			);

			// Address fields
			this.$checkout_form.on(
				'change',
				'#ship-to-different-address input',
				this.ship_to_different_address
			);

			// Trigger events
			this.$checkout_form
				.find( '#ship-to-different-address input' )
				.trigger( 'change' );
=======
			this.$checkout_form.on( 'change', 'select.shipping_method, input[name^="shipping_method"], #ship-to-different-address input, .update_totals_on_change select, .update_totals_on_change input[type="radio"], .update_totals_on_change input[type="checkbox"]', this.trigger_update_checkout ); // eslint-disable-line max-len
			this.$checkout_form.on( 'change', '.address-field select', this.input_changed );
			this.$checkout_form.on( 'change', '.address-field input.input-text, .update_totals_on_change input.input-text', this.maybe_input_changed ); // eslint-disable-line max-len
			this.$checkout_form.on( 'keydown', '.address-field input.input-text, .update_totals_on_change input.input-text', this.queue_update_checkout ); // eslint-disable-line max-len

			// Address fields
			this.$checkout_form.on( 'change', '#ship-to-different-address input', this.ship_to_different_address );

			// Trigger events
			this.$checkout_form.find( '#ship-to-different-address input' ).trigger( 'change' );
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
			this.init_payment_methods();

			// Update on page load
			if ( wc_checkout_params.is_checkout === '1' ) {
				$( document.body ).trigger( 'init_checkout' );
			}
			if ( wc_checkout_params.option_guest_checkout === 'yes' ) {
<<<<<<< HEAD
				$( 'input#createaccount' )
					.on( 'change', this.toggle_create_account )
					.trigger( 'change' );
			}
		},
		init_payment_methods: function () {
			var $payment_methods = $( '.woocommerce-checkout' ).find(
				'input[name="payment_method"]'
			);

			// If there is one method, we can hide the radio input
			if ( 1 === $payment_methods.length ) {
				$payment_methods.eq( 0 ).hide();
=======
				$( 'input#createaccount' ).on( 'change', this.toggle_create_account ).trigger( 'change' );
			}
		},
		init_payment_methods: function() {
			var $payment_methods = $( '.woocommerce-checkout' ).find( 'input[name="payment_method"]' );

			// If there is one method, we can hide the radio input
			if ( 1 === $payment_methods.length ) {
				$payment_methods.eq(0).hide();
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
			}

			// If there was a previously selected method, check that one.
			if ( wc_checkout_form.selectedPaymentMethod ) {
<<<<<<< HEAD
				$( '#' + wc_checkout_form.selectedPaymentMethod ).prop(
					'checked',
					true
				);
=======
				$( '#' + wc_checkout_form.selectedPaymentMethod ).prop( 'checked', true );
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
			}

			// If there are none selected, select the first.
			if ( 0 === $payment_methods.filter( ':checked' ).length ) {
<<<<<<< HEAD
				$payment_methods.eq( 0 ).prop( 'checked', true );
			}

			// Get name of new selected method.
			var checkedPaymentMethod = $payment_methods
				.filter( ':checked' )
				.eq( 0 )
				.prop( 'id' );

			if ( $payment_methods.length > 1 ) {
				// Hide open descriptions.
				$( 'div.payment_box:not(".' + checkedPaymentMethod + '")' )
					.filter( ':visible' )
					.slideUp( 0 );
			}

			// Trigger click event for selected method
			$payment_methods.filter( ':checked' ).eq( 0 ).trigger( 'click' );
		},
		get_payment_method: function () {
			return wc_checkout_form.$checkout_form
				.find( 'input[name="payment_method"]:checked' )
				.val();
		},
		payment_method_selected: function ( e ) {
			e.stopPropagation();

			if ( $( '.payment_methods input.input-radio' ).length > 1 ) {
				var target_payment_box = $(
						'div.payment_box.' + $( this ).attr( 'ID' )
					),
					is_checked = $( this ).is( ':checked' );
=======
				$payment_methods.eq(0).prop( 'checked', true );
			}

			// Get name of new selected method.
			var checkedPaymentMethod = $payment_methods.filter( ':checked' ).eq(0).prop( 'id' );

			if ( $payment_methods.length > 1 ) {
				// Hide open descriptions.
				$( 'div.payment_box:not(".' + checkedPaymentMethod + '")' ).filter( ':visible' ).slideUp( 0 );
			}

			// Trigger click event for selected method
			$payment_methods.filter( ':checked' ).eq(0).trigger( 'click' );
		},
		get_payment_method: function() {
			return wc_checkout_form.$checkout_form.find( 'input[name="payment_method"]:checked' ).val();
		},
		payment_method_selected: function( e ) {
			e.stopPropagation();

			if ( $( '.payment_methods input.input-radio' ).length > 1 ) {
				var target_payment_box = $( 'div.payment_box.' + $( this ).attr( 'ID' ) ),
					is_checked         = $( this ).is( ':checked' );
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)

				if ( is_checked && ! target_payment_box.is( ':visible' ) ) {
					$( 'div.payment_box' ).filter( ':visible' ).slideUp( 230 );

					if ( is_checked ) {
						target_payment_box.slideDown( 230 );
					}
				}
			} else {
				$( 'div.payment_box' ).show();
			}

			if ( $( this ).data( 'order_button_text' ) ) {
<<<<<<< HEAD
				$( '#place_order' ).text(
					$( this ).data( 'order_button_text' )
				);
=======
				$( '#place_order' ).text( $( this ).data( 'order_button_text' ) );
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
			} else {
				$( '#place_order' ).text( $( '#place_order' ).data( 'value' ) );
			}

<<<<<<< HEAD
			var selectedPaymentMethod = $(
				'.woocommerce-checkout input[name="payment_method"]:checked'
			).attr( 'id' );

			if (
				selectedPaymentMethod !== wc_checkout_form.selectedPaymentMethod
			) {
=======
			var selectedPaymentMethod = $( '.woocommerce-checkout input[name="payment_method"]:checked' ).attr( 'id' );

			if ( selectedPaymentMethod !== wc_checkout_form.selectedPaymentMethod ) {
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
				$( document.body ).trigger( 'payment_method_selected' );
			}

			wc_checkout_form.selectedPaymentMethod = selectedPaymentMethod;
		},
<<<<<<< HEAD
		toggle_create_account: function () {
=======
		toggle_create_account: function() {
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
			$( 'div.create-account' ).hide();

			if ( $( this ).is( ':checked' ) ) {
				// Ensure password is not pre-populated.
				$( '#account_password' ).val( '' ).trigger( 'change' );
				$( 'div.create-account' ).slideDown();
			}
		},
<<<<<<< HEAD
		init_checkout: function () {
			$( document.body ).trigger( 'update_checkout' );
		},
		maybe_input_changed: function ( e ) {
=======
		init_checkout: function() {
			$( document.body ).trigger( 'update_checkout' );
		},
		maybe_input_changed: function( e ) {
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
			if ( wc_checkout_form.dirtyInput ) {
				wc_checkout_form.input_changed( e );
			}
		},
<<<<<<< HEAD
		input_changed: function ( e ) {
			wc_checkout_form.dirtyInput = e.target;
			wc_checkout_form.maybe_update_checkout();
		},
		queue_update_checkout: function ( e ) {
=======
		input_changed: function( e ) {
			wc_checkout_form.dirtyInput = e.target;
			wc_checkout_form.maybe_update_checkout();
		},
		queue_update_checkout: function( e ) {
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
			var code = e.keyCode || e.which || 0;

			if ( code === 9 ) {
				return true;
			}

			wc_checkout_form.dirtyInput = this;
			wc_checkout_form.reset_update_checkout_timer();
<<<<<<< HEAD
			wc_checkout_form.updateTimer = setTimeout(
				wc_checkout_form.maybe_update_checkout,
				'1000'
			);
		},
		trigger_update_checkout: function ( event ) {
			wc_checkout_form.reset_update_checkout_timer();
			wc_checkout_form.dirtyInput = false;
			$( document.body ).trigger( 'update_checkout', {
				current_target: event ? event.currentTarget : null,
			} );
		},
		maybe_update_checkout: function () {
			var update_totals = true;

			if ( $( wc_checkout_form.dirtyInput ).length ) {
				var $required_inputs = $( wc_checkout_form.dirtyInput )
					.closest( 'div' )
					.find( '.address-field.validate-required' );

				if ( $required_inputs.length ) {
					$required_inputs.each( function () {
						if (
							$( this ).find( 'input.input-text' ).val() === ''
						) {
							update_totals = false;
						}
					} );
=======
			wc_checkout_form.updateTimer = setTimeout( wc_checkout_form.maybe_update_checkout, '1000' );
		},
		trigger_update_checkout: function( event ) {
			wc_checkout_form.reset_update_checkout_timer();
			wc_checkout_form.dirtyInput = false;
			$( document.body ).trigger( 'update_checkout', { current_target: event ? event.currentTarget : null } );
		},
		maybe_update_checkout: function() {
			var update_totals = true;

			if ( $( wc_checkout_form.dirtyInput ).length ) {
				var $required_inputs = $( wc_checkout_form.dirtyInput ).closest( 'div' ).find( '.address-field.validate-required' );

				if ( $required_inputs.length ) {
					$required_inputs.each( function() {
						if ( $( this ).find( 'input.input-text' ).val() === '' ) {
							update_totals = false;
						}
					});
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
				}
			}
			if ( update_totals ) {
				wc_checkout_form.trigger_update_checkout();
			}
		},
<<<<<<< HEAD
		ship_to_different_address: function () {
=======
		ship_to_different_address: function() {
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
			$( 'div.shipping_address' ).hide();
			if ( $( this ).is( ':checked' ) ) {
				$( 'div.shipping_address' ).slideDown();
			}
		},
<<<<<<< HEAD
		reset_update_checkout_timer: function () {
			clearTimeout( wc_checkout_form.updateTimer );
		},
		is_valid_json: function ( raw_json ) {
			try {
				var json = JSON.parse( raw_json );

				return json && 'object' === typeof json;
=======
		reset_update_checkout_timer: function() {
			clearTimeout( wc_checkout_form.updateTimer );
		},
		is_valid_json: function( raw_json ) {
			try {
				var json = JSON.parse( raw_json );

				return ( json && 'object' === typeof json );
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
			} catch ( e ) {
				return false;
			}
		},
<<<<<<< HEAD
		validate_field: function ( e ) {
			var $this = $( this ),
				$parent = $this.closest( '.form-row' ),
				validated = true,
				validate_required = $parent.is( '.validate-required' ),
				validate_email = $parent.is( '.validate-email' ),
				validate_phone = $parent.is( '.validate-phone' ),
				pattern = '',
				event_type = e.type;

			if ( 'input' === event_type ) {
				$this
					.removeAttr( 'aria-invalid' )
					.removeAttr( 'aria-describedby' );
				$parent.find( '.checkout-inline-error-message' ).remove();
				$parent.removeClass(
					// eslint-disable-next-line max-len
					'woocommerce-invalid woocommerce-invalid-required-field woocommerce-invalid-email woocommerce-invalid-phone woocommerce-validated'
				);
			}

			if (
				'validate' === event_type ||
				'change' === event_type ||
				'focusout' === event_type
			) {
				if ( validate_required ) {
					if (
						( 'checkbox' === $this.attr( 'type' ) &&
							! $this.is( ':checked' ) ) ||
						$this.val() === ''
					) {
						$this.attr( 'aria-invalid', 'true' );
						$parent
							.removeClass( 'woocommerce-validated' )
							.addClass(
								'woocommerce-invalid woocommerce-invalid-required-field'
							);
=======
		validate_field: function( e ) {
			var $this             = $( this ),
				$parent           = $this.closest( '.form-row' ),
				validated         = true,
				validate_required = $parent.is( '.validate-required' ),
				validate_email    = $parent.is( '.validate-email' ),
				validate_phone    = $parent.is( '.validate-phone' ),
				pattern           = '',
				event_type        = e.type;

			if ( 'input' === event_type ) {
				$this.removeAttr( 'aria-invalid' ).removeAttr( 'aria-describedby' );
				$parent.find( '.checkout-inline-error-message' ).remove();
				$parent.removeClass( 'woocommerce-invalid woocommerce-invalid-required-field woocommerce-invalid-email woocommerce-invalid-phone woocommerce-validated' ); // eslint-disable-line max-len
			}

			if ( 'validate' === event_type || 'change' === event_type || 'focusout' === event_type ) {

				if ( validate_required ) {
					if ( ( 'checkbox' === $this.attr( 'type' ) && ! $this.is( ':checked' ) ) || $this.val() === '' ) {
						$this.attr( 'aria-invalid', 'true' );
						$parent.removeClass( 'woocommerce-validated' ).addClass( 'woocommerce-invalid woocommerce-invalid-required-field' );
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
						validated = false;
					}
				}

				if ( validate_email ) {
					if ( $this.val() ) {
						/* https://stackoverflow.com/questions/2855865/jquery-validate-e-mail-address-regex */
<<<<<<< HEAD
						pattern = new RegExp(
							// eslint-disable-next-line max-len
							/^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[0-9a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i
						); // eslint-disable-line max-len

						if ( ! pattern.test( $this.val() ) ) {
							$this.attr( 'aria-invalid', 'true' );
							$parent
								.removeClass( 'woocommerce-validated' )
								.addClass(
									'woocommerce-invalid woocommerce-invalid-email'
								); // eslint-disable-line max-len
=======
						pattern = new RegExp( /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[0-9a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i ); // eslint-disable-line max-len

						if ( ! pattern.test( $this.val() ) ) {
							$this.attr( 'aria-invalid', 'true' );
							$parent.removeClass( 'woocommerce-validated' ).addClass( 'woocommerce-invalid woocommerce-invalid-email woocommerce-invalid-phone' ); // eslint-disable-line max-len
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
							validated = false;
						}
					}
				}

				if ( validate_phone ) {
					pattern = new RegExp( /[\s\#0-9_\-\+\/\(\)\.]/g );

					if ( 0 < $this.val().replace( pattern, '' ).length ) {
						$this.attr( 'aria-invalid', 'true' );
<<<<<<< HEAD
						$parent
							.removeClass( 'woocommerce-validated' )
							.addClass(
								'woocommerce-invalid woocommerce-invalid-phone'
							);
=======
						$parent.removeClass( 'woocommerce-validated' ).addClass( 'woocommerce-invalid woocommerce-invalid-phone' );
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
						validated = false;
					}
				}

				if ( validated ) {
<<<<<<< HEAD
					$this
						.removeAttr( 'aria-invalid' )
						.removeAttr( 'aria-describedby' );
					$parent.find( '.checkout-inline-error-message' ).remove();
					$parent
						.removeClass(
							'woocommerce-invalid woocommerce-invalid-required-field woocommerce-invalid-email woocommerce-invalid-phone'
						)
						.addClass( 'woocommerce-validated' ); // eslint-disable-line max-len
				}
			}
		},
		update_checkout: function ( event, args ) {
			// Small timeout to prevent multiple requests when several fields update at the same time
			wc_checkout_form.reset_update_checkout_timer();
			wc_checkout_form.updateTimer = setTimeout(
				wc_checkout_form.update_checkout_action,
				'5',
				args
			);
		},
		update_checkout_action: function ( args ) {
=======
					$this.removeAttr( 'aria-invalid' ).removeAttr( 'aria-describedby' );
					$parent.find( '.checkout-inline-error-message' ).remove();
					$parent.removeClass( 'woocommerce-invalid woocommerce-invalid-required-field woocommerce-invalid-email woocommerce-invalid-phone' ).addClass( 'woocommerce-validated' ); // eslint-disable-line max-len
				}
			}
		},
		update_checkout: function( event, args ) {
			// Small timeout to prevent multiple requests when several fields update at the same time
			wc_checkout_form.reset_update_checkout_timer();
			wc_checkout_form.updateTimer = setTimeout( wc_checkout_form.update_checkout_action, '5', args );
		},
		update_checkout_action: function( args ) {
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
			if ( wc_checkout_form.xhr ) {
				wc_checkout_form.xhr.abort();
			}

			if ( $( 'form.checkout' ).length === 0 ) {
				return;
			}

<<<<<<< HEAD
			args =
				typeof args !== 'undefined'
					? args
					: {
							update_shipping_method: true,
					  };

			var country = $( '#billing_country' ).val(),
				state = $( '#billing_state' ).val(),
				postcode = $( ':input#billing_postcode' ).val(),
				city = $( '#billing_city' ).val(),
				address = $( ':input#billing_address_1' ).val(),
				address_2 = $( ':input#billing_address_2' ).val(),
				s_country = country,
				s_state = state,
				s_postcode = postcode,
				s_city = city,
				s_address = address,
				s_address_2 = address_2,
				$required_inputs = $( wc_checkout_form.$checkout_form ).find(
					'.address-field.validate-required:visible'
				),
				has_full_address = true;

			if ( $required_inputs.length ) {
				$required_inputs.each( function () {
					if ( $( this ).find( ':input' ).val() === '' ) {
						has_full_address = false;
					}
				} );
			}

			if (
				$( '#ship-to-different-address' )
					.find( 'input' )
					.is( ':checked' )
			) {
				s_country = $( '#shipping_country' ).val();
				s_state = $( '#shipping_state' ).val();
				s_postcode = $( ':input#shipping_postcode' ).val();
				s_city = $( '#shipping_city' ).val();
				s_address = $( ':input#shipping_address_1' ).val();
				s_address_2 = $( ':input#shipping_address_2' ).val();
			}

			var data = {
				security: wc_checkout_params.update_order_review_nonce,
				payment_method: wc_checkout_form.get_payment_method(),
				country: country,
				state: state,
				postcode: postcode,
				city: city,
				address: address,
				address_2: address_2,
				s_country: s_country,
				s_state: s_state,
				s_postcode: s_postcode,
				s_city: s_city,
				s_address: s_address,
				s_address_2: s_address_2,
				has_full_address: has_full_address,
				post_data: $( 'form.checkout' ).serialize(),
=======
			args = typeof args !== 'undefined' ? args : {
				update_shipping_method: true
			};

			var country			 = $( '#billing_country' ).val(),
				state			 = $( '#billing_state' ).val(),
				postcode		 = $( ':input#billing_postcode' ).val(),
				city			 = $( '#billing_city' ).val(),
				address			 = $( ':input#billing_address_1' ).val(),
				address_2		 = $( ':input#billing_address_2' ).val(),
				s_country		 = country,
				s_state			 = state,
				s_postcode		 = postcode,
				s_city			 = city,
				s_address		 = address,
				s_address_2		 = address_2,
				$required_inputs = $( wc_checkout_form.$checkout_form ).find( '.address-field.validate-required:visible' ),
				has_full_address = true;

			if ( $required_inputs.length ) {
				$required_inputs.each( function() {
					if ( $( this ).find( ':input' ).val() === '' ) {
						has_full_address = false;
					}
				});
			}

			if ( $( '#ship-to-different-address' ).find( 'input' ).is( ':checked' ) ) {
				s_country		 = $( '#shipping_country' ).val();
				s_state			 = $( '#shipping_state' ).val();
				s_postcode		 = $( ':input#shipping_postcode' ).val();
				s_city			 = $( '#shipping_city' ).val();
				s_address		 = $( ':input#shipping_address_1' ).val();
				s_address_2		 = $( ':input#shipping_address_2' ).val();
			}

			var data = {
				security        : wc_checkout_params.update_order_review_nonce,
				payment_method  : wc_checkout_form.get_payment_method(),
				country         : country,
				state           : state,
				postcode        : postcode,
				city            : city,
				address         : address,
				address_2       : address_2,
				s_country       : s_country,
				s_state         : s_state,
				s_postcode      : s_postcode,
				s_city          : s_city,
				s_address       : s_address,
				s_address_2     : s_address_2,
				has_full_address: has_full_address,
				post_data       : $( 'form.checkout' ).serialize()
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
			};

			if ( false !== args.update_shipping_method ) {
				var shipping_methods = {};

<<<<<<< HEAD
				$(
					// eslint-disable-next-line max-len
					'select.shipping_method, input[name^="shipping_method"][type="radio"]:checked, input[name^="shipping_method"][type="hidden"]'
				).each( function () {
					shipping_methods[ $( this ).data( 'index' ) ] =
						$( this ).val();
=======
				// eslint-disable-next-line max-len
				$( 'select.shipping_method, input[name^="shipping_method"][type="radio"]:checked, input[name^="shipping_method"][type="hidden"]' ).each( function() {
					shipping_methods[ $( this ).data( 'index' ) ] = $( this ).val();
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
				} );

				data.shipping_method = shipping_methods;
			}

<<<<<<< HEAD
			$(
				'.woocommerce-checkout-payment, .woocommerce-checkout-review-order-table'
			).block( {
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6,
				},
			} );

			wc_checkout_form.xhr = $.ajax( {
				type: 'POST',
				url: wc_checkout_params.wc_ajax_url
					.toString()
					.replace( '%%endpoint%%', 'update_order_review' ),
				data: data,
				success: function ( data ) {
=======
			$( '.woocommerce-checkout-payment, .woocommerce-checkout-review-order-table' ).block({
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
			});

			wc_checkout_form.xhr = $.ajax({
				type:		'POST',
				url:		wc_checkout_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'update_order_review' ),
				data:		data,
				success:	function( data ) {

>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
					// Reload the page if requested
					if ( data && true === data.reload ) {
						window.location.reload();
						return;
					}

					// Remove any notices added previously
					$( '.woocommerce-NoticeGroup-updateOrderReview' ).remove();

					var termsCheckBoxChecked = $( '#terms' ).prop( 'checked' );

					// Save payment details to a temporary object
					var paymentDetails = {};
<<<<<<< HEAD
					$( '.payment_box :input' ).each( function () {
						var ID = $( this ).attr( 'id' );

						if ( ID ) {
							if (
								$.inArray( $( this ).attr( 'type' ), [
									'checkbox',
									'radio',
								] ) !== -1
							) {
								paymentDetails[ ID ] =
									$( this ).prop( 'checked' );
=======
					$( '.payment_box :input' ).each( function() {
						var ID = $( this ).attr( 'id' );

						if ( ID ) {
							if ( $.inArray( $( this ).attr( 'type' ), [ 'checkbox', 'radio' ] ) !== -1 ) {
								paymentDetails[ ID ] = $( this ).prop( 'checked' );
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
							} else {
								paymentDetails[ ID ] = $( this ).val();
							}
						}
<<<<<<< HEAD
					} );
=======
					});
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)

					// Always update the fragments
					if ( data && data.fragments ) {
						$.each( data.fragments, function ( key, value ) {
<<<<<<< HEAD
							if (
								! wc_checkout_form.fragments ||
								wc_checkout_form.fragments[ key ] !== value
							) {
=======
							if ( ! wc_checkout_form.fragments || wc_checkout_form.fragments[ key ] !== value ) {
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
								$( key ).replaceWith( value );
							}
							$( key ).unblock();
						} );
						wc_checkout_form.fragments = data.fragments;
					}

					// Recheck the terms and conditions box, if needed
					if ( termsCheckBoxChecked ) {
						$( '#terms' ).prop( 'checked', true );
					}

					// Fill in the payment details if possible without overwriting data if set.
					if ( ! $.isEmptyObject( paymentDetails ) ) {
<<<<<<< HEAD
						$( '.payment_box :input' ).each( function () {
							var ID = $( this ).attr( 'id' );
							if ( ID ) {
								if (
									$.inArray( $( this ).attr( 'type' ), [
										'checkbox',
										'radio',
									] ) !== -1
								) {
									$( this )
										.prop( 'checked', paymentDetails[ ID ] )
										.trigger( 'change' );
								} else if (
									$.inArray( $( this ).attr( 'type' ), [
										'select',
									] ) !== -1
								) {
									$( this )
										.val( paymentDetails[ ID ] )
										.trigger( 'change' );
								} else if (
									null !== $( this ).val() &&
									0 === $( this ).val().length
								) {
									$( this )
										.val( paymentDetails[ ID ] )
										.trigger( 'change' );
								}
							}
						} );
=======
						$( '.payment_box :input' ).each( function() {
							var ID = $( this ).attr( 'id' );
							if ( ID ) {
								if ( $.inArray( $( this ).attr( 'type' ), [ 'checkbox', 'radio' ] ) !== -1 ) {
									$( this ).prop( 'checked', paymentDetails[ ID ] ).trigger( 'change' );
								} else if ( $.inArray( $( this ).attr( 'type' ), [ 'select' ] ) !== -1 ) {
									$( this ).val( paymentDetails[ ID ] ).trigger( 'change' );
								} else if ( null !== $( this ).val() && 0 === $( this ).val().length ) {
									$( this ).val( paymentDetails[ ID ] ).trigger( 'change' );
								}
							}
						});
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
					}

					// Check for error
					if ( data && 'failure' === data.result ) {
<<<<<<< HEAD
						var $form = $( 'form.checkout' );

						// Remove notices from all sources
						$(
							'.woocommerce-error, .woocommerce-message, .is-error, .is-success'
						).remove();

						// Add new errors returned by this event
						if ( data.messages ) {
							$form.prepend(
								'<div class="woocommerce-NoticeGroup woocommerce-NoticeGroup-updateOrderReview">' +
									data.messages +
									'</div>'
							); // eslint-disable-line max-len
=======

						var $form = $( 'form.checkout' );

						// Remove notices from all sources
						$( '.woocommerce-error, .woocommerce-message, .is-error, .is-success' ).remove();

						// Add new errors returned by this event
						if ( data.messages ) {
							$form.prepend( '<div class="woocommerce-NoticeGroup woocommerce-NoticeGroup-updateOrderReview">' + data.messages + '</div>' ); // eslint-disable-line max-len
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
						} else {
							$form.prepend( data );
						}

						// Lose focus for all fields
<<<<<<< HEAD
						$form
							.find( '.input-text, select, input:checkbox' )
							.trigger( 'validate' )
							.trigger( 'blur' );
=======
						$form.find( '.input-text, select, input:checkbox' ).trigger( 'validate' ).trigger( 'blur' );
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)

						wc_checkout_form.scroll_to_notices();
					}

					// Re-init methods
					wc_checkout_form.init_payment_methods();

					// If there is no errors and the checkout update was triggered by changing the shipping method, focus its radio input.
					if (
						data &&
						'success' === data.result &&
						args.current_target &&
<<<<<<< HEAD
						args.current_target.id.indexOf( 'shipping_method' ) !==
							-1
					) {
						document
							.getElementById( args.current_target.id )
							.focus();
=======
						args.current_target.id.indexOf( 'shipping_method' ) !== -1
					) {
						document.getElementById( args.current_target.id ).focus();
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
					}

					// Fire updated_checkout event.
					$( document.body ).trigger( 'updated_checkout', [ data ] );
<<<<<<< HEAD
				},
			} );
		},
		handleUnloadEvent: function ( e ) {
=======
				}

			});
		},
		handleUnloadEvent: function( e ) {
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
			// Modern browsers have their own standard generic messages that they will display.
			// Confirm, alert, prompt or custom message are not allowed during the unload event
			// Browsers will display their own standard messages

			// Check if the browser is Internet Explorer
<<<<<<< HEAD
			if (
				navigator.userAgent.indexOf( 'MSIE' ) !== -1 ||
				!! document.documentMode
			) {
=======
			if((navigator.userAgent.indexOf('MSIE') !== -1 ) || (!!document.documentMode)) {
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
				// IE handles unload events differently than modern browsers
				e.preventDefault();
				return undefined;
			}

			return true;
		},
<<<<<<< HEAD
		attachUnloadEventsOnSubmit: function () {
			$( window ).on( 'beforeunload', this.handleUnloadEvent );
		},
		detachUnloadEventsOnSubmit: function () {
			$( window ).off( 'beforeunload', this.handleUnloadEvent );
		},
		blockOnSubmit: function ( $form ) {
			var isBlocked = $form.data( 'blockUI.isBlocked' );

			if ( 1 !== isBlocked ) {
				$form.block( {
					message: null,
					overlayCSS: {
						background: '#fff',
						opacity: 0.6,
					},
				} );
			}
		},
		submitOrder: function () {
			wc_checkout_form.blockOnSubmit( $( this ) );
		},
		submit: function () {
=======
		attachUnloadEventsOnSubmit: function() {
			$( window ).on('beforeunload', this.handleUnloadEvent);
		},
		detachUnloadEventsOnSubmit: function() {
			$( window ).off('beforeunload', this.handleUnloadEvent);
		},
		blockOnSubmit: function( $form ) {
			var isBlocked = $form.data( 'blockUI.isBlocked' );

			if ( 1 !== isBlocked ) {
				$form.block({
					message: null,
					overlayCSS: {
						background: '#fff',
						opacity: 0.6
					}
				});
			}
		},
		submitOrder: function() {
			wc_checkout_form.blockOnSubmit( $( this ) );
		},
		submit: function() {
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
			wc_checkout_form.reset_update_checkout_timer();
			var $form = $( this );

			if ( $form.is( '.processing' ) ) {
				return false;
			}

			// Trigger a handler to let gateways manipulate the checkout if needed
<<<<<<< HEAD
			if (
				$form.triggerHandler( 'checkout_place_order', [
					wc_checkout_form,
				] ) !== false &&
				$form.triggerHandler(
					'checkout_place_order_' +
						wc_checkout_form.get_payment_method(),
					[ wc_checkout_form ]
				) !== false
			) {
=======
			// eslint-disable-next-line max-len
			if ( $form.triggerHandler( 'checkout_place_order', [ wc_checkout_form ] ) !== false && $form.triggerHandler( 'checkout_place_order_' + wc_checkout_form.get_payment_method(), [ wc_checkout_form ] ) !== false ) {

>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
				$form.addClass( 'processing' );

				wc_checkout_form.blockOnSubmit( $form );

				// Attach event to block reloading the page when the form has been submitted
				wc_checkout_form.attachUnloadEventsOnSubmit();

				// ajaxSetup is global, but we use it to ensure JSON is valid once returned.
				$.ajaxSetup( {
<<<<<<< HEAD
					dataFilter: function ( raw_response, dataType ) {
=======
					dataFilter: function( raw_response, dataType ) {
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
						// We only want to work with JSON
						if ( 'json' !== dataType ) {
							return raw_response;
						}

						if ( wc_checkout_form.is_valid_json( raw_response ) ) {
							return raw_response;
						} else {
							// Attempt to fix the malformed JSON
<<<<<<< HEAD
							var maybe_valid_json =
								raw_response.match( /{"result.*}/ );

							if ( null === maybe_valid_json ) {
								console.log(
									'Unable to fix malformed JSON #1'
								);
							} else if (
								wc_checkout_form.is_valid_json(
									maybe_valid_json[ 0 ]
								)
							) {
								console.log(
									'Fixed malformed JSON. Original:'
								);
								console.log( raw_response );
								raw_response = maybe_valid_json[ 0 ];
							} else {
								console.log(
									'Unable to fix malformed JSON #2'
								);
=======
							var maybe_valid_json = raw_response.match( /{"result.*}/ );

							if ( null === maybe_valid_json ) {
								console.log( 'Unable to fix malformed JSON #1' );
							} else if ( wc_checkout_form.is_valid_json( maybe_valid_json[0] ) ) {
								console.log( 'Fixed malformed JSON. Original:' );
								console.log( raw_response );
								raw_response = maybe_valid_json[0];
							} else {
								console.log( 'Unable to fix malformed JSON #2' );
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
							}
						}

						return raw_response;
<<<<<<< HEAD
					},
				} );

				$.ajax( {
					type: 'POST',
					url: wc_checkout_params.checkout_url,
					data: $form.serialize(),
					dataType: 'json',
					success: function ( result ) {
=======
					}
				} );

				$.ajax({
					type:		'POST',
					url:		wc_checkout_params.checkout_url,
					data:		$form.serialize(),
					dataType:   'json',
					success:	function( result ) {
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
						// Detach the unload handler that prevents a reload / redirect
						wc_checkout_form.detachUnloadEventsOnSubmit();

						$( '.checkout-inline-error-message' ).remove();

						try {
<<<<<<< HEAD
							if (
								'success' === result.result &&
								$form.triggerHandler(
									'checkout_place_order_success',
									[ result, wc_checkout_form ]
								) !== false
							) {
								if (
									-1 ===
										result.redirect.indexOf( 'https://' ) ||
									-1 === result.redirect.indexOf( 'http://' )
								) {
									window.location = result.redirect;
								} else {
									window.location = decodeURI(
										result.redirect
									);
=======
							if ( 'success' === result.result &&
								$form.triggerHandler( 'checkout_place_order_success', [ result, wc_checkout_form ] ) !== false ) {
								if ( -1 === result.redirect.indexOf( 'https://' ) || -1 === result.redirect.indexOf( 'http://' ) ) {
									window.location = result.redirect;
								} else {
									window.location = decodeURI( result.redirect );
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
								}
							} else if ( 'failure' === result.result ) {
								throw 'Result failure';
							} else {
								throw 'Invalid response';
							}
<<<<<<< HEAD
						} catch ( err ) {
=======
						} catch( err ) {
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
							// Reload page
							if ( true === result.reload ) {
								window.location.reload();
								return;
							}

							// Trigger update in case we need a fresh nonce
							if ( true === result.refresh ) {
								$( document.body ).trigger( 'update_checkout' );
							}

							// Add new errors
							if ( result.messages ) {
								var $msgs = $( result.messages )
									// The error notice template (plugins/woocommerce/templates/notices/error.php)
									// adds the role="alert" to a list HTML element. This becomes a problem in this context
									// because screen readers won't read the list content correctly if its role is not "list".
									.removeAttr( 'role' )
									.attr( 'tabindex', '-1' );
<<<<<<< HEAD
								var $msgsWithLink =
									wc_checkout_form.wrapMessagesInsideLink(
										$msgs
									);
								var $msgsWrapper = $(
									'<div role="alert"></div>'
								).append( $msgsWithLink );

								wc_checkout_form.submit_error(
									$msgsWrapper.prop( 'outerHTML' )
								);
								wc_checkout_form.show_inline_errors( $msgs );
							} else {
								wc_checkout_form.submit_error(
									'<div class="woocommerce-error">' +
										wc_checkout_params.i18n_checkout_error +
										'</div>'
								); // eslint-disable-line max-len
							}
						}
					},
					error: function ( jqXHR, textStatus, errorThrown ) {
=======
								var $msgsWithLink = wc_checkout_form.wrapMessagesInsideLink( $msgs );
								var $msgsWrapper = $( '<div role="alert"></div>' ).append( $msgsWithLink );

								wc_checkout_form.submit_error( $msgsWrapper.prop( 'outerHTML' ) );
								wc_checkout_form.show_inline_errors( $msgs );
							} else {
								wc_checkout_form.submit_error( '<div class="woocommerce-error">' + wc_checkout_params.i18n_checkout_error + '</div>' ); // eslint-disable-line max-len
							}
						}
					},
					error:	function( jqXHR, textStatus, errorThrown ) {
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
						// Detach the unload handler that prevents a reload / redirect
						wc_checkout_form.detachUnloadEventsOnSubmit();

						// This is just a technical error fallback. i18_checkout_error is expected to be always defined and localized.
						var errorMessage = errorThrown;

						if (
							typeof wc_checkout_params === 'object' &&
							wc_checkout_params !== null &&
<<<<<<< HEAD
							wc_checkout_params.hasOwnProperty(
								'i18n_checkout_error'
							) &&
							typeof wc_checkout_params.i18n_checkout_error ===
								'string' &&
							wc_checkout_params.i18n_checkout_error.trim() !== ''
						) {
							errorMessage =
								wc_checkout_params.i18n_checkout_error;
						}

						wc_checkout_form.submit_error(
							'<div class="woocommerce-error">' +
								errorMessage +
								'</div>'
						);
					},
				} );
=======
							wc_checkout_params.hasOwnProperty( 'i18n_checkout_error' ) &&
							typeof wc_checkout_params.i18n_checkout_error === 'string' &&
							wc_checkout_params.i18n_checkout_error.trim() !== ''
						) {
							errorMessage = wc_checkout_params.i18n_checkout_error;
						}

						wc_checkout_form.submit_error(
							'<div class="woocommerce-error">' + errorMessage + '</div>'
						);
					}
				});
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
			}

			return false;
		},
<<<<<<< HEAD
		submit_error: function ( error_message ) {
			$(
				'.woocommerce-NoticeGroup-checkout, .woocommerce-error, .woocommerce-message, .is-error, .is-success'
			).remove();
			wc_checkout_form.$checkout_form.prepend(
				'<div class="woocommerce-NoticeGroup woocommerce-NoticeGroup-checkout">' +
					error_message +
					'</div>'
			); // eslint-disable-line max-len
			wc_checkout_form.$checkout_form
				.removeClass( 'processing' )
				.unblock();
			wc_checkout_form.$checkout_form
				.find( '.input-text, select, input:checkbox' )
				.trigger( 'validate' )
				.trigger( 'blur' );
			wc_checkout_form.scroll_to_notices();
			wc_checkout_form.$checkout_form
				.find(
					'.woocommerce-error[tabindex="-1"], .wc-block-components-notice-banner.is-error[tabindex="-1"]'
				)
				.focus();
			$( document.body ).trigger( 'checkout_error', [ error_message ] );
		},
		wrapMessagesInsideLink: function ( $msgs ) {
			$msgs.find( 'li[data-id]' ).each( function () {
				const $this = $( this );
				const dataId = $this.attr( 'data-id' );
				if ( dataId ) {
					const $link = $( '<a>', {
						href: '#' + dataId,
						html: $this.html(),
=======
		submit_error: function( error_message ) {
			$( '.woocommerce-NoticeGroup-checkout, .woocommerce-error, .woocommerce-message, .is-error, .is-success' ).remove();
			wc_checkout_form.$checkout_form.prepend( '<div class="woocommerce-NoticeGroup woocommerce-NoticeGroup-checkout">' + error_message + '</div>' ); // eslint-disable-line max-len
			wc_checkout_form.$checkout_form.removeClass( 'processing' ).unblock();
			wc_checkout_form.$checkout_form.find( '.input-text, select, input:checkbox' ).trigger( 'validate' ).trigger( 'blur' );
			wc_checkout_form.scroll_to_notices();
			wc_checkout_form.$checkout_form.find(
				'.woocommerce-error[tabindex="-1"], .wc-block-components-notice-banner.is-error[tabindex="-1"]' )
			.focus();
			$( document.body ).trigger( 'checkout_error' , [ error_message ] );
		},
		wrapMessagesInsideLink: function( $msgs ) {
			$msgs.find( 'li[data-id]' ).each( function() {
				const $this = $( this );
				const dataId = $this.attr( 'data-id' );
				if ( dataId ) {
					const $link = $('<a>', {
						href: '#' + dataId,
						html: $this.html()
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
					} );
					$this.empty().append( $link );
				}
			} );

			return $msgs;
		},
<<<<<<< HEAD
		show_inline_errors: function ( $messages ) {
			$messages.find( 'li[data-id]' ).each( function () {
=======
		show_inline_errors: function( $messages ) {
			$messages.find( 'li[data-id]' ).each( function() {
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
				const $this = $( this );
				const dataId = $this.attr( 'data-id' );
				const $field = $( '#' + dataId );

				if ( $field.length === 1 ) {
					const descriptionId = dataId + '_description';
					const msg = $this.text().trim();
					const $formRow = $field.closest( '.form-row' );

					const errorMessage = document.createElement( 'p' );
					errorMessage.id = descriptionId;
					errorMessage.className = 'checkout-inline-error-message';
					errorMessage.textContent = msg;

					if ( $formRow && errorMessage.textContent.length > 0 ) {
						$formRow.append( errorMessage );
					}

					$field.attr( 'aria-describedby', descriptionId );
					$field.attr( 'aria-invalid', 'true' );
				}
			} );
		},
<<<<<<< HEAD
		scroll_to_notices: function () {
			var scrollElement = $(
				'.woocommerce-NoticeGroup-updateOrderReview, .woocommerce-NoticeGroup-checkout'
			);
=======
		scroll_to_notices: function() {
			var scrollElement = $( '.woocommerce-NoticeGroup-updateOrderReview, .woocommerce-NoticeGroup-checkout' );
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)

			if ( ! scrollElement.length ) {
				scrollElement = $( 'form.checkout' );
			}
			$.scroll_to_notices( scrollElement );
<<<<<<< HEAD
		},
	};

	var wc_checkout_coupons = {
		init: function () {
			$( document.body ).on(
				'click',
				'a.showcoupon',
				this.show_coupon_form
			);
			$( document.body ).on(
				'click',
				'.woocommerce-remove-coupon',
				this.remove_coupon
			);
			$( document.body ).on(
				'keydown',
				'.woocommerce-remove-coupon',
				this.on_keydown_remove_coupon
			);
			$( document.body ).on(
				'change input',
				'#coupon_code',
				this.remove_coupon_error
			);
			$( 'form.checkout_coupon' )
				.hide()
				.on( 'submit', this.submit.bind( this ) );
		},
		show_coupon_form: function () {
			var $showcoupon = $( this );

			$( '.checkout_coupon' ).slideToggle( 400, function () {
=======
		}
	};

	var wc_checkout_coupons = {
		init: function() {
			$( document.body ).on( 'click', 'a.showcoupon', this.show_coupon_form );
			$( document.body ).on( 'click', '.woocommerce-remove-coupon', this.remove_coupon );
			$( document.body ).on( 'blur change input', '#coupon_code', this.remove_coupon_error );
			$( 'form.checkout_coupon' ).hide().on( 'submit', this.submit.bind( this ) );
		},
		show_coupon_form: function() {
			var $showcoupon = $( this );

			$( '.checkout_coupon' ).slideToggle( 400, function() {
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
				var $coupon_form = $( this );

				if ( $coupon_form.is( ':visible' ) ) {
					$showcoupon.attr( 'aria-expanded', 'true' );
					$coupon_form.find( ':input:eq(0)' ).trigger( 'focus' );
				} else {
					$showcoupon.attr( 'aria-expanded', 'false' );
				}
<<<<<<< HEAD
			} );
			return false;
		},
		show_coupon_error: function ( html_element, $target ) {
=======
			});
			return false;
		},
		show_coupon_error: function( html_element, $target ) {
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
			if ( $target.length === 0 ) {
				return;
			}

<<<<<<< HEAD
			this.remove_coupon_error();

=======
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
			var msg = $( $.parseHTML( html_element ) ).text().trim();

			if ( msg === '' ) {
				return;
			}

<<<<<<< HEAD
			$target
				.find( '#coupon_code' )
=======
			$target.find( '#coupon_code' )
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
				.focus()
				.addClass( 'has-error' )
				.attr( 'aria-invalid', 'true' )
				.attr( 'aria-describedby', 'coupon-error-notice' );

<<<<<<< HEAD
			$( '<span>', {
				class: 'coupon-error-notice',
				id: 'coupon-error-notice',
				role: 'alert',
				text: msg,
			} ).appendTo( $target );
		},
		remove_coupon_error: function () {
			var $coupon_field = $( '#coupon_code' );

			if ( $coupon_field.length === 0 ) {
				return;
			}

			$coupon_field
=======
			$('<span>', {
				class: 'coupon-error-notice',
				id: 'coupon-error-notice',
				role: 'alert',
				text: msg
			}).appendTo($target);
		},
		remove_coupon_error: function( evt ) {
			$( evt.currentTarget )
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
				.removeClass( 'has-error' )
				.removeAttr( 'aria-invalid' )
				.removeAttr( 'aria-describedby' )
				.next( '.coupon-error-notice' )
				.remove();
		},
<<<<<<< HEAD

		clear_coupon_input: function () {
			const $coupon_field = $( '#coupon_code' );
			$coupon_field
				.val('')
				.removeClass('has-error')
				.removeAttr('aria-invalid')
				.removeAttr('aria-describedby')
				.next('.coupon-error-notice')
				.remove();
		},
		submit: function ( evt ) {
=======
		submit: function( evt ) {
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
			var $form = $( evt.currentTarget );
			var $coupon_field = $form.find( '#coupon_code' );
			var self = this;

<<<<<<< HEAD
			self.remove_coupon_error();

=======
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
			if ( $form.is( '.processing' ) ) {
				return false;
			}

<<<<<<< HEAD
			$form.addClass( 'processing' ).block( {
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6,
				},
			} );

			var data = {
				security: wc_checkout_params.apply_coupon_nonce,
				coupon_code: $form.find( 'input[name="coupon_code"]' ).val(),
				billing_email: wc_checkout_form.$checkout_form
					.find( 'input[name="billing_email"]' )
					.val(),
			};

			$.ajax( {
				type: 'POST',
				url: wc_checkout_params.wc_ajax_url
					.toString()
					.replace( '%%endpoint%%', 'apply_coupon' ),
				data: data,
				success: function ( response ) {
					$(
						'.woocommerce-error, .woocommerce-message, .is-error, .is-success, .checkout-inline-error-message'
					).remove();
=======
			$form.addClass( 'processing' ).block({
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
			});

			var data = {
				security: wc_checkout_params.apply_coupon_nonce,
				coupon_code: $form.find('input[name="coupon_code"]').val(),
				billing_email: wc_checkout_form.$checkout_form.find('input[name="billing_email"]').val()
			};

			$.ajax({
				type:		'POST',
				url:		wc_checkout_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'apply_coupon' ),
				data:		data,
				success:	function( response ) {
					$( '.woocommerce-error, .woocommerce-message, .is-error, .is-success, .checkout-inline-error-message' ).remove();
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
					$form.removeClass( 'processing' ).unblock();

					if ( response ) {
						// We only want to show coupon notices if they are no errors.
						// Coupon errors are shown under the input.
<<<<<<< HEAD
						if (
							response.indexOf( 'woocommerce-error' ) === -1 &&
							response.indexOf( 'is-error' ) === -1
						) {
							$form.slideUp( 400, function () {
								$( 'a.showcoupon' ).attr(
									'aria-expanded',
									'false'
								);
								$form.before( response );
							} );
							self.clear_coupon_input();
						} else {
							self.show_coupon_error(
								response,
								$coupon_field.parent()
							);
						}

						$( document.body ).trigger(
							'applied_coupon_in_checkout',
							[ data.coupon_code ]
						);
						$( document.body ).trigger( 'update_checkout', {
							update_shipping_method: false,
						} );
					}
				},
				dataType: 'html',
			} );

			return false;
		},
		remove_coupon: function ( e ) {
			e.preventDefault();

			var container = $( this ).parents(
					'.woocommerce-checkout-review-order'
				),
				coupon = $( this ).data( 'coupon' );

			container.addClass( 'processing' ).block( {
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6,
				},
			} );

			var data = {
				security: wc_checkout_params.remove_coupon_nonce,
				coupon: coupon,
			};

			$.ajax( {
				type: 'POST',
				url: wc_checkout_params.wc_ajax_url
					.toString()
					.replace( '%%endpoint%%', 'remove_coupon' ),
				data: data,
				success: function ( code ) {
					$(
						'.woocommerce-error, .woocommerce-message, .is-error, .is-success'
					).remove();
=======
						if ( response.indexOf( 'woocommerce-error' ) === -1 && response.indexOf( 'is-error' ) === -1 ) {
							$form.slideUp( 400, function() {
								$( 'a.showcoupon' ).attr( 'aria-expanded', 'false' );
								$form.before( response );
							} );
						} else {
							self.show_coupon_error( response, $coupon_field.parent() );
						}

						$( document.body ).trigger( 'applied_coupon_in_checkout', [ data.coupon_code ] );
						$( document.body ).trigger( 'update_checkout', { update_shipping_method: false } );
					}
				},
				dataType: 'html'
			});

			return false;
		},
		remove_coupon: function( e ) {
			e.preventDefault();

			var container = $( this ).parents( '.woocommerce-checkout-review-order' ),
				coupon    = $( this ).data( 'coupon' );

			container.addClass( 'processing' ).block({
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
			});

			var data = {
				security: wc_checkout_params.remove_coupon_nonce,
				coupon:   coupon
			};

			$.ajax({
				type:    'POST',
				url:     wc_checkout_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'remove_coupon' ),
				data:    data,
				success: function( code ) {
					$( '.woocommerce-error, .woocommerce-message, .is-error, .is-success' ).remove();
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
					container.removeClass( 'processing' ).unblock();

					if ( code ) {
						$( 'form.woocommerce-checkout' ).before( code );

<<<<<<< HEAD
						$( document.body ).trigger(
							'removed_coupon_in_checkout',
							[ data.coupon ]
						);
						$( document.body ).trigger( 'update_checkout', {
							update_shipping_method: false,
						} );

						// Remove coupon code from coupon field
						wc_checkout_coupons.clear_coupon_input();
						$( 'form.checkout_coupon' ).slideUp( 400, function () {
							$( 'a.showcoupon' ).attr(
								'aria-expanded',
								'false'
							);
=======
						$( document.body ).trigger( 'removed_coupon_in_checkout', [ data.coupon ] );
						$( document.body ).trigger( 'update_checkout', { update_shipping_method: false } );

						// Remove coupon code from coupon field
						$( 'form.checkout_coupon' ).find( 'input[name="coupon_code"]' ).val( '' );
						$( 'form.checkout_coupon' ).slideUp( 400, function() {
							$( 'a.showcoupon' ).attr( 'aria-expanded', 'false' );
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
						} );
					}
				},
				error: function ( jqXHR ) {
					if ( wc_checkout_params.debug_mode ) {
						/* jshint devel: true */
						console.log( jqXHR.responseText );
					}
				},
<<<<<<< HEAD
				dataType: 'html',
			} );
		},
		/**
		 * Handle when pressing the Space key on the remove coupon link.
		 * This is necessary because the link got the role="button" attribute
		 * and needs to act like a button.
		 *
		 * @param {Object} e The JQuery event
		 */
		on_keydown_remove_coupon: function ( e ) {
			if ( e.key === ' ' ) {
				e.preventDefault();
				$( this ).trigger( 'click' );
			}
		},
	};

	var wc_checkout_login_form = {
		init: function () {
			$( document.body ).on(
				'click',
				'a.showlogin',
				this.show_login_form
			);
		},
		show_login_form: function () {
			var $form = $( 'form.login, form.woocommerce-form--login' );
			if ( $form.is( ':visible' ) ) {
				// If already visible, hide it.
				$form.slideToggle( {
					duration: 400,
				} );
			} else {
				// If not visible, show it and then scroll
				$form.slideToggle( {
					duration: 400,
					complete: function () {
						if ( $form.is( ':visible' ) ) {
							$( 'html, body' ).animate(
								{
									scrollTop: $form.offset().top - 50,
								},
								300
							);
						}
					},
				} );
			}
			return false;
		},
	};

	var wc_terms_toggle = {
		init: function () {
			$( document.body ).on(
				'click',
				'a.woocommerce-terms-and-conditions-link',
				this.toggle_terms
			);
		},

		toggle_terms: function () {
			if ( $( '.woocommerce-terms-and-conditions' ).length ) {
				$( '.woocommerce-terms-and-conditions' ).slideToggle(
					function () {
						var link_toggle = $(
							'.woocommerce-terms-and-conditions-link'
						);

						if (
							$( '.woocommerce-terms-and-conditions' ).is(
								':visible'
							)
						) {
							link_toggle.addClass(
								'woocommerce-terms-and-conditions-link--open'
							);
							link_toggle.removeClass(
								'woocommerce-terms-and-conditions-link--closed'
							);
						} else {
							link_toggle.removeClass(
								'woocommerce-terms-and-conditions-link--open'
							);
							link_toggle.addClass(
								'woocommerce-terms-and-conditions-link--closed'
							);
						}
					}
				);

				return false;
			}
		},
=======
				dataType: 'html'
			});
		}
	};

	var wc_checkout_login_form = {
		init: function() {
			$( document.body ).on( 'click', 'a.showlogin', this.show_login_form );
		},
		show_login_form: function() {
			$( 'form.login, form.woocommerce-form--login' ).slideToggle();
			return false;
		}
	};

	var wc_terms_toggle = {
		init: function() {
			$( document.body ).on( 'click', 'a.woocommerce-terms-and-conditions-link', this.toggle_terms );
		},

		toggle_terms: function() {
			if ( $( '.woocommerce-terms-and-conditions' ).length ) {
				$( '.woocommerce-terms-and-conditions' ).slideToggle( function() {
					var link_toggle = $( '.woocommerce-terms-and-conditions-link' );

					if ( $( '.woocommerce-terms-and-conditions' ).is( ':visible' ) ) {
						link_toggle.addClass( 'woocommerce-terms-and-conditions-link--open' );
						link_toggle.removeClass( 'woocommerce-terms-and-conditions-link--closed' );
					} else {
						link_toggle.removeClass( 'woocommerce-terms-and-conditions-link--open' );
						link_toggle.addClass( 'woocommerce-terms-and-conditions-link--closed' );
					}
				} );

				return false;
			}
		}
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
	};

	wc_checkout_form.init();
	wc_checkout_coupons.init();
	wc_checkout_login_form.init();
	wc_terms_toggle.init();
<<<<<<< HEAD
} );
=======
});
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
