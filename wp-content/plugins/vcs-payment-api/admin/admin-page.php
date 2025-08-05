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
            
            <form method="post" action="options.php">
                <?php
                settings_fields('vcs_payment_api_options');
                do_settings_sections('vcs_payment_api_options');
                submit_button();
                ?>
            </form>
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
                
                <!-- PayPal Payment -->
                <div class="endpoint-doc">
                    <h4>POST /payments/paypal</h4>
                    <p><?php _e('Process PayPal payment', 'vcs-payment-api'); ?></p>
                    <div class="endpoint-example">
                        <strong><?php _e('Request Body:', 'vcs-payment-api'); ?></strong>
                        <pre><code>{
    "amount": 99.99,
    "currency": "USD",
    "order_id": "order-123",
    "description": "Payment for services",
    "return_url": "https://example.com/success",
    "cancel_url": "https://example.com/cancel"
}</code></pre>
                    </div>
                </div>
                
                <!-- PayPal Credit Card -->
                <div class="endpoint-doc">
                    <h4>POST /payments/paypal/credit-card</h4>
                    <p><?php _e('Process PayPal credit card payment', 'vcs-payment-api'); ?></p>
                    <div class="endpoint-example">
                        <strong><?php _e('Request Body:', 'vcs-payment-api'); ?></strong>
                        <pre><code>{
    "amount": 99.99,
    "currency": "USD",
    "card_number": "4111111111111111",
    "expiry_month": 12,
    "expiry_year": 2025,
    "cvv": "123",
    "order_id": "order-123",
    "description": "Credit card payment"
}</code></pre>
                    </div>
                </div>
                
                <!-- BTCPay Payment -->
                <div class="endpoint-doc">
                    <h4>POST /payments/btcpay</h4>
                    <p><?php _e('Process BTCPay cryptocurrency payment', 'vcs-payment-api'); ?></p>
                    <div class="endpoint-example">
                        <strong><?php _e('Request Body:', 'vcs-payment-api'); ?></strong>
                        <pre><code>{
    "amount": 0.001,
    "currency": "BTC",
    "order_id": "order-123",
    "description": "Bitcoin payment",
    "notification_url": "https://example.com/webhook"
}</code></pre>
                    </div>
                </div>
                
                <!-- WooPayments -->
                <div class="endpoint-doc">
                    <h4>POST /payments/woopayments</h4>
                    <p><?php _e('Process WooPayments (Stripe) payment', 'vcs-payment-api'); ?></p>
                    <div class="endpoint-example">
                        <strong><?php _e('Request Body:', 'vcs-payment-api'); ?></strong>
                        <pre><code>{
    "amount": 99.99,
    "currency": "usd",
    "payment_method_id": "pm_1234567890",
    "order_id": "order-123",
    "description": "Stripe payment",
    "customer_id": "cus_1234567890"
}</code></pre>
                    </div>
                </div>
                
                <!-- Payment Status -->
                <div class="endpoint-doc">
                    <h4>GET /payments/status/{order_id}</h4>
                    <p><?php _e('Get payment status for an order', 'vcs-payment-api'); ?></p>
                    <div class="endpoint-example">
                        <strong><?php _e('Example:', 'vcs-payment-api'); ?></strong>
                        <code>GET <?php echo esc_url(rest_url('vcs-payment-api/v1/payments/status/123')); ?></code>
                    </div>
                </div>
                
                <!-- Webhooks -->
                <div class="endpoint-doc">
                    <h4>Webhook Endpoints</h4>
                    <p><?php _e('Webhook URLs for payment notifications:', 'vcs-payment-api'); ?></p>
                    <ul>
                        <li><strong>PayPal:</strong> <code><?php echo esc_url(rest_url('vcs-payment-api/v1/payments/webhook/paypal')); ?></code></li>
                        <li><strong>BTCPay:</strong> <code><?php echo esc_url(rest_url('vcs-payment-api/v1/payments/webhook/btcpay')); ?></code></li>
                    </ul>
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
</script> 