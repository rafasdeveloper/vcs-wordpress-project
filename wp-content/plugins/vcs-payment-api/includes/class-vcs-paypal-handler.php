<?php
/**
 * VCS PayPal Handler
 * 
 * Handles PayPal payment processing
 */

if (!defined('ABSPATH')) {
    exit;
}

class VCS_PayPal_Handler {
    
    private $client_id;
    private $client_secret;
    private $is_sandbox;
    private $api_base_url;
    
    public function __construct() {
        $this->init_settings();
    }
    
    /**
     * Initialize PayPal settings
     */
    private function init_settings() {
        // Get PayPal settings from WooCommerce PayPal Payments plugin
        $paypal_settings = get_option('woocommerce_ppcp-gateway_settings', array());
        
        // Log settings for debugging
        VCS_Logger::log('Loaded PayPal settings: ' . print_r($paypal_settings, true));
        
        $this->is_sandbox = isset($paypal_settings['sandbox']) && $paypal_settings['sandbox'] === 'yes';
        
        if ($this->is_sandbox) {
            $this->client_id = isset($paypal_settings['sandbox_client_id']) ? $paypal_settings['sandbox_client_id'] : '';
            $this->client_secret = isset($paypal_settings['sandbox_client_secret']) ? $paypal_settings['sandbox_client_secret'] : '';
            $this->api_base_url = 'https://api-m.sandbox.paypal.com';
        } else {
            $this->client_id = isset($paypal_settings['client_id']) ? $paypal_settings['client_id'] : '';
            $this->client_secret = isset($paypal_settings['client_secret']) ? $paypal_settings['client_secret'] : '';
            $this->api_base_url = 'https://api-m.paypal.com';
        }
        
        // Log credentials for debugging
        VCS_Logger::log('Using PayPal Client ID: ' . $this->client_id);
    }
    
    /**
     * Process PayPal payment
     */
    public function process_payment($params) {
        try {
            // Create order
            $order_data = $this->create_paypal_order($params);
            
            // Create WooCommerce order
            $wc_order = $this->create_wc_order($params, 'paypal');
            
            // Store PayPal order ID
            $wc_order->update_meta_data('_paypal_order_id', $order_data['id']);
            $wc_order->save();
            
            return array(
                'success' => true,
                'order_id' => $wc_order->get_id(),
                'paypal_order_id' => $order_data['id'],
                'approval_url' => $this->get_approval_url($order_data['links']),
                'status' => 'pending',
                'message' => 'PayPal order created successfully'
            );
            
        } catch (Exception $e) {
            return array(
                'success' => false,
                'error' => $e->getMessage()
            );
        }
    }
    
    /**
     * Process PayPal credit card payment
     */
    public function process_credit_card_payment($params) {
        try {
            // Create order with credit card
            $order_data = $this->create_paypal_order_with_card($params);
            
            // Create WooCommerce order
            $wc_order = $this->create_wc_order($params, 'paypal_credit_card');
            
            // Store PayPal order ID
            $wc_order->update_meta_data('_paypal_order_id', $order_data['id']);
            $wc_order->save();
            
            return array(
                'success' => true,
                'order_id' => $wc_order->get_id(),
                'paypal_order_id' => $order_data['id'],
                'status' => $order_data['status'],
                'message' => 'Credit card payment processed successfully'
            );
            
        } catch (Exception $e) {
            return array(
                'success' => false,
                'error' => $e->getMessage()
            );
        }
    }
    
