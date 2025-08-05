<?php
/**
 * VCS BTCPay Handler
 * 
 * Handles BTCPay payment processing
 */

if (!defined('ABSPATH')) {
    exit;
}

class VCS_BTCPay_Handler {
    
    private $server_url;
    private $api_key;
    private $store_id;
    private $is_testnet;
    
    public function __construct() {
        $this->init_settings();
    }
    
    /**
     * Initialize BTCPay settings
     */
    private function init_settings() {
        // Get BTCPay settings from WooCommerce BTCPay plugin
        $btcpay_settings = get_option('woocommerce_btcpay_greenfield_settings', array());
        
        $this->server_url = isset($btcpay_settings['server_url']) ? rtrim($btcpay_settings['server_url'], '/') : '';
        $this->api_key = isset($btcpay_settings['api_key']) ? $btcpay_settings['api_key'] : '';
        $this->store_id = isset($btcpay_settings['store_id']) ? $btcpay_settings['store_id'] : '';
        $this->is_testnet = isset($btcpay_settings['testnet']) ? $btcpay_settings['testnet'] === 'yes' : false;
    }
    
    /**
     * Process BTCPay payment
     */
    public function process_payment($params) {
        try {
            // Create invoice
            $invoice_data = $this->create_btcpay_invoice($params);
            
            // Create WooCommerce order
            $wc_order = $this->create_wc_order($params, 'btcpay');
            
            // Store BTCPay invoice ID
            $wc_order->update_meta_data('_btcpay_invoice_id', $invoice_data['id']);
            $wc_order->update_meta_data('_btcpay_invoice_url', $invoice_data['checkoutLink']);
            $wc_order->save();
            
            return array(
                'success' => true,
                'order_id' => $wc_order->get_id(),
                'invoice_id' => $invoice_data['id'],
                'checkout_url' => $invoice_data['checkoutLink'],
                'amount' => $invoice_data['amount'],
                'currency' => $invoice_data['currency'],
                'status' => $invoice_data['status'],
                'message' => 'BTCPay invoice created successfully'
            );
            
        } catch (Exception $e) {
            return array(
                'success' => false,
                'error' => $e->getMessage()
            );
        }
    }
    
    /**
     * Create BTCPay invoice
     */
    private function create_btcpay_invoice($params) {
        $invoice_data = array(
            'amount' => $params['amount'],
            'currency' => $params['currency'],
            'metadata' => array(
                'orderId' => isset($params['order_id']) ? $params['order_id'] : '',
                'description' => isset($params['description']) ? $params['description'] : 'Payment via VCS Payment API'
            ),
            'checkout' => array(
                'redirectURL' => isset($params['notification_url']) ? $params['notification_url'] : home_url('/'),
                'defaultLanguage' => 'en'
            )
        );
        
        $response = wp_remote_post($this->server_url . '/api/v1/stores/' . $this->store_id . '/invoices', array(
            'headers' => array(
                'Authorization' => 'token ' . $this->api_key,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ),
            'body' => json_encode($invoice_data),
            'timeout' => 30
        ));
        
        if (is_wp_error($response)) {
            throw new Exception('Failed to create BTCPay invoice: ' . $response->get_error_message());
        }
        
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        if (wp_remote_retrieve_response_code($response) !== 200) {
            throw new Exception('BTCPay API error: ' . (isset($data['message']) ? $data['message'] : 'Unknown error'));
        }
        
        return $data;
    }
    
    /**
     * Get BTCPay invoice status
     */
    public function get_invoice_status($invoice_id) {
        $response = wp_remote_get($this->server_url . '/api/v1/stores/' . $this->store_id . '/invoices/' . $invoice_id, array(
            'headers' => array(
                'Authorization' => 'token ' . $this->api_key,
                'Accept' => 'application/json'
            ),
            'timeout' => 30
        ));
        
        if (is_wp_error($response)) {
            throw new Exception('Failed to get BTCPay invoice: ' . $response->get_error_message());
        }
        
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        if (wp_remote_retrieve_response_code($response) !== 200) {
            throw new Exception('BTCPay API error: ' . (isset($data['message']) ? $data['message'] : 'Unknown error'));
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
            'btcpay' => 'BTCPay (Cryptocurrency)'
        );
        
        return isset($titles[$payment_method]) ? $titles[$payment_method] : $payment_method;
    }
    
