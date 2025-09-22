#!/bin/bash
# Test runner script for NCA Toolkit PHP Client

# Check if a test file was provided
if [ $# -eq 0 ]; then
    echo "Usage: $0 <test_file.php>"
    echo "Example: $0 test_api.php"
    echo "Available test files:"
    ls -1 test_*.php
    exit 1
fi

# Run the specified test file
php "$1"