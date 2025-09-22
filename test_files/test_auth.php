<?php
require_once 'config.php';
require_once 'api/NcaApiClient.php';

// Initialize API client
$apiClient = new NcaApiClient();

echo "API Key from config: " . NCA_API_KEY . "\n";

// Test the authentication directly
$result = $apiClient->authenticate();

echo "Authenticate Result:\n";
print_r($result);
?>