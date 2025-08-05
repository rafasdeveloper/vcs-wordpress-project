# VCS Payment API Installation

## Requirements

- WordPress 5.0+
- WooCommerce 5.0+
- WooCommerce PayPal Payments plugin (active and configured)
- PHP 7.4+
- Composer (for PayPal SDK installation)

## Installation Steps

1. Upload the plugin to your WordPress plugins directory
2. Navigate to the plugin directory in your terminal:
   ```bash
   cd wp-content/plugins/vcs-payment-api
   ```
3. Install PayPal SDK dependencies using Composer:
   ```bash
   composer install
   ```
4. Activate the plugin in WordPress admin
5. Configure your PayPal settings in WooCommerce > Settings > Payments > PayPal

## PayPal SDK Installation

The plugin requires the PayPal Server SDK for PHP. If you don't have Composer installed:

### Install Composer (if not already installed)
```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

### Install PayPal SDK
```bash
cd wp-content/plugins/vcs-payment-api
composer install
```

## Configuration

1. Ensure WooCommerce PayPal Payments plugin is installed and configured
2. Set up your PayPal sandbox or production credentials in WooCommerce
3. The VCS Payment API plugin will automatically use these credentials

## API Endpoints

After installation, the following endpoints will be available:

- `GET /wp-json/vcs-payment-api/v1/payments/methods` - Get available payment methods
- `POST /wp-json/vcs-payment-api/v1/payments/paypal/order/create` - Create PayPal order
- `POST /wp-json/vcs-payment-api/v1/payments/paypal/order/capture` - Capture PayPal order

## Testing

Visit WooCommerce > VCS Payment API in your WordPress admin to:
- View API documentation
- Test endpoints
- Check logs

## Troubleshooting

1. **"PayPal SDK not found"** - Run `composer install` in the plugin directory
2. **"PayPal client not initialized"** - Check your PayPal plugin configuration
3. **Authentication errors** - Verify your PayPal credentials in WooCommerce settings

For more detailed logs, check the API Logs section in the plugin admin page.