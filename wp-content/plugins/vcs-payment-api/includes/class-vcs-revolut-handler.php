<?php
/**
 * VCS Revolut Handler
 * 
 * Handles Revolut payment processing
 */

if (!defined('ABSPATH')) {
    exit;
}

class VCS_Revolut_Handler {
    
    private $api_key;
    private $public_key;
    private $mode;
    private $api_url;
    
    public function __construct() {
        $this->init_settings();
    }
    
    /**
     * Initialize Revolut settings
     */
    private function init_settings() {
        $options = get_option('vcs_payment_api_settings', array());
        
        $this->api_key = isset($options['revolut_api_key']) ? $options['revolut_api_key'] : '';
        $this->public_key = isset($options['revolut_public_key']) ? $options['revolut_public_key'] : '';
        $this->mode = isset($options['revolut_mode']) ? $options['revolut_mode'] : 'sandbox';
        
        $this->api_url = ($this->mode === 'production') 
            ? 'https://merchant.revolut.com/api/1.0' 
            : 'https://sandbox-merchant.revolut.com/api/1.0';
    }
    
    /**
     * Check if Revolut is properly configured
     */
    public function is_configured() {
        return !empty($this->api_key) && !empty($this->public_key);
    }
    
    /**
     * Get Public Key
     */
    public function get_public_key() {
        return $this->public_key;
    }
    
    /**
     * Get Mode
     */
    public function get_mode() {
        return $this->mode;
    }
    
    /**
     * Create Revolut Order
     */
    public function create_order($params) {
        if (!$this->is_configured()) {
            throw new Exception('Revolut is not properly configured.');
        }
        
        $amount = isset($params['amount']) ? floatval($params['amount']) : 0;
        $currency = isset($params['currency']) ? $params['currency'] : 'GBP';
        
        // Amount needs to be in minor currency units (e.g. cents/pence) for Revolut API
        // BUT the create order endpoint Documentation says "amount" is number
        // Let's check Revolut API docs: https://developer.revolut.com/docs/merchant/create-order
        // It says "amount": 1000 for 10.00 GBP if currency is GBP? NO.
        // Revolut API says "amount" is integer representing the amount in the smallest currency unit.
        // So for GBP, 10.00 GBP = 1000.
        
        $amount_in_cents = round($amount * 100);
        
        $order_data = array(
            'amount' => $amount_in_cents,
            'currency' => $currency,
            'description' => isset($params['description']) ? $params['description'] : 'Order from VCS Website',
            'capture_mode' => 'AUTOMATIC', // or MANUAL if we want to capture later
            'customer' => array(
                'email' => isset($params['email']) ? $params['email'] : '',
                'name' => isset($params['name']) ? $params['name'] : '',
                'phone' => isset($params['phone']) ? $params['phone'] : '',
            ),
             'merchant_order_ext_ref' => isset($params['merchant_order_ext_ref']) ? $params['merchant_order_ext_ref'] : uniqid(),
        );

        if (isset($params['return_url'])) {
            $order_data['return_url'] = $params['return_url'];
        }

        if (isset($params['cancel_url'])) {
            $order_data['cancel_url'] = $params['cancel_url'];
        }

        // Add metadata if needed
        if (isset($params['metadata'])) {
             $order_data['metadata'] = $params['metadata'];
        }
        
        $response = $this->make_request('POST', '/orders', $order_data);
        
        if (is_wp_error($response)) {
            throw new Exception($response->get_error_message());
        }
        
        return array(
            'success' => true,
            'public_id' => $response['public_id'], // Token for frontend
            'id' => $response['id'], // Internal ID
            'order_data' => $response
        );
    }
    
    /**
     * Get Revolut Order Status
     */
    public function get_order_status($order_id) {
        if (!$this->is_configured()) {
            throw new Exception('Revolut is not properly configured.');
        }
        
        $response = $this->make_request('GET', '/orders/' . $order_id);
        
        if (is_wp_error($response)) {
            throw new Exception($response->get_error_message());
        }
        
        return array(
            'success' => true,
            'id' => $response['id'],
            'state' => $response['state'], // PENDING, PROCESSING, AUTHORISED, COMPLETED, CANCELLED, FAILED
            'order_data' => $response
        );
    }
    
    /**
     * Make request to Revolut API
     */
    private function make_request($method, $endpoint, $data = null) {
        $url = $this->api_url . $endpoint;
        
        $args = array(
            'method' => $method,
            'headers' => array(
                'Authorization' => 'Bearer ' . $this->api_key,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ),
            'timeout' => 45
        );
        
        if ($data && ($method === 'POST' || $method === 'PUT')) {
            $args['body'] = json_encode($data);
        }
        
        VCS_Logger::log("Revolut Request to $url: " . ($data ? json_encode($data) : ''));
        
        $response = wp_remote_request($url, $args);
        
        if (is_wp_error($response)) {
            VCS_Logger::log("Revolut Request Failed: " . $response->get_error_message(), 'error');
            return $response;
        }
        
        $code = wp_remote_retrieve_response_code($response);
        $body = wp_remote_retrieve_body($response);
        
        VCS_Logger::log("Revolut Response ($code): " . $body);
        
        $parsed_body = json_decode($body, true);
        
        if ($code >= 400) {
            $message = isset($parsed_body['message']) ? $parsed_body['message'] : 'Unknown error from Revolut API';
            return new WP_Error('revolut_api_error', $message, array('status' => $code));
        }
        
        return $parsed_body;
    }
}
