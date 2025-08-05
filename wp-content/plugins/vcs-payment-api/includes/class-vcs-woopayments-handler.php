<?php
/**
 * VCS WooPayments Handler
 * 
 * Handles WooPayments payment processing
 */

if (!defined('ABSPATH')) {
    exit;
}

class VCS_WooPayments_Handler {
    
    private $publishable_key;
    private $secret_key;
    private $is_test_mode;
    private $api_base_url;
    
    public function __construct() {
        $this->init_settings();
    }
    
    /**
     * Initialize WooPayments settings
     */
    private function init_settings() {
        // Get WooPayments settings
        $woopayments_settings = get_option('woocommerce_woocommerce_payments_settings', array());
        
        $this->is_test_mode = isset($woopayments_settings['test_mode']) ? $woopayments_settings['test_mode'] === 'yes' : true;
        
        if ($this->is_test_mode) {
            $this->publishable_key = isset($woopayments_settings['test_publishable_key']) ? $woopayments_settings['test_publishable_key'] : '';
            $this->secret_key = isset($woopayments_settings['test_secret_key']) ? $woopayments_settings['test_secret_key'] : '';
        } else {
            $this->publishable_key = isset($woopayments_settings['publishable_key']) ? $woopayments_settings['publishable_key'] : '';
            $this->secret_key = isset($woopayments_settings['secret_key']) ? $woopayments_settings['secret_key'] : '';
        }
        
        $this->api_base_url = 'https://api.stripe.com/v1';
    }
    
    /**
     * Process WooPayments payment
     */
    public function process_payment($params) {
        try {
            // Create payment intent
            $payment_intent = $this->create_payment_intent($params);
            
            // Create WooCommerce order
            $wc_order = $this->create_wc_order($params, 'woopayments');
            
            // Store Stripe payment intent ID
            $wc_order->update_meta_data('_stripe_payment_intent_id', $payment_intent['id']);
            $wc_order->save();
            
            return array(
                'success' => true,
                'order_id' => $wc_order->get_id(),
                'payment_intent_id' => $payment_intent['id'],
                'client_secret' => $payment_intent['client_secret'],
                'status' => $payment_intent['status'],
                'amount' => $payment_intent['amount'],
                'currency' => $payment_intent['currency'],
                'message' => 'WooPayments payment intent created successfully'
            );
            
        } catch (Exception $e) {
            return array(
                'success' => false,
                'error' => $e->getMessage()
            );
        }
    }
    