    /**
     * Handle BTCPay webhook
     */
    public function handle_webhook($request) {
        $body = $request->get_body();
        $data = json_decode($body, true);
        
        // Verify webhook signature (implement proper verification)
        if (!$this->verify_webhook_signature($request)) {
            return array('success' => false, 'error' => 'Invalid webhook signature');
        }
        
        $invoice_id = $data['invoiceId'] ?? '';
        $status = $data['status'] ?? '';
        
        if ($invoice_id) {
            $orders = wc_get_orders(array(
                'meta_key' => '_btcpay_invoice_id',
                'meta_value' => $invoice_id,
                'limit' => 1
            ));
            
            if (!empty($orders)) {
                $order = $orders[0];
                
                switch ($status) {
                    case 'Settled':
                        $order->payment_complete();
                        $order->set_status('processing');
                        $order->save();
                        
                        return array('success' => true, 'message' => 'Payment completed for order ' . $order->get_id());
                        
                    case 'Expired':
                    case 'Invalid':
                        $order->set_status('failed');
                        $order->save();
                        
                        return array('success' => true, 'message' => 'Payment failed for order ' . $order->get_id());
                        
                    default:
                        return array('success' => true, 'message' => 'Invoice status updated: ' . $status);
                }
            }
        }
        
        return array('success' => false, 'error' => 'Order not found');
    }
    
    /**
     * Verify webhook signature
     */
    private function verify_webhook_signature($request) {
        // Implement proper webhook signature verification for BTCPay
        // For now, return true (you should implement proper verification)
        return true;
    }
    
    /**
     * Get supported cryptocurrencies
     */
    public function get_supported_cryptocurrencies() {
        return array(
            'BTC' => 'Bitcoin',
            'LTC' => 'Litecoin',
            'ETH' => 'Ethereum',
            'BCH' => 'Bitcoin Cash',
            'XRP' => 'Ripple',
            'USDT' => 'Tether',
            'USDC' => 'USD Coin',
            'DAI' => 'Dai'
        );
    }
    
    /**
     * Get cryptocurrency exchange rates
     */
    public function get_exchange_rates($base_currency = 'USD') {
        try {
            $response = wp_remote_get($this->server_url . '/api/v1/stores/' . $this->store_id . '/rates', array(
                'headers' => array(
                    'Authorization' => 'token ' . $this->api_key,
                    'Accept' => 'application/json'
                ),
                'timeout' => 30
            ));
            
            if (is_wp_error($response)) {
                throw new Exception('Failed to get exchange rates: ' . $response->get_error_message());
            }
            
            $body = wp_remote_retrieve_body($response);
            $data = json_decode($body, true);
            
            if (wp_remote_retrieve_response_code($response) !== 200) {
                throw new Exception('BTCPay API error: ' . (isset($data['message']) ? $data['message'] : 'Unknown error'));
            }
            
            return $data;
            
        } catch (Exception $e) {
            return array('error' => $e->getMessage());
        }
    }
    
    /**
     * Create payment request with specific cryptocurrency
     */
    public function create_crypto_payment($params, $crypto_currency) {
        try {
            // Get exchange rate
            $rates = $this->get_exchange_rates($params['currency']);
            
            if (isset($rates['error'])) {
                throw new Exception($rates['error']);
            }
            
            // Calculate crypto amount
            $fiat_amount = $params['amount'];
            $crypto_rate = $rates[$crypto_currency] ?? null;
            
            if (!$crypto_rate) {
                throw new Exception('Exchange rate not available for ' . $crypto_currency);
            }
            
            $crypto_amount = $fiat_amount / $crypto_rate;
            
            // Create invoice with crypto amount
            $invoice_params = $params;
            $invoice_params['amount'] = $crypto_amount;
            $invoice_params['currency'] = $crypto_currency;
            
            return $this->process_payment($invoice_params);
            
        } catch (Exception $e) {
            return array(
                'success' => false,
                'error' => $e->getMessage()
            );
        }
    }
} 