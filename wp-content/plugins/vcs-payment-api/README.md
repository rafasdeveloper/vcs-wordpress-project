# VCS Payment API Plugin

A comprehensive WordPress plugin that exposes REST APIs for various payment methods including PayPal, BTCPay, and WooPayments.

## Features

- **PayPal Integration**: Process payments through PayPal accounts and credit cards
- **BTCPay Integration**: Accept cryptocurrency payments (Bitcoin, Litecoin, Ethereum, etc.)
- **WooPayments Integration**: Process payments through WooPayments (Stripe)
- **REST API**: Clean, RESTful API endpoints for all payment methods
- **Webhook Support**: Handle payment notifications via webhooks
- **Order Management**: Automatic WooCommerce order creation and management
- **Validation**: Comprehensive input validation for all payment requests
- **Admin Interface**: Easy-to-use admin panel with API documentation

## Requirements

- WordPress 5.0 or higher
- WooCommerce 5.0 or higher
- PHP 7.4 or higher
- One or more of the following payment plugins:
  - WooCommerce PayPal Payments
  - BTCPay Greenfield for WooCommerce
  - WooPayments

## Installation

1. Upload the `vcs-payment-api` folder to your `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Configure your payment gateway settings in WooCommerce
4. Access the plugin settings at WooCommerce > VCS Payment API

## API Endpoints

### Base URL
```
https://your-site.com/wp-json/vcs-payment-api/v1
```

### Authentication
All endpoints require WordPress authentication. Use one of these methods:
- Application Passwords
- JWT Authentication
- OAuth 2.0

### Available Endpoints

#### 1. Get Payment Methods
```
GET /payments/methods
```
Returns available payment methods and their endpoints.

**Response:**
```json
{
    "paypal": {
        "name": "PayPal",
        "description": "Pay with PayPal account or credit card",
        "endpoints": {
            "payment": "/vcs-payment-api/v1/payments/paypal",
            "credit_card": "/vcs-payment-api/v1/payments/paypal/credit-card"
        }
    },
    "btcpay": {
        "name": "BTCPay",
        "description": "Pay with Bitcoin and other cryptocurrencies",
        "endpoints": {
            "payment": "/vcs-payment-api/v1/payments/btcpay"
        }
    },
    "woopayments": {
        "name": "WooPayments",
        "description": "Pay with WooPayments (Stripe)",
        "endpoints": {
            "payment": "/vcs-payment-api/v1/payments/woopayments"
        }
    }
}
```

#### 2. PayPal Payment
```
POST /payments/paypal
```

**Request Body:**
```json
{
    "amount": 99.99,
    "currency": "USD",
    "order_id": "order-123",
    "description": "Payment for services",
    "return_url": "https://example.com/success",
    "cancel_url": "https://example.com/cancel"
}
```

**Response:**
```json
{
    "success": true,
    "order_id": 123,
    "paypal_order_id": "PAY-123456789",
    "approval_url": "https://www.paypal.com/checkoutnow/...",
    "status": "pending",
    "message": "PayPal order created successfully"
}
```

#### 3. PayPal Credit Card Payment
```
POST /payments/paypal/credit-card
```

**Request Body:**
```json
{
    "amount": 99.99,
    "currency": "USD",
    "card_number": "4111111111111111",
    "expiry_month": 12,
    "expiry_year": 2025,
    "cvv": "123",
    "order_id": "order-123",
    "description": "Credit card payment"
}
```

#### 4. BTCPay Payment
```
POST /payments/btcpay
```

**Request Body:**
```json
{
    "amount": 0.001,
    "currency": "BTC",
    "order_id": "order-123",
    "description": "Bitcoin payment",
    "notification_url": "https://example.com/webhook"
}
```

**Response:**
```json
{
    "success": true,
    "order_id": 123,
    "invoice_id": "invoice-123",
    "checkout_url": "https://btcpay.example.com/invoice/...",
    "amount": 0.001,
    "currency": "BTC",
    "status": "New",
    "message": "BTCPay invoice created successfully"
}
```

#### 5. WooPayments Payment
```
POST /payments/woopayments
```

**Request Body:**
```json
{
    "amount": 99.99,
    "currency": "usd",
    "payment_method_id": "pm_1234567890",
    "order_id": "order-123",
    "description": "Stripe payment",
    "customer_id": "cus_1234567890"
}
```

**Response:**
```json
{
    "success": true,
    "order_id": 123,
    "payment_intent_id": "pi_1234567890",
    "client_secret": "pi_1234567890_secret_...",
    "status": "requires_payment_method",
    "amount": 9999,
    "currency": "usd",
    "message": "WooPayments payment intent created successfully"
}
```

#### 6. Get Payment Status
```
GET /payments/status/{order_id}
```

**Response:**
```json
{
    "order_id": "123",
    "status": "processing",
    "payment_method": "paypal",
    "total": "99.99",
    "currency": "USD",
    "created_at": "2024-01-01T12:00:00+00:00",
    "updated_at": "2024-01-01T12:05:00+00:00"
}
```

### Webhook Endpoints

#### PayPal Webhook
```
POST /payments/webhook/paypal
```

#### BTCPay Webhook
```
POST /payments/webhook/btcpay
```

## Usage Examples

### JavaScript Example (PayPal Payment)

```javascript
const paymentData = {
    amount: 99.99,
    currency: 'USD',
    order_id: 'order-123',
    description: 'Payment for services',
    return_url: 'https://example.com/success',
    cancel_url: 'https://example.com/cancel'
};

