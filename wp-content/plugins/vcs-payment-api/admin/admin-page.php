<?php
/**
 * VCS Payment API Admin Page
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    
    <div class="vcs-payment-api-admin">
        <!-- Settings Tab -->
        <div class="vcs-payment-api-section">
            <h2><?php _e('API Settings', 'vcs-payment-api'); ?></h2>
            
            <div class="notice notice-info">
                <p><strong><?php _e('Payment Method Settings:', 'vcs-payment-api'); ?></strong> <?php _e('Use the checkboxes below to enable or disable specific payment methods. Changes will be saved when you click "Save Changes".', 'vcs-payment-api'); ?></p>
            </div>
            
            <form method="post" action="options.php">
                <?php
                settings_fields('vcs_payment_api_options');
                do_settings_sections('vcs_payment_api_options');
                submit_button();
                ?>
            </form>
        </div>
        
        <!-- PayPal Configuration Tab -->
        <div class="vcs-payment-api-section">
            <h2><?php _e('PayPal Configuration', 'vcs-payment-api'); ?></h2>
            
            <div class="vcs-payment-config">
                <p><?php _e('Current PayPal configuration from WooCommerce PayPal Payments plugin:', 'vcs-payment-api'); ?></p>
                
                <form method="post" action="">
                    <input type="hidden" name="vcs_action" value="refresh_paypal_settings">
                    <?php submit_button(__('Refresh PayPal Settings', 'vcs-payment-api'), 'secondary', 'refresh_paypal_settings', false); ?>
                </form>
                
                <?php
                // Handle refresh action
                if (isset($_POST['vcs_action']) && $_POST['vcs_action'] === 'refresh_paypal_settings') {
                    // Clear any cached settings by creating a new instance
                    $paypal_handler = new VCS_PayPal_Handler();
                    echo '<div class="updated"><p>' . __('PayPal settings refreshed. Check the logs below for details.', 'vcs-payment-api') . '</p></div>';
                }
                
                $paypal_handler = new VCS_PayPal_Handler();
                ?>
                
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php _e('Environment', 'vcs-payment-api'); ?></th>
                        <td>
                            <?php
                            $is_sandbox = $paypal_handler->is_sandbox_mode();
                            echo '<span class="environment-badge ' . ($is_sandbox ? 'sandbox' : 'production') . '">';
                            echo $is_sandbox ? __('Sandbox', 'vcs-payment-api') : __('Production', 'vcs-payment-api');
                            echo '</span>';
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Client ID', 'vcs-payment-api'); ?></th>
                        <td>
                            <div class="password-field-container">
                                <input type="password" 
                                       id="paypal-client-id" 
                                       class="regular-text password-field" 
                                       value="<?php echo esc_attr($paypal_handler->get_client_id()); ?>" 
                                       readonly />
                                <button type="button" 
                                        class="button toggle-password" 
                                        data-target="paypal-client-id">
                                    <span class="dashicons dashicons-visibility"></span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Client Secret', 'vcs-payment-api'); ?></th>
                        <td>
                            <div class="password-field-container">
                                <input type="password" 
                                       id="paypal-client-secret" 
                                       class="regular-text password-field" 
                                       value="<?php echo esc_attr($paypal_handler->get_client_secret()); ?>" 
                                       readonly />
                                <button type="button" 
                                        class="button toggle-password" 
                                        data-target="paypal-client-secret">
                                    <span class="dashicons dashicons-visibility"></span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Merchant ID', 'vcs-payment-api'); ?></th>
                        <td>
                            <div class="password-field-container">
                                <input type="password" 
                                       id="paypal-merchant-id" 
                                       class="regular-text password-field" 
                                       value="<?php echo esc_attr($paypal_handler->get_merchant_id()); ?>" 
                                       readonly />
                                <button type="button" 
                                        class="button toggle-password" 
                                        data-target="paypal-merchant-id">
                                    <span class="dashicons dashicons-visibility"></span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Status', 'vcs-payment-api'); ?></th>
                        <td>
                            <?php
                            if ($paypal_handler->is_configured()) {
                                echo '<span class="status-badge configured">' . __('Configured', 'vcs-payment-api') . '</span>';
                            } else {
                                echo '<span class="status-badge not-configured">' . __('Not Configured', 'vcs-payment-api') . '</span>';
                                echo '<p class="description">' . __('Please configure PayPal in WooCommerce > Settings > Payments > PayPal', 'vcs-payment-api') . '</p>';
                            }
                            ?>
                        </td>
                    </tr>
                </table>
                
                <!-- Debug Information -->
                <details style="margin-top: 20px;">
                    <summary><?php _e('Debug Information', 'vcs-payment-api'); ?></summary>
                    <div style="background: #f9f9f9; padding: 15px; margin-top: 10px; border: 1px solid #ddd;">
                        <h4><?php _e('Plugin Status:', 'vcs-payment-api'); ?></h4>
                        <ul>
                            <li><strong><?php _e('WooCommerce Active:', 'vcs-payment-api'); ?></strong> <?php echo class_exists('WooCommerce') ? __('Yes', 'vcs-payment-api') : __('No', 'vcs-payment-api'); ?></li>
                            <li><strong><?php _e('PayPal Payments Plugin Active:', 'vcs-payment-api'); ?></strong> <?php echo function_exists('woocommerce_paypal_payments') ? __('Yes', 'vcs-payment-api') : __('No', 'vcs-payment-api'); ?></li>
                            <li><strong><?php _e('PayPal Plugin Initialized:', 'vcs-payment-api'); ?></strong> <?php echo did_action('woocommerce_paypal_payments_init') ? __('Yes', 'vcs-payment-api') : __('No', 'vcs-payment-api'); ?></li>
                        </ul>
                        
                        <h4><?php _e('WooCommerce Options:', 'vcs-payment-api'); ?></h4>
                        <?php
                        $paypal_settings = get_option('woocommerce_ppcp-gateway_settings', array());
                        if (!empty($paypal_settings)) {
                            echo '<p><strong>' . __('PayPal Gateway Settings Keys:', 'vcs-payment-api') . '</strong></p>';
                            echo '<ul>';
                            foreach (array_keys($paypal_settings) as $key) {
                                echo '<li>' . esc_html($key) . '</li>';
                            }
                            echo '</ul>';
                        } else {
                            echo '<p>' . __('No PayPal gateway settings found in WooCommerce options.', 'vcs-payment-api') . '</p>';
                        }
                        ?>
                        
                        <h4><?php _e('General Settings Data:', 'vcs-payment-api'); ?></h4>
                        <?php
                        if (class_exists('\WooCommerce\PayPalCommerce\PPCP')) {
                            try {
                                $paypal_container = \WooCommerce\PayPalCommerce\PPCP::container();
                                $general_settings = $paypal_container->get('settings.data.general');
                                
                                if ($general_settings) {
                                    // Use reflection to access the protected data property
                                    $reflection = new ReflectionClass($general_settings);
                                    $data_property = $reflection->getProperty('data');
                                    $data_property->setAccessible(true);
                                    $data = $data_property->getValue($general_settings);
                                    
                                    echo '<p><strong>' . __('General Settings Data Keys:', 'vcs-payment-api') . '</strong></p>';
                                    echo '<ul>';
                                    foreach (array_keys($data) as $key) {
                                        $value = $data[$key];
                                        if (is_bool($value)) {
                                            $display_value = $value ? 'true' : 'false';
                                        } elseif (is_string($value) && strlen($value) > 50) {
                                            $display_value = substr($value, 0, 50) . '...';
                                        } else {
                                            $display_value = $value;
                                        }
                                        echo '<li><strong>' . esc_html($key) . ':</strong> ' . esc_html($display_value) . '</li>';
                                    }
                                    echo '</ul>';
                                    
                                    echo '<p><strong>' . __('Merchant Connection Status:', 'vcs-payment-api') . '</strong></p>';
                                    echo '<ul>';
                                    echo '<li><strong>is_merchant_connected:</strong> ' . ($general_settings->is_merchant_connected() ? 'Yes' : 'No') . '</li>';
                                    echo '<li><strong>is_sandbox_merchant:</strong> ' . ($general_settings->is_sandbox_merchant() ? 'Yes' : 'No') . '</li>';
                                    echo '<li><strong>merchant_id:</strong> ' . ($general_settings->get_merchant_id() ?: 'Not set') . '</li>';
                                    echo '</ul>';
                                } else {
                                    echo '<p>' . __('Could not retrieve general settings from PayPal plugin container.', 'vcs-payment-api') . '</p>';
                                }
                            } catch (Exception $e) {
                                echo '<p>' . __('Error accessing PayPal container: ', 'vcs-payment-api') . esc_html($e->getMessage()) . '</p>';
                            }
                        } else {
                            echo '<p>' . __('PPCP class not found. PayPal plugin may not be properly loaded.', 'vcs-payment-api') . '</p>';
                        }
                        ?>
                    </div>
                </details>
            </div>
        </div>
        
        <!-- Payment Methods Management Tab -->
        <div class="vcs-payment-api-section">
            <h2><?php _e('Payment Methods Management', 'vcs-payment-api'); ?></h2>
            
            <div class="vcs-payment-methods">
                <p><?php _e('Enable or disable specific payment methods for the API. Disabled methods will not be available through the API endpoints.', 'vcs-payment-api'); ?></p>
                
                <?php
                $payment_methods = array(
                    'paypal' => array(
                        'name' => __('PayPal', 'vcs-payment-api'),
                        'description' => __('Pay with PayPal account', 'vcs-payment-api'),
                        'status' => VCS_Payment_API::is_payment_method_enabled('paypal') ? 'enabled' : 'disabled',
                        'available' => class_exists('\WooCommerce\PayPalCommerce\PPCP')
                    ),
                    'credit_card' => array(
                        'name' => __('Credit Card (via PayPal)', 'vcs-payment-api'),
                        'description' => __('Pay with credit card powered by PayPal', 'vcs-payment-api'),
                        'status' => VCS_Payment_API::is_payment_method_enabled('credit_card') ? 'enabled' : 'disabled',
                        'available' => class_exists('\WooCommerce\PayPalCommerce\PPCP')
                    )
                );
                ?>
                
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th><?php _e('Payment Method', 'vcs-payment-api'); ?></th>
                            <th><?php _e('Description', 'vcs-payment-api'); ?></th>
                            <th><?php _e('Status', 'vcs-payment-api'); ?></th>
                            <th><?php _e('Availability', 'vcs-payment-api'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($payment_methods as $method_key => $method_data): ?>
                        <tr>
                            <td><strong><?php echo esc_html($method_data['name']); ?></strong></td>
                            <td><?php echo esc_html($method_data['description']); ?></td>
                            <td>
                                <span class="status-badge <?php echo $method_data['status']; ?>">
                                    <?php echo $method_data['status'] === 'enabled' ? __('Enabled', 'vcs-payment-api') : __('Disabled', 'vcs-payment-api'); ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($method_data['available']): ?>
                                    <span class="status-badge available"><?php _e('Available', 'vcs-payment-api'); ?></span>
                                <?php else: ?>
                                    <span class="status-badge unavailable"><?php _e('Not Available', 'vcs-payment-api'); ?></span>
                                    <p class="description"><?php _e('Required plugin not installed or configured', 'vcs-payment-api'); ?></p>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <div style="margin-top: 20px;">
                    <p><strong><?php _e('Note:', 'vcs-payment-api'); ?></strong> <?php _e('To change payment method settings, go to the API Settings section above and use the checkboxes to enable/disable payment methods.', 'vcs-payment-api'); ?></p>
                    
                    <h4><?php _e('Test Payment Methods API', 'vcs-payment-api'); ?></h4>
                    <button type="button" class="button button-secondary" onclick="testPaymentMethodsFromAdmin()">
                        <?php _e('Test GET /payments/methods', 'vcs-payment-api'); ?>
                    </button>
                    <div id="payment-methods-test-results" class="test-results" style="margin-top: 10px;"></div>
                    
                    <h4><?php _e('Current Settings Debug', 'vcs-payment-api'); ?></h4>
                    <details>
                        <summary><?php _e('Show current payment method settings', 'vcs-payment-api'); ?></summary>
                        <div style="background: #f9f9f9; padding: 15px; margin-top: 10px; border: 1px solid #ddd;">
                            <?php
                            $current_settings = get_option('vcs_payment_api_settings', array());
                            echo '<p><strong>Raw Settings:</strong></p>';
                            echo '<pre>' . print_r($current_settings, true) . '</pre>';
                            
                            echo '<p><strong>Payment Method Status:</strong></p>';
                            echo '<ul>';
                            echo '<li>PayPal Enabled: ' . (VCS_Payment_API::is_payment_method_enabled('paypal') ? 'Yes' : 'No') . '</li>';
                            echo '<li>Credit Card Enabled: ' . (VCS_Payment_API::is_payment_method_enabled('credit_card') ? 'Yes' : 'No') . '</li>';
                            echo '</ul>';
                            ?>
                        </div>
                    </details>
                </div>
            </div>
        </div>
        
        <!-- API Documentation Tab -->
        <div class="vcs-payment-api-section">
            <h2><?php _e('API Documentation', 'vcs-payment-api'); ?></h2>
            
            <div class="vcs-payment-api-docs">
                <h3><?php _e('Base URL', 'vcs-payment-api'); ?></h3>
                <code><?php echo esc_url(rest_url('vcs-payment-api/v1')); ?></code>
                
                <h3><?php _e('Authentication', 'vcs-payment-api'); ?></h3>
                <p><?php _e('All API endpoints require WordPress authentication. Use WordPress REST API authentication methods such as:', 'vcs-payment-api'); ?></p>
                <ul>
                    <li><?php _e('Application Passwords', 'vcs-payment-api'); ?></li>
                    <li><?php _e('JWT Authentication', 'vcs-payment-api'); ?></li>
                    <li><?php _e('OAuth 2.0', 'vcs-payment-api'); ?></li>
                </ul>
                
                <h3><?php _e('Available Endpoints', 'vcs-payment-api'); ?></h3>
                
                <!-- Get Payment Methods -->
                <div class="endpoint-doc">
                    <h4>GET /payments/methods</h4>
                    <p><?php _e('Get available payment methods', 'vcs-payment-api'); ?></p>
                    <div class="endpoint-example">
                        <strong><?php _e('Example:', 'vcs-payment-api'); ?></strong>
                        <code>GET <?php echo esc_url(rest_url('vcs-payment-api/v1/payments/methods')); ?></code>
                    </div>
                </div>
                
                <!-- PayPal Create Order -->
                <div class="endpoint-doc">
                    <h4>POST /payments/paypal/order/create</h4>
                    <p><?php _e('Create PayPal order', 'vcs-payment-api'); ?></p>
                    <div class="endpoint-example">
                        <strong><?php _e('Request Body:', 'vcs-payment-api'); ?></strong>
                        <pre><code>{
    "intent": "CAPTURE",
    "amount": 99.99,
    "currency": "USD",
    "description": "Payment for services"
}</code></pre>
                    </div>
                </div>
                
                <!-- PayPal Client ID -->
                <div class="endpoint-doc">
                    <h4>GET /payments/paypal/client-id</h4>
                    <p><?php _e('Get PayPal client ID and environment information for frontend integration', 'vcs-payment-api'); ?></p>
                    <div class="endpoint-example">
                        <strong><?php _e('Example:', 'vcs-payment-api'); ?></strong>
                        <code>GET <?php echo esc_url(rest_url('vcs-payment-api/v1/payments/paypal/client-id')); ?></code>
                    </div>
                    <div class="endpoint-example">
                        <strong><?php _e('Response:', 'vcs-payment-api'); ?></strong>
                        <pre><code>{
    "client_id": "YOUR_PAYPAL_CLIENT_ID",
    "environment": "sandbox",
    "merchant_id": "YOUR_MERCHANT_ID",
    "configured": true
}</code></pre>
                    </div>
                    <p><em><?php _e('This endpoint is useful for React/frontend applications that need to initialize PayPal SDK with the correct client ID and environment.', 'vcs-payment-api'); ?></em></p>
                </div>
                
                <!-- PayPal Capture Order -->
                <div class="endpoint-doc">
                    <h4>POST /payments/paypal/order/capture</h4>
                    <p><?php _e('Capture PayPal order', 'vcs-payment-api'); ?></p>
                    <div class="endpoint-example">
                        <strong><?php _e('Request Body:', 'vcs-payment-api'); ?></strong>
                        <pre><code>{
    "order_id": "PAYPAL_ORDER_ID_HERE"
}</code></pre>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Test API Tab -->
        <div class="vcs-payment-api-section">
            <h2><?php _e('Test API', 'vcs-payment-api'); ?></h2>
            
            <div class="vcs-payment-api-test">
                <h3><?php _e('Test Payment Methods Endpoint', 'vcs-payment-api'); ?></h3>
                <button type="button" class="button button-secondary" onclick="testPaymentMethods()">
                    <?php _e('Test GET /payments/methods', 'vcs-payment-api'); ?>
                </button>
                <div id="test-results" class="test-results"></div>
            </div>
        </div>
        
        <!-- Logs Tab -->
        <div class="vcs-payment-api-section">
            <h2><?php _e('API Logs', 'vcs-payment-api'); ?></h2>
            
            <div class="vcs-payment-api-logs">
                <form method="post" action="">
                    <input type="hidden" name="vcs_action" value="clear_logs">
                    <?php submit_button(__('Clear Logs', 'vcs-payment-api'), 'delete', 'clear_logs_button', false); ?>
                </form>
                
                <div id="api-logs" class="api-logs">
                    <?php
                    if (isset($_POST['vcs_action']) && $_POST['vcs_action'] === 'clear_logs') {
                        VCS_Logger::clear_logs();
                        echo '<div class="updated"><p>' . __('Logs cleared.', 'vcs-payment-api') . '</p></div>';
                    }
                    
                    $logs = array_reverse(VCS_Logger::get_logs());
                    
                    if (!empty($logs)) {
                        echo '<table class="wp-list-table widefat fixed striped">';
                        echo '<thead><tr><th style="width: 150px;">Date</th><th style="width: 80px;">Level</th><th>Message</th></tr></thead>';
                        echo '<tbody>';
                        foreach ($logs as $log) {
                            echo '<tr>';
                            echo '<td>' . esc_html(date('Y-m-d H:i:s', $log['timestamp'])) . '</td>';
                            echo '<td><span class="log-level log-level-' . esc_attr($log['level']) . '">' . esc_html($log['level']) . '</span></td>';
                            echo '<td><pre>' . esc_html($log['message']) . '</pre></td>';
                            echo '</tr>';
                        }
                        echo '</tbody></table>';
                    } else {
                        echo '<p>' . __('No logs available.', 'vcs-payment-api') . '</p>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.vcs-payment-api-admin {
    max-width: 1200px;
}

.vcs-payment-api-section {
    margin-bottom: 30px;
    padding: 20px;
    background: #fff;
    border: 1px solid #ccd0d4;
    box-shadow: 0 1px 1px rgba(0,0,0,.04);
}

.vcs-payment-api-section h2 {
    margin-top: 0;
    border-bottom: 1px solid #eee;
    padding-bottom: 10px;
}

.vcs-payment-api-docs h3 {
    color: #23282d;
    margin-top: 30px;
}

.endpoint-doc {
    margin-bottom: 30px;
    padding: 15px;
    background: #f9f9f9;
    border-left: 4px solid #0073aa;
}

.endpoint-doc h4 {
    margin-top: 0;
    color: #0073aa;
}

.endpoint-example {
    margin-top: 10px;
}

.endpoint-example code {
    background: #fff;
    padding: 10px;
    display: block;
    border: 1px solid #ddd;
    border-radius: 3px;
    font-family: monospace;
    white-space: pre-wrap;
}

.test-results {
    margin-top: 15px;
    padding: 15px;
    background: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 3px;
    display: none;
}

.api-logs {
    margin-top: 15px;
}

.api-logs table {
    margin-top: 10px;
}

.api-logs th {
    font-weight: bold;
}

.log-level {
    padding: 2px 6px;
    border-radius: 3px;
    color: #fff;
    font-size: 11px;
    text-transform: uppercase;
}

.log-level-info {
    background: #0073aa;
}

.log-level-debug {
    background: #777;
}

.log-level-error {
    background: #d63638;
}

.api-logs pre {
    margin: 0;
    padding: 0;
    white-space: pre-wrap;
    word-wrap: break-word;
}

/* PayPal Configuration Styles */
.password-field-container {
    position: relative;
    display: inline-block;
    width: 100%;
    max-width: 400px;
}

