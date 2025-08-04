#!/bin/bash

# WordPress Product Generator Script
# This script runs the PHP product generator with proper WordPress context

set -e  # Exit on any error

echo "🎵 WordPress Product Generator"
echo "=============================="
echo ""

# Check if we're in a WordPress directory
if [ ! -f "wp-config.php" ]; then
    echo "❌ Error: wp-config.php not found. Please run this script from your WordPress root directory."
    exit 1
fi

# Check if wp-cli is available
if ! command -v wp &> /dev/null; then
    echo "❌ Error: wp-cli is not installed or not in PATH."
    echo "Please install wp-cli first: https://wp-cli.org/#installing"
    exit 1
fi

# Check if PHP is available
if ! command -v php &> /dev/null; then
    echo "❌ Error: PHP is not installed or not in PATH."
    exit 1
fi

# Check if the PHP script exists
if [ ! -f "generate_products.php" ]; then
    echo "❌ Error: generate_products.php not found in current directory."
    exit 1
fi

# Make the PHP script executable
chmod +x generate_products.php

echo "✅ WordPress environment detected"
echo "✅ wp-cli is available"
echo "✅ PHP is available"
echo "✅ Product generator script found"
echo ""

# Ask for confirmation
read -p "🚨 This will create 24 products per category. Are you sure you want to continue? (y/N): " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo "❌ Operation cancelled."
    exit 0
fi

echo ""
echo "🚀 Starting product generation..."
echo ""

# Run the PHP script
php generate_products.php

echo ""
echo "🎉 Product generation completed!"
echo ""
echo "📋 Next steps:"
echo "   1. Check your WordPress admin panel to see the new products"
echo "   2. Add product images if needed"
echo "   3. Review and adjust product details as necessary"
echo "" 