fetch('/wp-json/vcs-payment-api/v1/payments/paypal', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer YOUR_TOKEN'
    },
    body: JSON.stringify(paymentData)
})
.then(response => response.json())
.then(data => {
    if (data.success) {
        // Redirect to PayPal approval URL
        window.location.href = data.approval_url;
    } else {
        console.error('Payment failed:', data.error);
    }
});
```

### PHP Example (BTCPay Payment)

```php
$payment_data = array(
    'amount' => 0.001,
    'currency' => 'BTC',
    'order_id' => 'order-123',
    'description' => 'Bitcoin payment'
);

$response = wp_remote_post(rest_url('vcs-payment-api/v1/payments/btcpay'), array(
    'headers' => array(
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer ' . $token
    ),
    'body' => json_encode($payment_data)
));

$result = json_decode(wp_remote_retrieve_body($response), true);

if ($result['success']) {
    $checkout_url = $result['checkout_url'];
    // Redirect user to BTCPay checkout
} else {
    echo 'Payment failed: ' . $result['error'];
}
```

## Configuration

### PayPal Configuration
1. Install and configure WooCommerce PayPal Payments plugin
2. Set up your PayPal API credentials in WooCommerce > Settings > Payments > PayPal
3. The VCS Payment API will automatically use these settings

### BTCPay Configuration
1. Install and configure BTCPay Greenfield for WooCommerce plugin
2. Set up your BTCPay Server URL, API key, and Store ID
3. The VCS Payment API will automatically use these settings

### WooPayments Configuration
1. Install and configure WooPayments plugin
2. Set up your Stripe API keys in WooPayments settings
3. The VCS Payment API will automatically use these settings

## Security Considerations

1. **Authentication**: Always use proper authentication for API requests
2. **HTTPS**: Use HTTPS in production environments
3. **Webhook Verification**: Implement proper webhook signature verification
4. **Input Validation**: The plugin includes comprehensive input validation
5. **Rate Limiting**: Consider implementing rate limiting for production use

## Troubleshooting

### Common Issues

1. **"WooCommerce not found"**: Ensure WooCommerce is installed and activated
2. **"Payment gateway not configured"**: Configure your payment gateway settings
3. **"Invalid API credentials"**: Check your payment gateway API credentials
4. **"Webhook not working"**: Verify webhook URLs and signature verification

### Debug Mode

Enable debug mode in the plugin settings to get detailed error messages and logs.

### Logs

Check the API logs in the admin panel to troubleshoot issues:
- WooCommerce > VCS Payment API > API Logs

## Development

### Hooks and Filters

The plugin provides several hooks for customization:

```php
// Modify payment data before processing
add_filter('vcs_payment_api_paypal_payment_data', function($data, $params) {
    // Modify $data
    return $data;
}, 10, 2);

// Handle payment completion
add_action('vcs_payment_api_payment_completed', function($order_id, $payment_method) {
    // Custom logic after payment completion
}, 10, 2);
```

### Custom Payment Handlers

You can extend the plugin by creating custom payment handlers:

```php
class Custom_Payment_Handler {
    public function process_payment($params) {
        // Custom payment processing logic
        return array(
            'success' => true,
            'order_id' => $order_id,
            'message' => 'Payment processed successfully'
        );
    }
}
```

## Support

For support and questions:
- Check the plugin documentation in the admin panel
- Review the API logs for error details
- Ensure all required plugins are properly configured

## License

This plugin is licensed under the GPL v2 or later.

## Changelog

### Version 1.0.0
- Initial release
- PayPal payment processing
- BTCPay cryptocurrency payments
- WooPayments (Stripe) integration
- REST API endpoints
- Webhook support
- Admin interface with documentation 