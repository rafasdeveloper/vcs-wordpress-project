# PayPal SDK Setup Guide

This guide explains how to set up the PayPal Server SDK for the VCS Payment API plugin.

## Overview

The VCS Payment API plugin uses the PayPal Server SDK (v1.1.0) for all PayPal integrations. This SDK provides access to PayPal's latest REST APIs with improved error handling and logging.

## Installation

### Using Composer (Required)

1. Navigate to the plugin directory:
   ```bash
   cd wp-content/plugins/vcs-payment-api
   ```

2. Install dependencies:
   ```bash
   composer install
   ```

3. Verify installation:
   ```bash
   composer show paypal/paypal-server-sdk
   ```

## SDK Information

### PayPal Server SDK (v1.1.0)
- **Package**: `paypal/paypal-server-sdk`
- **Version**: `^1.1.0`
- **Features**: 
  - Orders API v2
  - Payments API v2
  - Vault Controller (US only)
  - Better error handling
  - Improved logging
  - Enhanced security

## Configuration

The plugin uses the PayPal Server SDK for all PayPal operations:

### SDK Initialization
```php
$client = PaypalServerSdkClientBuilder::init()
    ->clientCredentialsAuthCredentials(
        ClientCredentialsAuthCredentialsBuilder::init(
            $client_id,
            $client_secret
        )
    )
    ->environment($is_sandbox ? Environment::SANDBOX : Environment::PRODUCTION)
    ->loggingConfiguration(
        LoggingConfigurationBuilder::init()
            ->level(LogLevel::INFO)
            ->requestConfiguration(RequestLoggingConfigurationBuilder::init()->body(true))
            ->responseConfiguration(ResponseLoggingConfigurationBuilder::init()->headers(true))
    )
    ->build();
```

## API Endpoints

The plugin provides the following endpoints:

### Get PayPal Client ID
```
GET /wp-json/vcs-payment-api/v1/payments/paypal/client-id
```

### Create PayPal Order
```
POST /wp-json/vcs-payment-api/v1/payments/paypal/order/create
```

### Capture PayPal Order
```
POST /wp-json/vcs-payment-api/v1/payments/paypal/order/capture
```

## Troubleshooting

### Common Issues

1. **"PayPal SDK not available" error**
   - Run `composer install` in the plugin directory
   - Check that the `vendor/autoload.php` file exists

2. **Authentication errors**
   - Verify your PayPal Client ID and Secret are correct
   - Check that the environment (sandbox/production) matches your credentials

3. **Composer not found**
   - Install Composer from https://getcomposer.org/
   - Ensure Composer is in your system PATH

### Logging

The plugin includes comprehensive logging. Check the WordPress admin logs or enable debug logging to troubleshoot issues:

```php
// Enable debug logging
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
```

## Requirements

- **PHP**: 7.4 or higher
- **WordPress**: 5.0 or higher
- **WooCommerce**: 5.0 or higher
- **Composer**: Required for dependency management

## Support

For issues with the PayPal SDK itself, refer to:
- [PayPal Server SDK Documentation](https://github.com/paypal/PayPal-PHP-Server-SDK)
- [PayPal Developer Portal](https://developer.paypal.com/)

For issues with the VCS Payment API plugin, check the plugin's main documentation. 