.password-field {
    padding-right: 40px !important;
}

.toggle-password {
    position: absolute;
    right: 5px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    cursor: pointer;
    padding: 5px;
    color: #666;
}

.toggle-password:hover {
    color: #0073aa;
}

.toggle-password .dashicons {
    font-size: 16px;
    width: 16px;
    height: 16px;
}

.environment-badge {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 3px;
    font-size: 12px;
    font-weight: bold;
    text-transform: uppercase;
}

.environment-badge.sandbox {
    background-color: #ffd700;
    color: #333;
}

.environment-badge.production {
    background-color: #28a745;
    color: white;
}

.status-badge {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 3px;
    font-size: 12px;
    font-weight: bold;
    text-transform: uppercase;
}

.status-badge.configured {
    background-color: #28a745;
    color: white;
}

.status-badge.not-configured {
    background-color: #dc3545;
    color: white;
}

.status-badge.enabled {
    background-color: #28a745;
    color: white;
}

.status-badge.disabled {
    background-color: #6c757d;
    color: white;
}

.status-badge.available {
    background-color: #17a2b8;
    color: white;
}

.status-badge.unavailable {
    background-color: #ffc107;
    color: #212529;
}

.vcs-payment-config .form-table th {
    width: 150px;
}
</style>

<script>
function testPaymentMethods() {
    const resultsDiv = document.getElementById('test-results');
    resultsDiv.style.display = 'block';
    resultsDiv.innerHTML = 'Testing...';
    
    fetch('<?php echo esc_url(rest_url('vcs-payment-api/v1/payments/methods')); ?>', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-WP-Nonce': '<?php echo wp_create_nonce('wp_rest'); ?>'
        }
    })
    .then(response => response.json())
    .then(data => {
        resultsDiv.innerHTML = '<strong>Success!</strong><br><pre>' + JSON.stringify(data, null, 2) + '</pre>';
    })
    .catch(error => {
        resultsDiv.innerHTML = '<strong>Error:</strong><br><pre>' + error.message + '</pre>';
    });
}

