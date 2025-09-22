<?php
require_once 'config.php';
require_once 'api/NcaApiClient.php';

// Initialize API client
$apiClient = new NcaApiClient();

// Test the API connection
$result = $apiClient->testConnection();

echo "Test API Result:\n";
print_r($result);
?>