<?php
/**
 * VCS Payment API Controller
 * 
 * Main REST API controller for handling payment requests
 */

if (!defined('ABSPATH')) {
    exit;
}

class VCS_Payment_API_Controller extends WP_REST_Controller {
    
    /**
     * Endpoint namespace
     */
    protected $namespace = 'vcs-payment-api/v1';
    
    /**
     * Route base
     */
    protected $rest_base = 'payments';

    private $paypal_handler;
    private $revolut_handler;
    
    /**
     * Constructor
     */
    public function __construct() {
        // PayPal handler is lazy-loaded on demand.
    }
    
    /**
     * Register the routes
     */
    public function register_routes() {
        // Get available payment methods
        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base . '/methods',
            array(
                array(
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => array($this, 'get_payment_methods'),
                    'permission_callback' => array($this, 'get_permission_check'),
                    'args'                => array(),
                ),
            )
        );
        
        // PayPal create order endpoint
        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base . '/paypal/order/create',
            array(
                array(
                    'methods'             => WP_REST_Server::CREATABLE,
                    'callback'            => array($this, 'create_paypal_order'),
                    'permission_callback' => array($this, 'post_permission_check'),
                    'args'                => $this->get_create_order_args(),
                ),
            )
        );
        
        // PayPal capture order endpoint
        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base . '/paypal/order/capture',
            array(
                array(
                    'methods'             => WP_REST_Server::CREATABLE,
                    'callback'            => array($this, 'capture_paypal_order'),
                    'permission_callback' => array($this, 'post_permission_check'),
                    'args'                => $this->get_capture_order_args(),
                ),
            )
        );
        
        // PayPal client ID endpoint
        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base . '/paypal/client-id',
            array(
                array(
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => array($this, 'get_paypal_client_id'),
                    'permission_callback' => array($this, 'get_permission_check'),
                    'args'                => array(),
                ),
            )
        );

        // Revolut create order endpoint
        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base . '/revolut/order/create',
            array(
                array(
                    'methods'             => WP_REST_Server::CREATABLE,
                    'callback'            => array($this, 'create_revolut_order'),
                    'permission_callback' => array($this, 'post_permission_check'),
                    'args'                => $this->get_create_order_args(),
                ),
            )
        );

        // Revolut config endpoint
        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base . '/revolut/config',
            array(
                array(
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => array($this, 'get_revolut_config'),
                    'permission_callback' => array($this, 'get_permission_check'),
                    'args'                => array(),
                ),
            )
        );

        // Revolut order status endpoint
        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base . '/revolut/order/status',
            array(
                array(
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => array($this, 'get_revolut_order_status'),
                    'permission_callback' => array($this, 'get_permission_check'),
                    'args'                => array(
                        'order_id' => array(
                            'required' => true,
                            'type'     => 'string',
                        ),
                    ),
                ),
            )
        );
    }
    
    private function get_paypal_handler() {
        if (null === $this->paypal_handler) {
            $this->paypal_handler = new VCS_PayPal_Handler();
        }
        return $this->paypal_handler;
    }

    private function get_revolut_handler() {
        if (null === $this->revolut_handler) {
            $this->revolut_handler = new VCS_Revolut_Handler();
        }
        return $this->revolut_handler;
    }

    /**
     * Get available payment methods
     */
    public function get_payment_methods($request) {
        $all_methods = array(
            'paypal' => array(
                'name' => 'PayPal',
                'description' => 'Pay with PayPal account',
                'endpoints' => array(
                    'create_order' => '/vcs-payment-api/v1/payments/paypal/order/create',
                    'capture_order' => '/vcs-payment-api/v1/payments/paypal/order/capture',
                ),
            ),
            'credit_card' => array(
                'name' => 'Credit Card',
                'description' => 'Pay with credit card powered by PayPal',
                'endpoints' => array(
                    'create_order' => '/vcs-payment-api/v1/payments/paypal/order/create',
                    'capture_order' => '/vcs-payment-api/v1/payments/paypal/order/capture',
                ),
            ),
            'revolut' => array(
                'name' => 'Revolut Pay',
                'description' => 'Pay with Revolut',
                'endpoints' => array(
                    'create_order' => '/vcs-payment-api/v1/payments/revolut/order/create',
                    'config' => '/vcs-payment-api/v1/payments/revolut/config',
                ),
            ),
            'revolut_card' => array(
                'name' => 'Credit Card', // Label it "Credit Card" for frontend consistency if desired, or "Credit Card (Revolut)"
                'description' => 'Pay with credit card powered by Revolut',
                'endpoints' => array(
                    'create_order' => '/vcs-payment-api/v1/payments/revolut/order/create',
                    'config' => '/vcs-payment-api/v1/payments/revolut/config',
                ),
            ),
        );
        
        // Filter methods based on admin settings
        $methods = array();
        foreach ($all_methods as $method_key => $method_data) {
            if (VCS_Payment_API::is_payment_method_enabled($method_key)) {
                $methods[$method_key] = $method_data;
            }
        }
        
        return new WP_REST_Response($methods, 200);
    }
    
    /**
     * Create PayPal order
     */
    public function create_paypal_order($request) {
        try {
            // Check if PayPal is enabled
            if (!VCS_Payment_API::is_payment_method_enabled('paypal')) {
                return new WP_Error('payment_method_disabled', 'PayPal payment method is disabled.', array('status' => 403));
            }
            
            $params = $request->get_params();
            
            // Create order using PayPal SDK
            $result = $this->get_paypal_handler()->create_order($params);
            
            return new WP_REST_Response($result, 200);
            
        } catch (Exception $e) {
            return new WP_Error('payment_error', $e->getMessage(), array('status' => 500));
        }
    }
    
    /**
     * Capture PayPal order
     */
    public function capture_paypal_order($request) {
        try {
            // Check if PayPal is enabled
            if (!VCS_Payment_API::is_payment_method_enabled('paypal')) {
                return new WP_Error('payment_method_disabled', 'PayPal payment method is disabled.', array('status' => 403));
            }
            
            $params = $request->get_params();
            
            // Capture order using PayPal SDK
            $result = $this->get_paypal_handler()->capture_order($params);
            
            return new WP_REST_Response($result, 200);
            
        } catch (Exception $e) {
            return new WP_Error('payment_error', $e->getMessage(), array('status' => 500));
        }
    }
    
    /**
     * Get PayPal client ID
     */
    public function get_paypal_client_id($request) {
        try {
            // Check if PayPal is enabled
            if (!VCS_Payment_API::is_payment_method_enabled('paypal')) {
                return new WP_Error('payment_method_disabled', 'PayPal payment method is disabled.', array('status' => 403));
            }
            
            $paypal_handler = $this->get_paypal_handler();
            
            if (!$paypal_handler->is_configured()) {
                return new WP_Error(
                    'paypal_not_configured',
                    'PayPal is not properly configured',
                    array('status' => 400)
                );
            }
            
            $response_data = array(
                'client_id' => $paypal_handler->get_client_id(),
                'environment' => $paypal_handler->is_sandbox_mode() ? 'sandbox' : 'production',
                'merchant_id' => $paypal_handler->get_merchant_id(),
                'configured' => true,
            );
            
            return new WP_REST_Response($response_data, 200);
            
        } catch (Exception $e) {
            return new WP_Error('paypal_error', $e->getMessage(), array('status' => 500));
        }
    }

    /**
     * Create Revolut order
     */
    public function create_revolut_order($request) {
        try {
            // Check if Revolut is enabled (either method)
            if (!VCS_Payment_API::is_payment_method_enabled('revolut') && !VCS_Payment_API::is_payment_method_enabled('revolut_card')) {
                return new WP_Error('payment_method_disabled', 'Revolut payment method is disabled.', array('status' => 403));
            }
            
            $params = $request->get_params();
            
            // Create order using Revolut Handler
            $result = $this->get_revolut_handler()->create_order($params);
            
            return new WP_REST_Response($result, 200);
            
        } catch (Exception $e) {
            return new WP_Error('payment_error', $e->getMessage(), array('status' => 500));
        }
    }

    /**
     * Get Revolut Config
     */
    public function get_revolut_config($request) {
        try {
            // Check if Revolut is enabled
            if (!VCS_Payment_API::is_payment_method_enabled('revolut') && !VCS_Payment_API::is_payment_method_enabled('revolut_card')) {
                return new WP_Error('payment_method_disabled', 'Revolut payment method is disabled.', array('status' => 403));
            }
            
            $revolut_handler = $this->get_revolut_handler();
            
            if (!$revolut_handler->is_configured()) {
                return new WP_Error(
                    'revolut_not_configured',
                    'Revolut is not properly configured',
                    array('status' => 400)
                );
            }
            
            $response_data = array(
                'public_key' => $revolut_handler->get_public_key(),
                'mode' => $revolut_handler->get_mode(),
                'configured' => true,
            );
            
            return new WP_REST_Response($response_data, 200);
            
        } catch (Exception $e) {
            return new WP_Error('revolut_error', $e->getMessage(), array('status' => 500));
        }
    }

    /**
     * Get Revolut Order Status
     */
    public function get_revolut_order_status($request) {
        try {
            if (!VCS_Payment_API::is_payment_method_enabled('revolut') && !VCS_Payment_API::is_payment_method_enabled('revolut_card')) {
                return new WP_Error('payment_method_disabled', 'Revolut payment method is disabled.', array('status' => 403));
            }
            
            $order_id = $request->get_param('order_id');
            
            if (empty($order_id)) {
                return new WP_Error('missing_order_id', 'Order ID is required.', array('status' => 400));
            }
            
            $result = $this->get_revolut_handler()->get_order_status($order_id);
            
            return new WP_REST_Response($result, 200);
            
        } catch (Exception $e) {
            return new WP_Error('payment_error', $e->getMessage(), array('status' => 500));
        }
    }
    
    /**
     * Permission check for GET requests
     */
    public function get_permission_check($request) {
        return true;
    }
    
    /**
     * Permission check for POST requests
     */
    public function post_permission_check($request) {
        return true;
    }
    
    /**
     * Get create order arguments
     */
    private function get_create_order_args() {
        return array(
            'intent' => array(
                'required' => false,
                'type' => 'string',
                'enum' => array('CAPTURE', 'AUTHORIZE'),
                'default' => 'CAPTURE',
            ),
            'amount' => array(
                'required' => true,
                'type' => 'number',
                'minimum' => 0.01,
            ),
            'currency' => array(
                'required' => true,
                'type' => 'string',
                'enum' => array('USD', 'EUR', 'GBP', 'CAD', 'AUD'),
            ),
            'description' => array(
                'required' => false,
                'type' => 'string',
            ),
            'return_url' => array(
                'required' => false,
                'type' => 'string',
                'format' => 'uri',
            ),
            'cancel_url' => array(
                'required' => false,
                'type' => 'string',
                'format' => 'uri',
            ),
            'addresses' => array(
                'required' => false,
                'type' => 'object',
            ),
        );
    }
    
    /**
     * Get capture order arguments
     */
    private function get_capture_order_args() {
        return array(
            'order_id' => array(
                'required' => true,
                'type' => 'string',
            ),
        );
    }
} 