#!/bin/bash
# setup_smtp.sh
# Script to set up SMTP configuration on the server

echo "=========================================="
echo "SMTP Configuration Setup"
echo "=========================================="
echo ""

# Check if smtp_config.php already exists
if [ -f "php/smtp_config.php" ]; then
    echo "✓ smtp_config.php already exists"
    echo ""
    read -p "Do you want to overwrite it? (y/n): " overwrite
    if [ "$overwrite" != "y" ]; then
        echo "Setup cancelled."
        exit 0
    fi
fi

# Copy example file
if [ ! -f "php/smtp_config.example.php" ]; then
    echo "✗ Error: smtp_config.example.php not found!"
    exit 1
fi

cp php/smtp_config.example.php php/smtp_config.php
echo "✓ Created smtp_config.php from example"
echo ""

# Prompt for configuration
echo "Please enter your SMTP configuration:"
echo ""

read -p "SMTP Host (e.g., smtp.gmail.com): " smtp_host
read -p "SMTP Port (e.g., 587): " smtp_port
read -p "SMTP Encryption (tls/ssl): " smtp_encryption
read -p "SMTP Username (your email): " smtp_username
read -p "SMTP Password (app password): " smtp_password
read -p "From Email: " from_email
read -p "From Name: " from_name
read -p "Admin Email: " admin_email

# Update the config file
sed -i "s/'smtp_host' => '[^']*'/'smtp_host' => '$smtp_host'/g" php/smtp_config.php
sed -i "s/'smtp_port' => [0-9]*/'smtp_port' => $smtp_port/g" php/smtp_config.php
sed -i "s/'smtp_encryption' => '[^']*'/'smtp_encryption' => '$smtp_encryption'/g" php/smtp_config.php
sed -i "s/'smtp_username' => '[^']*'/'smtp_username' => '$smtp_username'/g" php/smtp_config.php
sed -i "s/'smtp_password' => '[^']*'/'smtp_password' => '$smtp_password'/g" php/smtp_config.php
sed -i "s/'from_email' => '[^']*'/'from_email' => '$from_email'/g" php/smtp_config.php
sed -i "s/'from_name' => '[^']*'/'from_name' => '$from_name'/g" php/smtp_config.php
sed -i "s/'admin_email' => '[^']*'/'admin_email' => '$admin_email'/g" php/smtp_config.php

echo ""
echo "✓ Configuration updated successfully!"
echo ""
echo "Next steps:"
echo "1. Make sure composer dependencies are installed: cd php && composer install"
echo "2. Test the email configuration: visit php/test_email.php in your browser"
echo "3. Keep smtp_config.php secure and never commit it to git"
echo ""
