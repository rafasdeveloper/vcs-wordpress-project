<?php
/**
 * VCS Payment Validator
 * 
 * Validates payment requests for all payment methods
 */

if (!defined('ABSPATH')) {
    exit;
}

class VCS_Payment_Validator {
    
    /**
     * Validate PayPal payment request
     */
    public function validate_paypal_payment($params) {
        $errors = array();
        
        // Required fields
        if (empty($params['amount'])) {
            $errors[] = 'Amount is required';
        } elseif (!is_numeric($params['amount']) || $params['amount'] <= 0) {
            $errors[] = 'Amount must be a positive number';
        }
        
        if (empty($params['currency'])) {
            $errors[] = 'Currency is required';
        } elseif (!in_array($params['currency'], array('USD', 'EUR', 'GBP', 'CAD', 'AUD'))) {
            $errors[] = 'Invalid currency. Supported currencies: USD, EUR, GBP, CAD, AUD';
        }
        
        if (empty($params['return_url'])) {
            $errors[] = 'Return URL is required';
        } elseif (!filter_var($params['return_url'], FILTER_VALIDATE_URL)) {
            $errors[] = 'Invalid return URL format';
        }
        
        if (empty($params['cancel_url'])) {
            $errors[] = 'Cancel URL is required';
        } elseif (!filter_var($params['cancel_url'], FILTER_VALIDATE_URL)) {
            $errors[] = 'Invalid cancel URL format';
        }
        
        // Optional fields validation
        if (!empty($params['order_id']) && !is_string($params['order_id'])) {
            $errors[] = 'Order ID must be a string';
        }
        
        if (!empty($params['description']) && !is_string($params['description'])) {
            $errors[] = 'Description must be a string';
        }
        
        return array(
            'valid' => empty($errors),
            'message' => empty($errors) ? 'Valid' : implode(', ', $errors)
        );
    }
    
    /**
     * Validate PayPal credit card payment request
     */
    public function validate_paypal_credit_card($params) {
        $errors = array();
        
        // Required fields
        if (empty($params['amount'])) {
            $errors[] = 'Amount is required';
        } elseif (!is_numeric($params['amount']) || $params['amount'] <= 0) {
            $errors[] = 'Amount must be a positive number';
        }
        
        if (empty($params['currency'])) {
            $errors[] = 'Currency is required';
        } elseif (!in_array($params['currency'], array('USD', 'EUR', 'GBP', 'CAD', 'AUD'))) {
            $errors[] = 'Invalid currency. Supported currencies: USD, EUR, GBP, CAD, AUD';
        }
        
        if (empty($params['card_number'])) {
            $errors[] = 'Card number is required';
        } elseif (!$this->validate_card_number($params['card_number'])) {
            $errors[] = 'Invalid card number format';
        }
        
        if (empty($params['expiry_month'])) {
            $errors[] = 'Expiry month is required';
        } elseif (!is_numeric($params['expiry_month']) || $params['expiry_month'] < 1 || $params['expiry_month'] > 12) {
            $errors[] = 'Invalid expiry month (1-12)';
        }
        
        if (empty($params['expiry_year'])) {
            $errors[] = 'Expiry year is required';
        } elseif (!is_numeric($params['expiry_year']) || $params['expiry_year'] < date('Y')) {
            $errors[] = 'Invalid expiry year';
        }
        
        if (empty($params['cvv'])) {
            $errors[] = 'CVV is required';
        } elseif (!preg_match('/^[0-9]{3,4}$/', $params['cvv'])) {
            $errors[] = 'Invalid CVV format';
        }
        
        // Optional fields validation
        if (!empty($params['order_id']) && !is_string($params['order_id'])) {
            $errors[] = 'Order ID must be a string';
        }
        
        if (!empty($params['description']) && !is_string($params['description'])) {
            $errors[] = 'Description must be a string';
        }
        
        return array(
            'valid' => empty($errors),
            'message' => empty($errors) ? 'Valid' : implode(', ', $errors)
        );
    }
    
    /**
     * Validate BTCPay payment request
     */
    public function validate_btcpay_payment($params) {
        $errors = array();
        
        // Required fields
        if (empty($params['amount'])) {
            $errors[] = 'Amount is required';
        } elseif (!is_numeric($params['amount']) || $params['amount'] <= 0) {
            $errors[] = 'Amount must be a positive number';
        }
        
        if (empty($params['currency'])) {
            $errors[] = 'Currency is required';
        } elseif (!in_array($params['currency'], array('BTC', 'LTC', 'ETH', 'BCH'))) {
            $errors[] = 'Invalid currency. Supported currencies: BTC, LTC, ETH, BCH';
        }
        
        // Optional fields validation
        if (!empty($params['order_id']) && !is_string($params['order_id'])) {
            $errors[] = 'Order ID must be a string';
        }
        
        if (!empty($params['description']) && !is_string($params['description'])) {
            $errors[] = 'Description must be a string';
        }
        
        if (!empty($params['notification_url']) && !filter_var($params['notification_url'], FILTER_VALIDATE_URL)) {
            $errors[] = 'Invalid notification URL format';
        }
        
        return array(
            'valid' => empty($errors),
            'message' => empty($errors) ? 'Valid' : implode(', ', $errors)
        );
    }
    
