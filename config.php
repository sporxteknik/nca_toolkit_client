<?php
// Configuration file for NCA Toolkit PHP Client

// Load environment variables from .env file if it exists
if (file_exists(__DIR__ . '/.env')) {
    $env = parse_ini_file(__DIR__ . '/.env');
    foreach ($env as $key => $value) {
        putenv("$key=$value");
    }
}

// Base URL for the NCA Toolkit API
// Use environment variable if set, otherwise use default
define('NCA_API_BASE_URL', getenv('NCA_API_BASE_URL') ?: 'https://no-code-architects-toolkit-18628757896.europe-west1.run.app');

// API key - use environment variable if set, otherwise use default
define('NCA_API_KEY', getenv('NCA_API_KEY') ?: 'YOUR_API_KEY_HERE');

// API timeout in seconds
define('NCA_API_TIMEOUT', getenv('NCA_API_TIMEOUT') ?: 300);

// GCP Storage configuration
define('GOOGLE_APPLICATION_CREDENTIALS', getenv('GOOGLE_APPLICATION_CREDENTIALS') ?: '');
define('GCS_BUCKET_NAME', getenv('GCS_BUCKET_NAME') ?: 'maksimum-nca');
?>