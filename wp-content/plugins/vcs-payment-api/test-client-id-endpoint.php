<?php
/**
 * Test script for PayPal Client ID endpoint
 * 
 * This file can be accessed directly to test the new PayPal client ID endpoint
 */

// Load WordPress
require_once('../../../wp-load.php');

// Check if user is admin
if (!current_user_can('manage_options')) {
    wp_die('Access denied');
}

echo '<h1>PayPal Client ID Endpoint Test</h1>';

// Test the endpoint URL
$endpoint_url = rest_url('vcs-payment-api/v1/payments/paypal/client-id');
echo '<h2>Endpoint URL:</h2>';
echo '<p><code>' . esc_url($endpoint_url) . '</code></p>';

// Test the endpoint directly
echo '<h2>Direct API Test:</h2>';
try {
    // Create a mock request
    $request = new WP_REST_Request('GET', '/vcs-payment-api/v1/payments/paypal/client-id');
    
    // Get the controller
    $controller = new VCS_Payment_API_Controller();
    
    // Call the endpoint
    $response = $controller->get_paypal_client_id($request);
    
    if (is_wp_error($response)) {
        echo '<p style="color: red;">✗ Error: ' . esc_html($response->get_error_message()) . '</p>';
        echo '<p><strong>Error Code:</strong> ' . esc_html($response->get_error_code()) . '</p>';
    } else {
        echo '<p style="color: green;">✓ Endpoint working correctly</p>';
        echo '<h3>Response Data:</h3>';
        echo '<pre>' . esc_html(json_encode($response->get_data(), JSON_PRETTY_PRINT)) . '</pre>';
    }
    
} catch (Exception $e) {
    echo '<p style="color: red;">✗ Exception: ' . esc_html($e->getMessage()) . '</p>';
}

// Test via HTTP request
echo '<h2>HTTP Request Test:</h2>';
$response = wp_remote_get($endpoint_url, array(
    'headers' => array(
        'Authorization' => 'Basic ' . base64_encode('admin:admin'), // This is just for testing
    ),
));

if (is_wp_error($response)) {
    echo '<p style="color: red;">✗ HTTP Error: ' . esc_html($response->get_error_message()) . '</p>';
} else {
    $status_code = wp_remote_retrieve_response_code($response);
    $body = wp_remote_retrieve_body($response);
    
    if ($status_code === 200) {
        echo '<p style="color: green;">✓ HTTP request successful (Status: ' . $status_code . ')</p>';
        echo '<h3>Response:</h3>';
        echo '<pre>' . esc_html($body) . '</pre>';
    } else {
        echo '<p style="color: orange;">⚠ HTTP request returned status: ' . $status_code . '</p>';
        echo '<p><strong>Response:</strong></p>';
        echo '<pre>' . esc_html($body) . '</pre>';
    }
}

echo '<h2>Usage Example for React:</h2>';
echo '<p>Here\'s how you can use this endpoint in your React application:</p>';
echo '<pre><code>// Fetch PayPal client ID
const getPayPalClientId = async () => {
    try {
        const response = await fetch(\'/wp-json/vcs-payment-api/v1/payments/paypal/client-id\', {
            method: \'GET\',
            headers: {
                \'Content-Type\': \'application/json\',
                // Add your authentication headers here
            }
        });
        
        if (!response.ok) {
            throw new Error(\'Failed to fetch PayPal client ID\');
        }
        
        const data = await response.json();
        return data;
    } catch (error) {
        console.error(\'Error fetching PayPal client ID:\', error);
        throw error;
    }
};

// Initialize PayPal SDK
const initializePayPal = async () => {
    const paypalConfig = await getPayPalClientId();
    
    paypal.Buttons({
        clientId: paypalConfig.client_id,
        environment: paypalConfig.environment, // \'sandbox\' or \'production\'
        // ... other PayPal configuration
    }).render(\'#paypal-button-container\');
};</code></pre>';

echo '<p><a href="' . admin_url('admin.php?page=vcs-payment-api') . '">← Back to VCS Payment API Settings</a></p>';
?> 