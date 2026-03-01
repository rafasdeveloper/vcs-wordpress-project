<?php
/**
 * VCS CORS Handler
 * 
 * Handles Cross-Origin Resource Sharing (CORS) for the VCS Payment API
 */

if (!defined('ABSPATH')) {
    exit;
}

class VCS_CORS_Handler {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('init', array($this, 'init_cors'));
    }
    
    /**
     * Initialize CORS handling
     */
    public function init_cors() {
        // Add CORS headers for REST API requests
        add_filter('rest_pre_serve_request', array($this, 'add_cors_headers'), 10, 4);
        
        // Handle preflight OPTIONS requests
        add_action('rest_api_init', array($this, 'handle_preflight'));
        
        // Add CORS headers for all requests to our endpoints
        add_action('wp_loaded', array($this, 'handle_cors_headers'));
    }
    
    /**
     * Add CORS headers to REST API responses
     */
    public function add_cors_headers($served, $result, $request, $server) {
        // Only add CORS headers for our API endpoints
        if (strpos($request->get_route(), '/vcs-payment-api/') !== false) {
            $this->set_cors_headers();
        }
        
        return $served;
    }
    
    /**
     * Handle preflight OPTIONS requests
     */
    public function handle_preflight() {
        // Check if this is a preflight request for our API
        if ($this->is_preflight_request()) {
            $this->set_cors_headers();
            header('HTTP/1.1 200 OK');
            exit();
        }
    }
    
    /**
     * Handle CORS headers for all requests
     */
    public function handle_cors_headers() {
        // Only add CORS headers for our API endpoints
        if ($this->is_vcs_api_request()) {
            $this->set_cors_headers();
        }
    }
    
    /**
     * Set CORS headers
     */
    private function set_cors_headers() {
        // Get allowed origins from settings or use wildcard
        $allowed_origins = $this->get_allowed_origins();
        $origin = $this->get_request_origin();
        
        // Check if origin is allowed
        if ($origin && in_array($origin, $allowed_origins)) {
            header('Access-Control-Allow-Origin: ' . $origin);
        } else {
            header('Access-Control-Allow-Origin: *');
        }
        
        // Allow specific HTTP methods
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        
        // Allow specific headers
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, X-WP-Nonce, Accept');
        
        // Allow credentials
        header('Access-Control-Allow-Credentials: true');
        
        // Set max age for preflight requests
        header('Access-Control-Max-Age: 86400');
    }
    
    /**
     * Check if this is a preflight request
     */
    private function is_preflight_request() {
        return isset($_SERVER['REQUEST_METHOD']) && 
               $_SERVER['REQUEST_METHOD'] === 'OPTIONS' && 
               $this->is_vcs_api_request();
    }
    
    /**
     * Check if this is a VCS API request
     */
    private function is_vcs_api_request() {
        return isset($_SERVER['REQUEST_URI']) && 
               strpos($_SERVER['REQUEST_URI'], '/wp-json/vcs-payment-api/') !== false;
    }
    
    /**
     * Get the request origin
     */
    private function get_request_origin() {
        return isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : null;
    }
    
    /**
     * Get allowed origins
     */
    private function get_allowed_origins() {
        // You can customize this to allow specific origins
        $default_origins = array(
            'http://localhost:8081',
            'http://localhost:3000',
            'http://localhost:3001',
            'https://yourdomain.com'
        );
        
        // Get from WordPress options if set
        $saved_origins = get_option('vcs_payment_api_cors_origins', array());
        
        return array_merge($default_origins, $saved_origins);
    }
} 