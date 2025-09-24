<?php
// Configuration file for NCA Toolkit PHP Client

// Base URL for the NCA Toolkit API
// Use environment variable if set, otherwise use default
define('NCA_API_BASE_URL', getenv('NCA_API_BASE_URL') ?: 'https://no-code-architects-toolkit-18628757896.europe-west1.run.app');

// API key - use environment variable if set, otherwise use default
define('NCA_API_KEY', getenv('NCA_API_KEY') ?: 'YOUR_API_KEY_HERE');

// API timeout in seconds
define('NCA_API_TIMEOUT', getenv('NCA_API_TIMEOUT') ?: 600); // 10 minutes for video processing
?>