function testPaymentMethodsFromAdmin() {
    const resultsDiv = document.getElementById('payment-methods-test-results');
    resultsDiv.style.display = 'block';
    resultsDiv.innerHTML = 'Testing...';
    
    fetch('<?php echo esc_url(rest_url('vcs-payment-api/v1/payments/methods')); ?>', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-WP-Nonce': '<?php echo wp_create_nonce('wp_rest'); ?>'
        }
    })
    .then(response => response.json())
    .then(data => {
        let html = '<strong>Success!</strong><br><pre>' + JSON.stringify(data, null, 2) + '</pre>';
        
        // Add analysis of enabled/disabled methods
        if (data && typeof data === 'object') {
            const enabledMethods = Object.keys(data);
            html += '<br><strong>Currently Enabled Methods:</strong><br>';
            if (enabledMethods.length > 0) {
                enabledMethods.forEach(method => {
                    html += '- ' + method + '<br>';
                });
            } else {
                html += '<em>No payment methods are currently enabled.</em><br>';
            }
        }
        
        resultsDiv.innerHTML = html;
    })
    .catch(error => {
        resultsDiv.innerHTML = '<strong>Error:</strong><br><pre>' + error.message + '</pre>';
    });
}

// Toggle password visibility
document.addEventListener('DOMContentLoaded', function() {
    const toggleButtons = document.querySelectorAll('.toggle-password');
    
    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const icon = this.querySelector('.dashicons');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('dashicons-visibility');
                icon.classList.add('dashicons-hidden');
            } else {
                input.type = 'password';
                icon.classList.remove('dashicons-hidden');
                icon.classList.add('dashicons-visibility');
            }
        });
    });
});
</script> 