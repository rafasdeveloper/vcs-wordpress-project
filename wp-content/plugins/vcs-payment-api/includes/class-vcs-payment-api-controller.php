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
    }
    
    private function get_paypal_handler() {
        if (null === $this->paypal_handler) {
            $this->paypal_handler = new VCS_PayPal_Handler();
        }
        return $this->paypal_handler;
    }

    /**
     * Get available payment methods
     */
    public function get_payment_methods($request) {
        $methods = array(
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
        );
        
        return new WP_REST_Response($methods, 200);
    }
    
    /**
     * Create PayPal order
     */
    public function create_paypal_order($request) {
        try {
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
            $params = $request->get_params();
            
            // Capture order using PayPal SDK
            $result = $this->get_paypal_handler()->capture_order($params);
            
            return new WP_REST_Response($result, 200);
            
        } catch (Exception $e) {
            return new WP_Error('payment_error', $e->getMessage(), array('status' => 500));
        }
    }
    
    /**
     * Permission check for GET requests
     */
    public function get_permission_check($request) {
        return current_user_can('read');
    }
    
    /**
     * Permission check for POST requests
     */
    public function post_permission_check($request) {
        return current_user_can('edit_posts');
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