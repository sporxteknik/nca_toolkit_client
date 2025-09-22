<?php
require_once 'config.php';
require_once 'api/NcaApiClient.php';
require_once 'api/endpoints/Toolkit.php';

// Initialize API client
$apiClient = new NcaApiClient();

// Initialize toolkit endpoint
$toolkit = new Toolkit($apiClient);

echo "Testing Authentication...\n";

// Try the authenticate function
$result = $toolkit->authenticate();

echo "Authentication Response:\n";
echo json_encode($result, JSON_PRETTY_PRINT);

echo "\n\nTesting API Test Endpoint...\n";

// Try the test function
$result = $toolkit->test();

echo "Test Response:\n";
echo json_encode($result, JSON_PRETTY_PRINT);
?>