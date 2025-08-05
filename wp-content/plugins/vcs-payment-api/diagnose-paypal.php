<?php
/**
 * PayPal Plugin Diagnostic Script
 * 
 * This script helps diagnose issues with PayPal plugin detection
 */

// Load WordPress
require_once('../../../wp-load.php');

// Check if user is admin
if (!current_user_can('manage_options')) {
    wp_die('Access denied');
}

echo '<h1>PayPal Plugin Diagnostic</h1>';

echo '<h2>1. WordPress Environment</h2>';
echo '<ul>';
echo '<li><strong>WordPress Version:</strong> ' . get_bloginfo('version') . '</li>';
echo '<li><strong>PHP Version:</strong> ' . phpversion() . '</li>';
echo '<li><strong>WP_PLUGIN_DIR:</strong> ' . WP_PLUGIN_DIR . '</li>';
echo '</ul>';

echo '<h2>2. WooCommerce Status</h2>';
if (class_exists('WooCommerce')) {
    echo '<p style="color: green;">✓ WooCommerce is active</p>';
    echo '<ul>';
    echo '<li><strong>WooCommerce Version:</strong> ' . WC()->version . '</li>';
    echo '</ul>';
} else {
    echo '<p style="color: red;">✗ WooCommerce is NOT active</p>';
}

echo '<h2>3. PayPal Plugin File Check</h2>';
$plugin_file = WP_PLUGIN_DIR . '/woocommerce-paypal-payments/woocommerce-paypal-payments.php';
if (file_exists($plugin_file)) {
    echo '<p style="color: green;">✓ PayPal plugin file exists at: ' . $plugin_file . '</p>';
    
    // Check file contents
    $file_content = file_get_contents($plugin_file);
    if (strpos($file_content, 'woocommerce_paypal_payments') !== false) {
        echo '<p style="color: green;">✓ Plugin file contains the expected function name</p>';
    } else {
        echo '<p style="color: red;">✗ Plugin file does not contain expected function name</p>';
    }
} else {
    echo '<p style="color: red;">✗ PayPal plugin file NOT found at: ' . $plugin_file . '</p>';
}

echo '<h2>4. Active Plugins</h2>';
$active_plugins = get_option('active_plugins', array());
echo '<p><strong>Total Active Plugins:</strong> ' . count($active_plugins) . '</p>';

$paypal_found = false;
foreach ($active_plugins as $plugin) {
    if (strpos($plugin, 'woocommerce-paypal-payments') !== false) {
        echo '<p style="color: green;">✓ Found PayPal plugin: ' . $plugin . '</p>';
        $paypal_found = true;
    }
}

if (!$paypal_found) {
    echo '<p style="color: red;">✗ PayPal plugin NOT found in active plugins list</p>';
}

echo '<h2>5. Function Availability</h2>';
if (function_exists('woocommerce_paypal_payments')) {
    echo '<p style="color: green;">✓ woocommerce_paypal_payments() function is available</p>';
    
    try {
        $container = woocommerce_paypal_payments();
        echo '<p style="color: green;">✓ PayPal container retrieved successfully</p>';
        
        // Try to get general settings
        if ($container->has('settings.data.general')) {
            echo '<p style="color: green;">✓ settings.data.general service is available</p>';
            
            $general_settings = $container->get('settings.data.general');
            if ($general_settings) {
                echo '<p style="color: green;">✓ General settings object retrieved successfully</p>';
                
                // Try reflection
                try {
                    $reflection = new ReflectionClass($general_settings);
                    $data_property = $reflection->getProperty('data');
                    $data_property->setAccessible(true);
                    $data = $data_property->getValue($general_settings);
                    
                    echo '<p style="color: green;">✓ Reflection access to data property successful</p>';
                    echo '<p><strong>Data keys available:</strong> ' . implode(', ', array_keys($data)) . '</p>';
                    
                } catch (Exception $e) {
                    echo '<p style="color: red;">✗ Reflection failed: ' . esc_html($e->getMessage()) . '</p>';
                }
            } else {
                echo '<p style="color: red;">✗ Could not retrieve general settings object</p>';
            }
        } else {
            echo '<p style="color: red;">✗ settings.data.general service is NOT available</p>';
            
            // List available services
            echo '<p><strong>Available services:</strong></p>';
            echo '<ul>';
            if ($container->has('wcgateway.settings')) {
                echo '<li>wcgateway.settings</li>';
            }
            if ($container->has('settings.data.general')) {
                echo '<li>settings.data.general</li>';
            }
            echo '</ul>';
        }
        
    } catch (Exception $e) {
        echo '<p style="color: red;">✗ Error accessing PayPal container: ' . esc_html($e->getMessage()) . '</p>';
    }
} else {
    echo '<p style="color: red;">✗ woocommerce_paypal_payments() function is NOT available</p>';
}

echo '<h2>6. Plugin Loading Order</h2>';
echo '<p>PayPal plugin might not be loaded when our plugin tries to access it. Try refreshing this page or check the plugin loading order.</p>';

echo '<h2>7. Recommendations</h2>';
echo '<ul>';
echo '<li>Make sure WooCommerce PayPal Payments plugin is installed and activated</li>';
echo '<li>Try deactivating and reactivating the PayPal plugin</li>';
echo '<li>Check if there are any PHP errors in the error log</li>';
echo '<li>Try accessing the PayPal settings in WooCommerce admin to ensure the plugin is working</li>';
echo '</ul>';

echo '<h2>8. Test VCS PayPal Handler</h2>';
try {
    $paypal_handler = new VCS_PayPal_Handler();
    echo '<p style="color: green;">✓ VCS PayPal Handler created successfully</p>';
    echo '<ul>';
    echo '<li><strong>Environment:</strong> ' . ($paypal_handler->is_sandbox_mode() ? 'Sandbox' : 'Production') . '</li>';
    echo '<li><strong>Client ID:</strong> ' . ($paypal_handler->get_client_id()) . '</li>';
    echo '<li><strong>Client Secret:</strong> ' . ($paypal_handler->get_client_secret()) . '</li>';
    echo '<li><strong>Merchant ID:</strong> ' . ($paypal_handler->get_merchant_id()) . '</li>';
    echo '<li><strong>Is Configured:</strong> ' . ($paypal_handler->is_configured() ? 'Yes' : 'No') . '</li>';
    echo '</ul>';
} catch (Exception $e) {
    echo '<p style="color: red;">✗ Error creating VCS PayPal Handler: ' . esc_html($e->getMessage()) . '</p>';
}
?> 