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

use PaypalServerSdkLib\PaypalServerSdkClientBuilder;
use PaypalServerSdkLib\ClientCredentialsAuthCredentialsBuilder;
use PaypalServerSdkLib\Environment;
use PaypalServerSdkLib\LoggingConfigurationBuilder;
use PaypalServerSdkLib\RequestLoggingConfigurationBuilder;
use PaypalServerSdkLib\ResponseLoggingConfigurationBuilder;
use PaypalServerSdkLib\LogLevel;

class VCS_PayPal_Handler {
    
    private $client_id;
    private $client_secret;
    private $merchant_id;
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
        VCS_Logger::log('Starting PayPal settings initialization...');
        
        // Check if WooCommerce is active
        if (!class_exists('WooCommerce')) {
            VCS_Logger::log('WooCommerce is not active.', 'error');
            return;
        }
        VCS_Logger::log('WooCommerce is active.');
        
        // Try multiple times to get the PayPal plugin (in case of loading order issues)
        $max_attempts = 3;
        $attempt = 0;
        
        while ($attempt < $max_attempts) {
            $attempt++;
            VCS_Logger::log("Attempt $attempt of $max_attempts to access PayPal plugin...");
            
            // Check if the PPCP class exists
            if (!class_exists('\WooCommerce\PayPalCommerce\PPCP')) {
                VCS_Logger::log("Attempt $attempt: PPCP class not found.", 'error');
                
                if ($attempt < $max_attempts) {
                    VCS_Logger::log("Waiting 1 second before retry...");
                    sleep(1);
                    continue;
                }
                
                // Check if the plugin file exists
                $plugin_file = WP_PLUGIN_DIR . '/woocommerce-paypal-payments/woocommerce-paypal-payments.php';
                if (file_exists($plugin_file)) {
                    VCS_Logger::log('PayPal plugin file exists but PPCP class not available. Plugin may not be properly loaded.');
                } else {
                    VCS_Logger::log('PayPal plugin file not found at: ' . $plugin_file);
                }
                
                // Check active plugins
                $active_plugins = get_option('active_plugins', array());
                VCS_Logger::log('Active plugins: ' . json_encode($active_plugins));
                
                return;
            }
            
            VCS_Logger::log("Attempt $attempt: PPCP class found.");

            // Check if the plugin is fully loaded
            if (!did_action('woocommerce_paypal_payments_init')) {
                VCS_Logger::log("Attempt $attempt: WooCommerce PayPal Payments plugin not fully initialized yet.", 'warning');
                // Try to initialize it
                do_action('woocommerce_paypal_payments_init');
                
                if ($attempt < $max_attempts) {
                    VCS_Logger::log("Waiting 1 second before retry...");
                    sleep(1);
                    continue;
                }
            }

            try {
                // Get the PayPal plugin's dependency injection container using PPCP class
                $paypal_container = \WooCommerce\PayPalCommerce\PPCP::container();
                VCS_Logger::log("Attempt $attempt: PayPal container retrieved successfully using PPCP::container().");
                
                // Get the general settings object from the container using the correct service name.
                $general_settings = $paypal_container->get('settings.data.general');

                if (!$general_settings) {
                    VCS_Logger::log("Attempt $attempt: Could not retrieve general settings from PayPal plugin container.", 'error');
                    
                    if ($attempt < $max_attempts) {
                        VCS_Logger::log("Waiting 1 second before retry...");
                        sleep(1);
                        continue;
                    }
                    
                    $this->init_settings_fallback();
                    return;
                }

                VCS_Logger::log("Attempt $attempt: Successfully loaded general settings from PayPal plugin container.");

                // Use reflection to access the protected data property
                $reflection = new ReflectionClass($general_settings);
                $data_property = $reflection->getProperty('data');
                $data_property->setAccessible(true);
                $data = $data_property->getValue($general_settings);

                // Extract settings from the data array
                $this->is_sandbox = isset($data['sandbox_merchant']) && $data['sandbox_merchant'];
                $this->client_id = isset($data['client_id']) ? $data['client_id'] : '';
                $this->client_secret = isset($data['client_secret']) ? $data['client_secret'] : '';
                $this->merchant_id = isset($data['merchant_id']) ? $data['merchant_id'] : '';

                // Log detailed debugging information
                VCS_Logger::log("Attempt $attempt: General settings debugging (using reflection):");
                VCS_Logger::log('- sandbox_merchant: ' . ($this->is_sandbox ? 'Yes' : 'No'));
                VCS_Logger::log('- client_id: ' . ($this->client_id ? 'Set' : 'Not set'));
                VCS_Logger::log('- client_secret: ' . ($this->client_secret ? 'Set' : 'Not set'));
                VCS_Logger::log('- merchant_id: ' . ($this->merchant_id ? 'Set' : 'Not set'));
                VCS_Logger::log('- merchant_connected: ' . ($general_settings->is_merchant_connected() ? 'Yes' : 'No'));
                
                // Success! Break out of the retry loop
                break;
                
            } catch (Exception $e) {
                VCS_Logger::log("Attempt $attempt: Error initializing PayPal settings: " . $e->getMessage(), 'error');
                
                if ($attempt < $max_attempts) {
                    VCS_Logger::log("Waiting 1 second before retry...");
                    sleep(1);
                    continue;
                }
                
                VCS_Logger::log('Exception trace: ' . $e->getTraceAsString(), 'error');
                $this->init_settings_fallback();
            }
        }
    }
    
    /**
     * Fallback method to get PayPal settings directly from WooCommerce options
     */
    private function init_settings_fallback() {
        VCS_Logger::log('Using fallback method to get PayPal settings from WooCommerce options.');
        
        // Get PayPal gateway settings from WooCommerce using the correct gateway ID
        $paypal_gateway_settings = get_option('woocommerce_ppcp-gateway_settings', array());
        
        if (empty($paypal_gateway_settings)) {
            VCS_Logger::log('No PayPal gateway settings found in WooCommerce options.', 'error');
            return;
        }
        
        VCS_Logger::log('PayPal gateway settings found: ' . json_encode(array_keys($paypal_gateway_settings)));
        
        // Extract settings
        $this->is_sandbox = isset($paypal_gateway_settings['sandbox_on']) && $paypal_gateway_settings['sandbox_on'];
        $this->client_id = isset($paypal_gateway_settings['client_id']) ? $paypal_gateway_settings['client_id'] : '';
        $this->client_secret = isset($paypal_gateway_settings['client_secret']) ? $paypal_gateway_settings['client_secret'] : '';
        $this->merchant_id = isset($paypal_gateway_settings['merchant_id']) ? $paypal_gateway_settings['merchant_id'] : '';
        
        VCS_Logger::log('Fallback settings loaded:');
        VCS_Logger::log('- Sandbox mode: ' . ($this->is_sandbox ? 'Yes' : 'No'));
        VCS_Logger::log('- Client ID: ' . ($this->client_id ? 'Set' : 'Not set'));
        VCS_Logger::log('- Client Secret: ' . ($this->client_secret ? 'Set' : 'Not set'));
        VCS_Logger::log('- Merchant ID: ' . ($this->merchant_id ? 'Set' : 'Not set'));
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
            // Check if the new PayPal SDK is available
            if (!class_exists('PaypalServerSdkClientBuilder')) {
                VCS_Logger::log('PayPal Server SDK not available. Please install the SDK using composer.', 'error');
                return;
            }

            VCS_Logger::log('Initializing PayPal client with Server SDK...');

            // Use the new PayPal Server SDK
            $client = PaypalServerSdkClientBuilder::init()
                ->clientCredentialsAuthCredentials(
                    ClientCredentialsAuthCredentialsBuilder::init(
                        $this->client_id,
                        $this->client_secret
                    )
                )
                ->environment($this->is_sandbox ? Environment::SANDBOX : Environment::PRODUCTION)
                ->loggingConfiguration(
                    LoggingConfigurationBuilder::init()
                        ->level(LogLevel::INFO)
                        ->requestConfiguration(RequestLoggingConfigurationBuilder::init()->body(true))
                        ->responseConfiguration(ResponseLoggingConfigurationBuilder::init()->headers(true))
                )
                ->build();

            $this->paypal_client = $client;
            VCS_Logger::log('PayPal client initialized successfully with Server SDK.');

        } catch (Exception $e) {
            VCS_Logger::log('Error initializing PayPal client: ' . $e->getMessage(), 'error');
            $this->paypal_client = null;
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
            // Build order data
            $order_data = $this->build_order_data($params);
            
            VCS_Logger::log('Creating PayPal order. Data: ' . json_encode($order_data));
            
            // Create order using PayPal Server SDK
            $request = new OrdersCreateRequest();
            $request->body = $order_data;
            
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
            VCS_Logger::log('Capturing PayPal order: ' . $params['order_id']);
            
            // Create capture request using PayPal Server SDK
            $request = new OrdersCaptureRequest($params['order_id']);
            
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
        return !empty($this->client_id) && !empty($this->client_secret) && !empty($this->merchant_id) && $this->paypal_client !== null;
    }
    
    /**
     * Get PayPal client ID
     */
    public function get_client_id() {
        return $this->client_id;
    }
    
    /**
     * Get PayPal client secret
     */
    public function get_client_secret() {
        return $this->client_secret;
    }
    
    /**
     * Get PayPal merchant ID
     */
    public function get_merchant_id() {
        return $this->merchant_id;
    }
    
    /**
     * Check if PayPal is in sandbox mode
     */
    public function is_sandbox_mode() {
        return $this->is_sandbox;
    }
}