<?php
/**
 * Test script to verify PayPal settings retrieval
 * 
 * This file can be accessed directly to test the PayPal settings retrieval
 * without going through the admin interface.
 */

// Load WordPress
require_once('../../../wp-load.php');

// Check if user is admin
if (!current_user_can('manage_options')) {
    wp_die('Access denied');
}

echo '<h1>PayPal Settings Test</h1>';

// Test the PayPal handler
try {
    $paypal_handler = new VCS_PayPal_Handler();
    
    echo '<h2>PayPal Handler Test Results:</h2>';
    echo '<ul>';
    echo '<li><strong>Environment:</strong> ' . ($paypal_handler->is_sandbox_mode() ? 'Sandbox' : 'Production') . '</li>';
    echo '<li><strong>Client ID:</strong> ' . ($paypal_handler->get_client_id()) . '</li>';
    echo '<li><strong>Client Secret:</strong> ' . ($paypal_handler->get_client_secret()) . '</li>';
    echo '<li><strong>Merchant ID:</strong> ' . ($paypal_handler->get_merchant_id()) . '</li>';
    echo '<li><strong>Is Configured:</strong> ' . ($paypal_handler->is_configured() ? 'Yes' : 'No') . '</li>';
    echo '</ul>';
    
} catch (Exception $e) {
    echo '<p><strong>Error:</strong> ' . esc_html($e->getMessage()) . '</p>';
}

// Test direct WooCommerce options
echo '<h2>Direct WooCommerce Options Test:</h2>';
$paypal_settings = get_option('woocommerce_ppcp-gateway_settings', array());
echo '<p><strong>PayPal Gateway Settings:</strong></p>';
echo '<pre>' . esc_html(print_r($paypal_settings, true)) . '</pre>';

// Test if WooCommerce PayPal Payments plugin is active
echo '<h2>Plugin Status:</h2>';
echo '<ul>';
echo '<li><strong>WooCommerce PayPal Payments Active:</strong> ' . (function_exists('woocommerce_paypal_payments') ? 'Yes' : 'No') . '</li>';
echo '<li><strong>WooCommerce Active:</strong> ' . (class_exists('WooCommerce') ? 'Yes' : 'No') . '</li>';
echo '</ul>';

// Show recent logs
echo '<h2>Recent Logs:</h2>';
$logs = VCS_Logger::get_logs();
if (!empty($logs)) {
    echo '<table border="1" style="border-collapse: collapse; width: 100%;">';
    echo '<tr><th>Date</th><th>Level</th><th>Message</th></tr>';
    foreach (array_reverse(array_slice($logs, -10)) as $log) {
        echo '<tr>';
        echo '<td>' . esc_html(date('Y-m-d H:i:s', $log['timestamp'])) . '</td>';
        echo '<td>' . esc_html($log['level']) . '</td>';
        echo '<td><pre>' . esc_html($log['message']) . '</pre></td>';
        echo '</tr>';
    }
    echo '</table>';
} else {
    echo '<p>No logs available.</p>';
}
?> 