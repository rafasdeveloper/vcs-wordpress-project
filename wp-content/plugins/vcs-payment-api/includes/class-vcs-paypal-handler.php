<?php
/**
 * VCS PayPal Handler
 * 
 * Handles PayPal payment processing using PayPal Server SDK
 */

if (!defined('ABSPATH')) {
    exit;
}

// Load PayPal SDK if composer autoloader exists
if (file_exists(VCS_PAYMENT_API_PLUGIN_DIR . 'vendor/autoload.php')) {
    require_once VCS_PAYMENT_API_PLUGIN_DIR . 'vendor/autoload.php';
}

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;

class VCS_PayPal_Handler {
    
    private $client_id;
    private $client_secret;
    private $is_sandbox;
    private $paypal_client;
    
    public function __construct() {
        $this->init_settings();
        $this->init_paypal_client();
    }
    
    /**
     * Initialize PayPal settings
     */
    private function init_settings() {
        if (!function_exists('woocommerce_paypal_payments')) {
            VCS_Logger::log('WooCommerce PayPal Payments plugin not active.', 'error');
            return;
        }

        try {
            // Get the PayPal plugin's dependency injection container.
            $paypal_container = woocommerce_paypal_payments();
            
            // Get the settings object from the container.
            $settings = $paypal_container->get('settings');

            if (!$settings) {
                VCS_Logger::log('Could not retrieve settings from PayPal plugin container.', 'error');
                return;
            }

            VCS_Logger::log('Successfully loaded settings from PayPal plugin container.');

            $this->is_sandbox = $settings->get('sandbox') === 'yes';
            $this->client_id = $settings->get('client_id');
            $this->client_secret = $settings->get('client_secret');

            // Log credentials for debugging
            VCS_Logger::log('Sandbox mode: ' . ($this->is_sandbox ? 'Yes' : 'No'));
            VCS_Logger::log('Using PayPal Client ID: ' . $this->client_id);
            
        } catch (Exception $e) {
            VCS_Logger::log('Error initializing PayPal settings: ' . $e->getMessage(), 'error');
        }
    }
    
    /**
     * Initialize PayPal client
     */
    private function init_paypal_client() {
        if (empty($this->client_id) || empty($this->client_secret)) {
            VCS_Logger::log('PayPal credentials not available. Cannot initialize PayPal client.', 'error');
            return;
        }

        try {
            // Check if PayPal SDK classes are available
            if (!class_exists('PayPalCheckoutSdk\Core\PayPalHttpClient')) {
                VCS_Logger::log('PayPal SDK not found. Please run "composer install" in the plugin directory.', 'error');
                return;
            }

            // Create environment
            $environment = $this->is_sandbox 
                ? new SandboxEnvironment($this->client_id, $this->client_secret)
                : new ProductionEnvironment($this->client_id, $this->client_secret);

            // Create client
            $this->paypal_client = new PayPalHttpClient($environment);
            
            VCS_Logger::log('PayPal client initialized successfully.');
            
        } catch (Exception $e) {
            VCS_Logger::log('Error initializing PayPal client: ' . $e->getMessage(), 'error');
        }
    }
    
    /**
     * Create PayPal order
     */
    public function create_order($params) {
        if (!$this->paypal_client) {
            throw new Exception('PayPal client not initialized. Please check your PayPal configuration.');
        }

        try {
            $request = new OrdersCreateRequest();
            $request->prefer('return=representation');
            
            // Build order data
            $order_data = $this->build_order_data($params);
            $request->body = $order_data;
            
            VCS_Logger::log('Creating PayPal order with data: ' . json_encode($order_data));
            
            // Execute request
            $response = $this->paypal_client->execute($request);
            
            VCS_Logger::log('PayPal order created successfully. Order ID: ' . $response->result->id);
            
            return array(
                'success' => true,
                'order_id' => $response->result->id,
                'status' => $response->result->status,
                'links' => $response->result->links,
                'order_data' => $response->result
            );
            
        } catch (Exception $e) {
            VCS_Logger::log('Error creating PayPal order: ' . $e->getMessage(), 'error');
            throw new Exception('Failed to create PayPal order: ' . $e->getMessage());
        }
    }
    
    /**
     * Capture PayPal order
     */
    public function capture_order($params) {
        if (!$this->paypal_client) {
            throw new Exception('PayPal client not initialized. Please check your PayPal configuration.');
        }

        if (empty($params['order_id'])) {
            throw new Exception('Order ID is required for capturing payment.');
        }

        try {
            $request = new OrdersCaptureRequest($params['order_id']);
            $request->prefer('return=representation');
            
            VCS_Logger::log('Capturing PayPal order: ' . $params['order_id']);
            
            // Execute request
            $response = $this->paypal_client->execute($request);
            
            VCS_Logger::log('PayPal order captured successfully. Order ID: ' . $response->result->id);
            
            return array(
                'success' => true,
                'order_id' => $response->result->id,
                'status' => $response->result->status,
                'capture_id' => $response->result->purchase_units[0]->payments->captures[0]->id ?? '',
                'order_data' => $response->result
            );
            
        } catch (Exception $e) {
            VCS_Logger::log('Error capturing PayPal order: ' . $e->getMessage(), 'error');
            throw new Exception('Failed to capture PayPal order: ' . $e->getMessage());
        }
    }
    
    /**
     * Build order data for PayPal API
     */
    private function build_order_data($params) {
        $intent = isset($params['intent']) ? $params['intent'] : 'CAPTURE';
        $amount = number_format($params['amount'], 2, '.', '');
        $currency = strtoupper($params['currency']);
        
        $order_data = array(
            'intent' => $intent,
            'purchase_units' => array(
                array(
                    'amount' => array(
                        'currency_code' => $currency,
                        'value' => $amount
                    )
                )
            )
        );
        
        // Add description if provided
        if (!empty($params['description'])) {
            $order_data['purchase_units'][0]['description'] = $params['description'];
        }
        
        // Add application context if URLs are provided
        if (!empty($params['return_url']) || !empty($params['cancel_url'])) {
            $order_data['application_context'] = array();
            
            if (!empty($params['return_url'])) {
                $order_data['application_context']['return_url'] = $params['return_url'];
            }
            
            if (!empty($params['cancel_url'])) {
                $order_data['application_context']['cancel_url'] = $params['cancel_url'];
            }
            
            $order_data['application_context']['brand_name'] = get_bloginfo('name');
            $order_data['application_context']['shipping_preference'] = 'NO_SHIPPING';
        }
        
        return $order_data;
    }
    
    /**
     * Get PayPal client for external use if needed
     */
    public function get_paypal_client() {
        return $this->paypal_client;
    }
    
    /**
     * Check if PayPal is properly configured
     */
    public function is_configured() {
        return !empty($this->client_id) && !empty($this->client_secret) && $this->paypal_client !== null;
    }
    
    /**
     * Get client ID for admin display
     */
    public function get_client_id() {
        return $this->client_id ?: __('Not configured', 'vcs-payment-api');
    }
    
    /**
     * Get client secret for admin display
     */
    public function get_client_secret() {
        return $this->client_secret ?: __('Not configured', 'vcs-payment-api');
    }
    
    /**
     * Check if sandbox mode is enabled
     */
    public function is_sandbox_mode() {
        return $this->is_sandbox;
    }
}