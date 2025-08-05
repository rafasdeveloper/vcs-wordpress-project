<?php
/**
 * Plugin Name: VCS Payment API
 * Plugin URI: https://github.com/your-username/vcs-payment-api
 * Description: Exposes REST APIs for WooCommerce payment methods including PayPal, BTCPay, and WooPayments
 * Version: 1.0.0
 * Author: VCS Team
 * Author URI: https://your-website.com
 * Text Domain: vcs-payment-api
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.4
 * Requires PHP: 7.4
 * WC requires at least: 5.0
 * WC tested up to: 8.0
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('VCS_PAYMENT_API_VERSION', '1.0.0');
define('VCS_PAYMENT_API_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('VCS_PAYMENT_API_PLUGIN_URL', plugin_dir_url(__FILE__));
define('VCS_PAYMENT_API_PLUGIN_BASENAME', plugin_basename(__FILE__));

// Check if WooCommerce is active
function vcs_payment_api_check_woocommerce() {
    if (!class_exists('WooCommerce')) {
        add_action('admin_notices', function() {
            echo '<div class="notice notice-error"><p>' . 
                 __('VCS Payment API requires WooCommerce to be installed and activated.', 'vcs-payment-api') . 
                 '</p></div>';
        });
        return false;
    }
    return true;
}

// Main plugin class
class VCS_Payment_API {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        $this->init();
    }
    
    private function init() {
        // Check dependencies
        if (!vcs_payment_api_check_woocommerce()) {
            return;
        }
        
        // Load required files
        $this->load_dependencies();
        
        // Initialize hooks
        add_action('init', array($this, 'init_hooks'));
        
        // Register REST routes
        add_action('rest_api_init', array($this, 'register_rest_routes'));
        
        // Add admin menu
        add_action('admin_menu', array($this, 'add_admin_menu'));
        
        // Add settings
        add_action('admin_init', array($this, 'init_settings'));
    }
    
    private function load_dependencies() {
        require_once VCS_PAYMENT_API_PLUGIN_DIR . 'includes/class-vcs-logger.php';
        require_once VCS_PAYMENT_API_PLUGIN_DIR . 'includes/class-vcs-payment-api-controller.php';
        require_once VCS_PAYMENT_API_PLUGIN_DIR . 'includes/class-vcs-paypal-handler.php';
    }
    
    public function init_hooks() {
        // Add custom endpoints
        add_filter('woocommerce_rest_api_get_rest_namespaces', array($this, 'add_custom_endpoints'));
    }
    
    public function register_rest_routes() {
        $controller = new VCS_Payment_API_Controller();
        $controller->register_routes();
    }
    
    public function add_custom_endpoints($namespaces) {
        $namespaces['wc/v3']['vcs-payment-api'] = 'VCS_Payment_API_Controller';
        return $namespaces;
    }
    
    public function add_admin_menu() {
        add_submenu_page(
            'woocommerce',
            __('VCS Payment API', 'vcs-payment-api'),
            __('VCS Payment API', 'vcs-payment-api'),
            'manage_woocommerce',
            'vcs-payment-api',
            array($this, 'admin_page')
        );
    }
    
    public function admin_page() {
        include VCS_PAYMENT_API_PLUGIN_DIR . 'admin/admin-page.php';
    }
    
    public function init_settings() {
        register_setting('vcs_payment_api_options', 'vcs_payment_api_settings');
        
        add_settings_section(
            'vcs_payment_api_general',
            __('General Settings', 'vcs-payment-api'),
            array($this, 'settings_section_callback'),
            'vcs_payment_api_options'
        );
        
        add_settings_field(
            'vcs_payment_api_enabled',
            __('Enable API', 'vcs-payment-api'),
            array($this, 'checkbox_callback'),
            'vcs_payment_api_options',
            'vcs_payment_api_general',
            array('field' => 'enabled')
        );
        
        add_settings_field(
            'vcs_payment_api_debug',
            __('Debug Mode', 'vcs-payment-api'),
            array($this, 'checkbox_callback'),
            'vcs_payment_api_options',
            'vcs_payment_api_general',
            array('field' => 'debug')
        );
    }
    
    public function settings_section_callback() {
        echo '<p>' . __('Configure VCS Payment API settings.', 'vcs-payment-api') . '</p>';
    }
    
    public function checkbox_callback($args) {
        $options = get_option('vcs_payment_api_settings', array());
        $field = $args['field'];
        $value = isset($options[$field]) ? $options[$field] : false;
        
        echo '<input type="checkbox" id="vcs_payment_api_' . $field . '" name="vcs_payment_api_settings[' . $field . ']" value="1" ' . checked(1, $value, false) . '/>';
        echo '<label for="vcs_payment_api_' . $field . '">' . __('Enable', 'vcs-payment-api') . '</label>';
    }
}

// Initialize the plugin
function vcs_payment_api_init() {
    return VCS_Payment_API::get_instance();
}

// Start the plugin after WooCommerce is loaded
add_action('woocommerce_loaded', 'vcs_payment_api_init');

// Activation hook
register_activation_hook(__FILE__, 'vcs_payment_api_activate');
function vcs_payment_api_activate() {
    // Create necessary database tables or options
    add_option('vcs_payment_api_settings', array(
        'enabled' => true,
        'debug' => false
    ));
    
    // Flush rewrite rules
    flush_rewrite_rules();
}

// Deactivation hook
register_deactivation_hook(__FILE__, 'vcs_payment_api_deactivate');
function vcs_payment_api_deactivate() {
    // Clean up if necessary
    flush_rewrite_rules();
} 