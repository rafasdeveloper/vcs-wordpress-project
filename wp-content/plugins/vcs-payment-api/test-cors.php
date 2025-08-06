<?php
/**
 * CORS Test Script
 * 
 * This script helps test if CORS headers are working correctly
 */

// Load WordPress
require_once('../../../wp-load.php');

// Check if user is admin
if (!current_user_can('manage_options')) {
    wp_die('Access denied');
}

echo '<h1>CORS Headers Test</h1>';

// Test the PayPal client ID endpoint
$endpoint_url = rest_url('vcs-payment-api/v1/payments/paypal/client-id');
echo '<h2>Testing Endpoint:</h2>';
echo '<p><code>' . esc_url($endpoint_url) . '</code></p>';

// Test with curl to check headers
echo '<h2>CORS Headers Test:</h2>';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $endpoint_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Origin: http://localhost:8081',
    'Access-Control-Request-Method: GET',
    'Access-Control-Request-Headers: Content-Type'
));

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo '<h3>Response Headers:</h3>';
echo '<pre>' . esc_html($response) . '</pre>';

echo '<h3>HTTP Status Code:</h3>';
echo '<p>' . $http_code . '</p>';

// Test OPTIONS request (preflight)
echo '<h2>Preflight OPTIONS Test:</h2>';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $endpoint_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'OPTIONS');
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Origin: http://localhost:8081',
    'Access-Control-Request-Method: GET',
    'Access-Control-Request-Headers: Content-Type'
));

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo '<h3>OPTIONS Response Headers:</h3>';
echo '<pre>' . esc_html($response) . '</pre>';

echo '<h3>OPTIONS HTTP Status Code:</h3>';
echo '<p>' . $http_code . '</p>';

// JavaScript test
echo '<h2>JavaScript Test:</h2>';
echo '<p>Open your browser console and run this JavaScript:</p>';
echo '<pre><code>fetch(\'' . esc_url($endpoint_url) . '\', {
    method: \'GET\',
    headers: {
        \'Content-Type\': \'application/json\'
    }
})
.then(response => {
    console.log(\'Response status:\', response.status);
    console.log(\'CORS headers:\', response.headers);
    return response.json();
})
.then(data => {
    console.log(\'Response data:\', data);
})
.catch(error => {
    console.error(\'Error:\', error);
});</code></pre>';

echo '<p><a href="' . admin_url('admin.php?page=vcs-payment-api') . '">← Back to VCS Payment API Settings</a></p>';
?> 