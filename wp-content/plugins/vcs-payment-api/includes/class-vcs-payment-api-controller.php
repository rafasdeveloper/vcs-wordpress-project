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
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->init_handlers();
    }
    
    /**
     * Initialize payment handlers
     */
    private function init_handlers() {
        $this->paypal_handler = new VCS_PayPal_Handler();
        $this->btcpay_handler = new VCS_BTCPay_Handler();
        $this->woopayments_handler = new VCS_WooPayments_Handler();
        $this->validator = new VCS_Payment_Validator();
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
        
        // PayPal payment endpoint
        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base . '/paypal',
            array(
                array(
                    'methods'             => WP_REST_Server::CREATABLE,
                    'callback'            => array($this, 'process_paypal_payment'),
                    'permission_callback' => array($this, 'post_permission_check'),
                    'args'                => $this->get_paypal_args(),
                ),
            )
        );
        
        // PayPal credit card payment endpoint
        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base . '/paypal/credit-card',
            array(
                array(
                    'methods'             => WP_REST_Server::CREATABLE,
                    'callback'            => array($this, 'process_paypal_credit_card'),
                    'permission_callback' => array($this, 'post_permission_check'),
                    'args'                => $this->get_paypal_credit_card_args(),
                ),
            )
        );
        
        // BTCPay payment endpoint
        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base . '/btcpay',
            array(
                array(
                    'methods'             => WP_REST_Server::CREATABLE,
                    'callback'            => array($this, 'process_btcpay_payment'),
                    'permission_callback' => array($this, 'post_permission_check'),
                    'args'                => $this->get_btcpay_args(),
                ),
            )
        );
        
        // WooPayments endpoint
        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base . '/woopayments',
            array(
                array(
                    'methods'             => WP_REST_Server::CREATABLE,
                    'callback'            => array($this, 'process_woopayments'),
                    'permission_callback' => array($this, 'post_permission_check'),
                    'args'                => $this->get_woopayments_args(),
                ),
            )
        );
        
        // Payment status endpoint
        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base . '/status/(?P<order_id>[a-zA-Z0-9-]+)',
            array(
                array(
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => array($this, 'get_payment_status'),
                    'permission_callback' => array($this, 'get_permission_check'),
                    'args'                => array(
                        'order_id' => array(
                            'required' => true,
                            'type'     => 'string',
                            'sanitize_callback' => 'sanitize_text_field',
                        ),
                    ),
                ),
            )
        );
        
        // Webhook endpoints
        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base . '/webhook/paypal',
            array(
                array(
                    'methods'             => WP_REST_Server::CREATABLE,
                    'callback'            => array($this, 'handle_paypal_webhook'),
                    'permission_callback' => '__return_true',
                    'args'                => array(),
                ),
            )
        );
        
        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base . '/webhook/btcpay',
            array(
                array(
                    'methods'             => WP_REST_Server::CREATABLE,
                    'callback'            => array($this, 'handle_btcpay_webhook'),
                    'permission_callback' => '__return_true',
                    'args'                => array(),
                ),
            )
        );
    }
    
    /**
     * Get available payment methods
     */
    public function get_payment_methods($request) {
        $methods = array(
            'paypal' => array(
                'name' => 'PayPal',
                'description' => 'Pay with PayPal account or credit card',
                'endpoints' => array(
                    'payment' => '/vcs-payment-api/v1/payments/paypal',
                    'credit_card' => '/vcs-payment-api/v1/payments/paypal/credit-card',
                ),
            ),
            'btcpay' => array(
                'name' => 'BTCPay',
                'description' => 'Pay with Bitcoin and other cryptocurrencies',
                'endpoints' => array(
                    'payment' => '/vcs-payment-api/v1/payments/btcpay',
                ),
            ),
            'woopayments' => array(
                'name' => 'WooPayments',
                'description' => 'Pay with WooPayments (Stripe)',
                'endpoints' => array(
                    'payment' => '/vcs-payment-api/v1/payments/woopayments',
                ),
            ),
        );
        
        return new WP_REST_Response($methods, 200);
    }
    
    /**
     * Process PayPal payment
     */
    public function process_paypal_payment($request) {
        try {
            $params = $request->get_params();
            
            // Validate request
            $validation = $this->validator->validate_paypal_payment($params);
            if (!$validation['valid']) {
                return new WP_Error('validation_error', $validation['message'], array('status' => 400));
            }
            
            // Process payment
            $result = $this->paypal_handler->process_payment($params);
            
            return new WP_REST_Response($result, 200);
            
        } catch (Exception $e) {
            return new WP_Error('payment_error', $e->getMessage(), array('status' => 500));
        }
    }
    
    /**
     * Process PayPal credit card payment
     */
    public function process_paypal_credit_card($request) {
        try {
            $params = $request->get_params();
            
            // Validate request
            $validation = $this->validator->validate_paypal_credit_card($params);
            if (!$validation['valid']) {
                return new WP_Error('validation_error', $validation['message'], array('status' => 400));
            }
            
            // Process payment
            $result = $this->paypal_handler->process_credit_card_payment($params);
            
            return new WP_REST_Response($result, 200);
            
        } catch (Exception $e) {
            return new WP_Error('payment_error', $e->getMessage(), array('status' => 500));
        }
    }
    
    /**
     * Process BTCPay payment
     */
    public function process_btcpay_payment($request) {
        try {
            $params = $request->get_params();
            
            // Validate request
            $validation = $this->validator->validate_btcpay_payment($params);
            if (!$validation['valid']) {
                return new WP_Error('validation_error', $validation['message'], array('status' => 400));
            }
            
            // Process payment
            $result = $this->btcpay_handler->process_payment($params);
            
            return new WP_REST_Response($result, 200);
            
        } catch (Exception $e) {
            return new WP_Error('payment_error', $e->getMessage(), array('status' => 500));
        }
    }
    
    /**
     * Process WooPayments
     */
    public function process_woopayments($request) {
        try {
            $params = $request->get_params();
            
            // Validate request
            $validation = $this->validator->validate_woopayments($params);
            if (!$validation['valid']) {
                return new WP_Error('validation_error', $validation['message'], array('status' => 400));
            }
            
            // Process payment
            $result = $this->woopayments_handler->process_payment($params);
            
            return new WP_REST_Response($result, 200);
            
        } catch (Exception $e) {
            return new WP_Error('payment_error', $e->getMessage(), array('status' => 500));
        }
    }
    
    /**
     * Get payment status
     */
    public function get_payment_status($request) {
        $order_id = $request->get_param('order_id');
        
        try {
            $order = wc_get_order($order_id);
            
            if (!$order) {
                return new WP_Error('order_not_found', 'Order not found', array('status' => 404));
            }
            
            $status = array(
                'order_id' => $order_id,
                'status' => $order->get_status(),
                'payment_method' => $order->get_payment_method(),
                'total' => $order->get_total(),
                'currency' => $order->get_currency(),
                'created_at' => $order->get_date_created()->format('c'),
                'updated_at' => $order->get_date_modified()->format('c'),
            );
            
            return new WP_REST_Response($status, 200);
            
        } catch (Exception $e) {
            return new WP_Error('status_error', $e->getMessage(), array('status' => 500));
        }
    }
    
    /**
     * Handle PayPal webhook
     */
    public function handle_paypal_webhook($request) {
        try {
            $result = $this->paypal_handler->handle_webhook($request);
            return new WP_REST_Response($result, 200);
        } catch (Exception $e) {
            return new WP_Error('webhook_error', $e->getMessage(), array('status' => 500));
        }
    }
    
    /**
     * Handle BTCPay webhook
     */
    public function handle_btcpay_webhook($request) {
        try {
            $result = $this->btcpay_handler->handle_webhook($request);
            return new WP_REST_Response($result, 200);
        } catch (Exception $e) {
            return new WP_Error('webhook_error', $e->getMessage(), array('status' => 500));
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
     * Get PayPal payment arguments
     */
    private function get_paypal_args() {
        return array(
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
            'order_id' => array(
                'required' => false,
                'type' => 'string',
            ),
            'description' => array(
                'required' => false,
                'type' => 'string',
            ),
            'return_url' => array(
                'required' => true,
                'type' => 'string',
                'format' => 'uri',
            ),
            'cancel_url' => array(
                'required' => true,
                'type' => 'string',
                'format' => 'uri',
            ),
        );
    }
    
    /**
     * Get PayPal credit card arguments
     */
    private function get_paypal_credit_card_args() {
        return array(
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
            'card_number' => array(
                'required' => true,
                'type' => 'string',
                'pattern' => '^[0-9]{13,19}$',
            ),
            'expiry_month' => array(
                'required' => true,
                'type' => 'integer',
                'minimum' => 1,
                'maximum' => 12,
            ),
            'expiry_year' => array(
                'required' => true,
                'type' => 'integer',
                'minimum' => date('Y'),
            ),
            'cvv' => array(
                'required' => true,
                'type' => 'string',
                'pattern' => '^[0-9]{3,4}$',
            ),
            'order_id' => array(
                'required' => false,
                'type' => 'string',
            ),
            'description' => array(
                'required' => false,
                'type' => 'string',
            ),
        );
    }
    
    /**
     * Get BTCPay arguments
     */
    private function get_btcpay_args() {
        return array(
            'amount' => array(
                'required' => true,
                'type' => 'number',
                'minimum' => 0.000001,
            ),
            'currency' => array(
                'required' => true,
                'type' => 'string',
                'enum' => array('BTC', 'LTC', 'ETH', 'BCH'),
            ),
            'order_id' => array(
                'required' => false,
                'type' => 'string',
            ),
            'description' => array(
                'required' => false,
                'type' => 'string',
            ),
            'notification_url' => array(
                'required' => false,
                'type' => 'string',
                'format' => 'uri',
            ),
        );
    }
    
    /**
     * Get WooPayments arguments
     */
    private function get_woopayments_args() {
        return array(
            'amount' => array(
                'required' => true,
                'type' => 'number',
                'minimum' => 0.01,
            ),
            'currency' => array(
                'required' => true,
                'type' => 'string',
                'enum' => array('usd', 'eur', 'gbp', 'cad', 'aud'),
            ),
            'payment_method_id' => array(
                'required' => true,
                'type' => 'string',
            ),
            'order_id' => array(
                'required' => false,
                'type' => 'string',
            ),
            'description' => array(
                'required' => false,
                'type' => 'string',
            ),
            'customer_id' => array(
                'required' => false,
                'type' => 'string',
            ),
        );
    }
} 