    /**
     * Create PayPal order
     */
    private function create_paypal_order($params) {
        $access_token = $this->get_access_token();
        
        $order_data = array(
            'intent' => 'CAPTURE',
            'purchase_units' => array(
                array(
                    'amount' => array(
                        'currency_code' => $params['currency'],
                        'value' => number_format($params['amount'], 2, '.', '')
                    ),
                    'description' => isset($params['description']) ? $params['description'] : 'Payment via VCS Payment API',
                    'custom_id' => isset($params['order_id']) ? $params['order_id'] : ''
                )
            ),
            'application_context' => array(
                'return_url' => $params['return_url'],
                'cancel_url' => $params['cancel_url'],
                'brand_name' => get_bloginfo('name'),
                'shipping_preference' => 'NO_SHIPPING'
            )
        );
        
        $response = wp_remote_post($this->api_base_url . '/v2/checkout/orders', array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $access_token,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ),
            'body' => json_encode($order_data),
            'timeout' => 30
        ));
        
        if (is_wp_error($response)) {
            throw new Exception('Failed to create PayPal order: ' . $response->get_error_message());
        }
        
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        if (wp_remote_retrieve_response_code($response) !== 201) {
            throw new Exception('PayPal API error: ' . (isset($data['error_description']) ? $data['error_description'] : 'Unknown error'));
        }
        
        return $data;
    }
    
    /**
     * Create PayPal order with credit card
     */
    private function create_paypal_order_with_card($params) {
        $access_token = $this->get_access_token();
        
        $order_data = array(
            'intent' => 'CAPTURE',
            'payment_source' => array(
                'card' => array(
                    'number' => $params['card_number'],
                    'expiry' => $params['expiry_year'] . '-' . str_pad($params['expiry_month'], 2, '0', STR_PAD_LEFT),
                    'security_code' => $params['cvv']
                )
            ),
            'purchase_units' => array(
                array(
                    'amount' => array(
                        'currency_code' => $params['currency'],
                        'value' => number_format($params['amount'], 2, '.', '')
                    ),
                    'description' => isset($params['description']) ? $params['description'] : 'Credit card payment via VCS Payment API',
                    'custom_id' => isset($params['order_id']) ? $params['order_id'] : ''
                )
            )
        );
        
        $response = wp_remote_post($this->api_base_url . '/v2/checkout/orders', array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $access_token,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ),
            'body' => json_encode($order_data),
            'timeout' => 30
        ));
        
        if (is_wp_error($response)) {
            throw new Exception('Failed to create PayPal order: ' . $response->get_error_message());
        }
        
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        if (wp_remote_retrieve_response_code($response) !== 201) {
            throw new Exception('PayPal API error: ' . (isset($data['error_description']) ? $data['error_description'] : 'Unknown error'));
        }
        
        // Capture the payment immediately
        return $this->capture_paypal_payment($data['id']);
    }
    
    /**
     * Capture PayPal payment
     */
    private function capture_paypal_payment($order_id) {
        $access_token = $this->get_access_token();
        
        $response = wp_remote_post($this->api_base_url . '/v2/checkout/orders/' . $order_id . '/capture', array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $access_token,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ),
            'timeout' => 30
        ));
        
        if (is_wp_error($response)) {
            throw new Exception('Failed to capture PayPal payment: ' . $response->get_error_message());
        }
        
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        if (wp_remote_retrieve_response_code($response) !== 201) {
            throw new Exception('PayPal capture error: ' . (isset($data['error_description']) ? $data['error_description'] : 'Unknown error'));
        }
        
        return $data;
    }
    
    /**
     * Get PayPal access token
     */
    private function get_access_token() {
        // Log the credentials being used
        VCS_Logger::log('Getting PayPal access token with Client ID: ' . $this->client_id);
        
        $response = wp_remote_post($this->api_base_url . '/v1/oauth2/token', array(
            'headers' => array(
                'Authorization' => 'Basic ' . base64_encode($this->client_id . ':' . $this->client_secret),
                'Content-Type' => 'application/x-www-form-urlencoded'
            ),
            'body' => 'grant_type=client_credentials',
            'timeout' => 30
        ));
        
        if (is_wp_error($response)) {
            VCS_Logger::log('WP Error when getting access token: ' . $response->get_error_message(), 'error');
            throw new Exception('Failed to get PayPal access token: ' . $response->get_error_message());
        }
        
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        VCS_Logger::log('PayPal get_access_token response: ' . print_r($data, true));
        
        if (wp_remote_retrieve_response_code($response) !== 200) {
            VCS_Logger::log('PayPal authentication failed: ' . (isset($data['error_description']) ? $data['error_description'] : 'Unknown error'), 'error');
            throw new Exception('PayPal authentication error: ' . (isset($data['error_description']) ? $data['error_description'] : 'Unknown error'));
        }
        
        return $data['access_token'];
    }
    
    /**
     * Get approval URL from PayPal order links
     */
    private function get_approval_url($links) {
        foreach ($links as $link) {
            if ($link['rel'] === 'approve') {
                return $link['href'];
            }
        }
        return '';
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
            'paypal' => 'PayPal',
            'paypal_credit_card' => 'PayPal Credit Card'
        );
        
        return isset($titles[$payment_method]) ? $titles[$payment_method] : $payment_method;
    }
    
    /**
     * Handle PayPal webhook
     */
    public function handle_webhook($request) {
        $body = $request->get_body();
        $data = json_decode($body, true);
        
        // Verify webhook signature (implement proper verification)
        if (!$this->verify_webhook_signature($request)) {
            return array('success' => false, 'error' => 'Invalid webhook signature');
        }
        
        $event_type = $data['event_type'] ?? '';
        
        switch ($event_type) {
            case 'PAYMENT.CAPTURE.COMPLETED':
                return $this->handle_payment_completed($data);
            case 'PAYMENT.CAPTURE.DENIED':
                return $this->handle_payment_denied($data);
            default:
                return array('success' => true, 'message' => 'Webhook received: ' . $event_type);
        }
    }
    
    /**
     * Verify webhook signature
     */
    private function verify_webhook_signature($request) {
        // Implement proper webhook signature verification
        // For now, return true (you should implement proper verification)
        return true;
    }
    
    /**
     * Handle payment completed webhook
     */
    private function handle_payment_completed($data) {
        $paypal_order_id = $data['resource']['supplementary_data']['related_ids']['order_id'] ?? '';
        
        if ($paypal_order_id) {
            $orders = wc_get_orders(array(
                'meta_key' => '_paypal_order_id',
                'meta_value' => $paypal_order_id,
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
     * Handle payment denied webhook
     */
    private function handle_payment_denied($data) {
        $paypal_order_id = $data['resource']['supplementary_data']['related_ids']['order_id'] ?? '';
        
        if ($paypal_order_id) {
            $orders = wc_get_orders(array(
                'meta_key' => '_paypal_order_id',
                'meta_value' => $paypal_order_id,
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
} 