    /**
     * Create Stripe payment intent
     */
    private function create_payment_intent($params) {
        $payment_intent_data = array(
            'amount' => intval($params['amount'] * 100), // Convert to cents
            'currency' => strtolower($params['currency']),
            'payment_method' => $params['payment_method_id'],
            'confirm' => 'true',
            'return_url' => isset($params['return_url']) ? $params['return_url'] : home_url('/'),
            'metadata' => array(
                'order_id' => isset($params['order_id']) ? $params['order_id'] : '',
                'description' => isset($params['description']) ? $params['description'] : 'Payment via VCS Payment API'
            )
        );
        
        // Add customer if provided
        if (isset($params['customer_id'])) {
            $payment_intent_data['customer'] = $params['customer_id'];
        }
        
        $response = wp_remote_post($this->api_base_url . '/payment_intents', array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $this->secret_key,
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Accept' => 'application/json'
            ),
            'body' => http_build_query($payment_intent_data),
            'timeout' => 30
        ));
        
        if (is_wp_error($response)) {
            throw new Exception('Failed to create payment intent: ' . $response->get_error_message());
        }
        
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        if (wp_remote_retrieve_response_code($response) !== 200) {
            throw new Exception('Stripe API error: ' . (isset($data['error']['message']) ? $data['error']['message'] : 'Unknown error'));
        }
        
        return $data;
    }
    
    /**
     * Confirm payment intent
     */
    public function confirm_payment_intent($payment_intent_id) {
        $response = wp_remote_post($this->api_base_url . '/payment_intents/' . $payment_intent_id . '/confirm', array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $this->secret_key,
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Accept' => 'application/json'
            ),
            'timeout' => 30
        ));
        
        if (is_wp_error($response)) {
            throw new Exception('Failed to confirm payment intent: ' . $response->get_error_message());
        }
        
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        if (wp_remote_retrieve_response_code($response) !== 200) {
            throw new Exception('Stripe API error: ' . (isset($data['error']['message']) ? $data['error']['message'] : 'Unknown error'));
        }
        
        return $data;
    }
    
    /**
     * Get payment intent status
     */
    public function get_payment_intent_status($payment_intent_id) {
        $response = wp_remote_get($this->api_base_url . '/payment_intents/' . $payment_intent_id, array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $this->secret_key,
                'Accept' => 'application/json'
            ),
            'timeout' => 30
        ));
        
        if (is_wp_error($response)) {
            throw new Exception('Failed to get payment intent: ' . $response->get_error_message());
        }
        
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        if (wp_remote_retrieve_response_code($response) !== 200) {
            throw new Exception('Stripe API error: ' . (isset($data['error']['message']) ? $data['error']['message'] : 'Unknown error'));
        }
        
        return $data;
    }
    
    /**
     * Create customer
     */
    public function create_customer($email, $name = '') {
        $customer_data = array(
            'email' => $email
        );
        
        if ($name) {
            $customer_data['name'] = $name;
        }
        
        $response = wp_remote_post($this->api_base_url . '/customers', array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $this->secret_key,
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Accept' => 'application/json'
            ),
            'body' => http_build_query($customer_data),
            'timeout' => 30
        ));
        
        if (is_wp_error($response)) {
            throw new Exception('Failed to create customer: ' . $response->get_error_message());
        }
        
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        if (wp_remote_retrieve_response_code($response) !== 200) {
            throw new Exception('Stripe API error: ' . (isset($data['error']['message']) ? $data['error']['message'] : 'Unknown error'));
        }
        
        return $data;
    }
    
    /**
     * Create payment method
     */
    public function create_payment_method($card_data) {
        $payment_method_data = array(
            'type' => 'card',
            'card' => array(
                'number' => $card_data['number'],
                'exp_month' => $card_data['exp_month'],
                'exp_year' => $card_data['exp_year'],
                'cvc' => $card_data['cvc']
            )
        );
        
        $response = wp_remote_post($this->api_base_url . '/payment_methods', array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $this->secret_key,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ),
            'body' => json_encode($payment_method_data),
            'timeout' => 30
        ));
        
        if (is_wp_error($response)) {
            throw new Exception('Failed to create payment method: ' . $response->get_error_message());
        }
        
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        if (wp_remote_retrieve_response_code($response) !== 200) {
            throw new Exception('Stripe API error: ' . (isset($data['error']['message']) ? $data['error']['message'] : 'Unknown error'));
        }
        
        return $data;
    }
    
    /**
     * Create WooCommerce order
     */
    private function create_wc_order($params, $payment_method) {
        $order = wc_create_order();
        
        // Add a dummy product to the order
        $product_id = $this->get_or_create_dummy_product();
        $order->add_product(wc_get_product($product_id), 1, array(
            'subtotal' => $params['amount'],
            'total' => $params['amount']
        ));
        
        // Set order details
        $order->set_payment_method($payment_method);
        $order->set_payment_method_title($this->get_payment_method_title($payment_method));
        $order->set_total($params['amount']);
        $order->set_currency($params['currency']);
        $order->set_status('pending');
        
        if (isset($params['order_id'])) {
            $order->update_meta_data('_external_order_id', $params['order_id']);
        }
        
        $order->save();
        
        return $order;
    }
    
    /**
     * Get or create dummy product for orders
     */
    private function get_or_create_dummy_product() {
        $product_id = get_option('vcs_payment_api_dummy_product_id');
        
        if (!$product_id || !wc_get_product($product_id)) {
            $product = new WC_Product_Simple();
            $product->set_name('API Payment Product');
            $product->set_status('private');
            $product->set_catalog_visibility('hidden');
            $product->set_price(0);
            $product->set_regular_price(0);
            $product->set_virtual(true);
            $product->set_downloadable(false);
            
            $product_id = $product->save();
            update_option('vcs_payment_api_dummy_product_id', $product_id);
        }
        
        return $product_id;
    }
    
    /**
     * Get payment method title
     */
    private function get_payment_method_title($payment_method) {
        $titles = array(
            'woopayments' => 'WooPayments (Stripe)'
        );
        
        return isset($titles[$payment_method]) ? $titles[$payment_method] : $payment_method;
    }
    
    /**
     * Handle Stripe webhook
     */
    public function handle_webhook($request) {
        $body = $request->get_body();
        $data = json_decode($body, true);
        
        // Verify webhook signature (implement proper verification)
        if (!$this->verify_webhook_signature($request)) {
            return array('success' => false, 'error' => 'Invalid webhook signature');
        }
        
        $event_type = $data['type'] ?? '';
        
        switch ($event_type) {
            case 'payment_intent.succeeded':
                return $this->handle_payment_succeeded($data);
            case 'payment_intent.payment_failed':
                return $this->handle_payment_failed($data);
            default:
                return array('success' => true, 'message' => 'Webhook received: ' . $event_type);
        }
    }
    
    /**
     * Verify webhook signature
     */
    private function verify_webhook_signature($request) {
        // Implement proper webhook signature verification for Stripe
        // For now, return true (you should implement proper verification)
        return true;
    }
    
    /**
     * Handle payment succeeded webhook
     */
    private function handle_payment_succeeded($data) {
        $payment_intent_id = $data['data']['object']['id'] ?? '';
        
        if ($payment_intent_id) {
            $orders = wc_get_orders(array(
                'meta_key' => '_stripe_payment_intent_id',
                'meta_value' => $payment_intent_id,
                'limit' => 1
            ));
            
            if (!empty($orders)) {
                $order = $orders[0];
                $order->payment_complete();
                $order->set_status('processing');
                $order->save();
                
                return array('success' => true, 'message' => 'Payment completed for order ' . $order->get_id());
            }
        }
        
        return array('success' => false, 'error' => 'Order not found');
    }
    
    /**
     * Handle payment failed webhook
     */
    private function handle_payment_failed($data) {
        $payment_intent_id = $data['data']['object']['id'] ?? '';
        
        if ($payment_intent_id) {
            $orders = wc_get_orders(array(
                'meta_key' => '_stripe_payment_intent_id',
                'meta_value' => $payment_intent_id,
                'limit' => 1
            ));
            
            if (!empty($orders)) {
                $order = $orders[0];
                $order->set_status('failed');
                $order->save();
                
                return array('success' => true, 'message' => 'Payment failed for order ' . $order->get_id());
            }
        }
        
        return array('success' => false, 'error' => 'Order not found');
    }
    
    /**
     * Get supported payment methods
     */
    public function get_supported_payment_methods() {
        return array(
            'card' => 'Credit/Debit Card',
            'sepa_debit' => 'SEPA Direct Debit',
            'ideal' => 'iDEAL',
            'sofort' => 'Sofort',
            'bancontact' => 'Bancontact',
            'eps' => 'EPS',
            'giropay' => 'Giropay'
        );
    }
    
    /**
     * Process refund
     */
    public function process_refund($payment_intent_id, $amount = null) {
        $refund_data = array();
        
        if ($amount) {
            $refund_data['amount'] = intval($amount * 100); // Convert to cents
        }
        
        $response = wp_remote_post($this->api_base_url . '/refunds', array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $this->secret_key,
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Accept' => 'application/json'
            ),
            'body' => http_build_query(array_merge($refund_data, array('payment_intent' => $payment_intent_id))),
            'timeout' => 30
        ));
        
        if (is_wp_error($response)) {
            throw new Exception('Failed to process refund: ' . $response->get_error_message());
        }
        
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        if (wp_remote_retrieve_response_code($response) !== 200) {
            throw new Exception('Stripe API error: ' . (isset($data['error']['message']) ? $data['error']['message'] : 'Unknown error'));
        }
        
        return $data;
    }
} 