    /**
     * Validate WooPayments request
     */
    public function validate_woopayments($params) {
        $errors = array();
        
        // Required fields
        if (empty($params['amount'])) {
            $errors[] = 'Amount is required';
        } elseif (!is_numeric($params['amount']) || $params['amount'] <= 0) {
            $errors[] = 'Amount must be a positive number';
        }
        
        if (empty($params['currency'])) {
            $errors[] = 'Currency is required';
        } elseif (!in_array($params['currency'], array('usd', 'eur', 'gbp', 'cad', 'aud'))) {
            $errors[] = 'Invalid currency. Supported currencies: usd, eur, gbp, cad, aud';
        }
        
        if (empty($params['payment_method_id'])) {
            $errors[] = 'Payment method ID is required';
        } elseif (!is_string($params['payment_method_id'])) {
            $errors[] = 'Payment method ID must be a string';
        }
        
        // Optional fields validation
        if (!empty($params['order_id']) && !is_string($params['order_id'])) {
            $errors[] = 'Order ID must be a string';
        }
        
        if (!empty($params['description']) && !is_string($params['description'])) {
            $errors[] = 'Description must be a string';
        }
        
        if (!empty($params['customer_id']) && !is_string($params['customer_id'])) {
            $errors[] = 'Customer ID must be a string';
        }
        
        return array(
            'valid' => empty($errors),
            'message' => empty($errors) ? 'Valid' : implode(', ', $errors)
        );
    }
    
    /**
     * Validate card number using Luhn algorithm
     */
    private function validate_card_number($card_number) {
        // Remove spaces and dashes
        $card_number = preg_replace('/\s+/', '', $card_number);
        $card_number = str_replace('-', '', $card_number);
        
        // Check if it's numeric and has valid length
        if (!is_numeric($card_number) || strlen($card_number) < 13 || strlen($card_number) > 19) {
            return false;
        }
        
        // Luhn algorithm
        $sum = 0;
        $length = strlen($card_number);
        $parity = $length % 2;
        
        for ($i = 0; $i < $length; $i++) {
            $digit = intval($card_number[$i]);
            
            if ($i % 2 == $parity) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit -= 9;
                }
            }
            
            $sum += $digit;
        }
        
        return ($sum % 10) == 0;
    }
    
    /**
     * Validate email address
     */
    public function validate_email($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    /**
     * Validate URL
     */
    public function validate_url($url) {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }
    
    /**
     * Validate amount
     */
    public function validate_amount($amount, $min = 0.01) {
        return is_numeric($amount) && $amount >= $min;
    }
    
    /**
     * Validate currency
     */
    public function validate_currency($currency, $supported_currencies) {
        return in_array(strtoupper($currency), array_map('strtoupper', $supported_currencies));
    }
    
    /**
     * Sanitize input
     */
    public function sanitize_input($input) {
        if (is_array($input)) {
            return array_map(array($this, 'sanitize_input'), $input);
        }
        
        return sanitize_text_field($input);
    }
    
    /**
     * Validate webhook signature
     */
    public function validate_webhook_signature($payload, $signature, $secret) {
        // This is a placeholder for webhook signature validation
        // Implement proper signature validation based on the payment provider
        return true;
    }
    
    /**
     * Validate order ID format
     */
    public function validate_order_id($order_id) {
        // Allow alphanumeric characters, hyphens, and underscores
        return preg_match('/^[a-zA-Z0-9_-]+$/', $order_id);
    }
    
    /**
     * Validate description length
     */
    public function validate_description($description, $max_length = 255) {
        return is_string($description) && strlen($description) <= $max_length;
    }
    
    /**
     * Get validation errors as array
     */
    public function get_validation_errors($params, $rules) {
        $errors = array();
        
        foreach ($rules as $field => $rule) {
            $value = isset($params[$field]) ? $params[$field] : null;
            
            // Required validation
            if (isset($rule['required']) && $rule['required'] && empty($value)) {
                $errors[$field] = $field . ' is required';
                continue;
            }
            
            // Skip other validations if field is empty and not required
            if (empty($value)) {
                continue;
            }
            
            // Type validation
            if (isset($rule['type'])) {
                switch ($rule['type']) {
                    case 'string':
                        if (!is_string($value)) {
                            $errors[$field] = $field . ' must be a string';
                        }
                        break;
                    case 'numeric':
                        if (!is_numeric($value)) {
                            $errors[$field] = $field . ' must be numeric';
                        }
                        break;
                    case 'email':
                        if (!$this->validate_email($value)) {
                            $errors[$field] = $field . ' must be a valid email address';
                        }
                        break;
                    case 'url':
                        if (!$this->validate_url($value)) {
                            $errors[$field] = $field . ' must be a valid URL';
                        }
                        break;
                }
            }
            
            // Min/Max validation
            if (isset($rule['min']) && is_numeric($value) && $value < $rule['min']) {
                $errors[$field] = $field . ' must be at least ' . $rule['min'];
            }
            
            if (isset($rule['max']) && is_numeric($value) && $value > $rule['max']) {
                $errors[$field] = $field . ' must be at most ' . $rule['max'];
            }
            
            // Length validation
            if (isset($rule['min_length']) && strlen($value) < $rule['min_length']) {
                $errors[$field] = $field . ' must be at least ' . $rule['min_length'] . ' characters';
            }
            
            if (isset($rule['max_length']) && strlen($value) > $rule['max_length']) {
                $errors[$field] = $field . ' must be at most ' . $rule['max_length'] . ' characters';
            }
            
            // Pattern validation
            if (isset($rule['pattern']) && !preg_match($rule['pattern'], $value)) {
                $errors[$field] = $field . ' format is invalid';
            }
            
            // Enum validation
            if (isset($rule['enum']) && !in_array($value, $rule['enum'])) {
                $errors[$field] = $field . ' must be one of: ' . implode(', ', $rule['enum']);
            }
        }
        
        return $errors;
    }
} 