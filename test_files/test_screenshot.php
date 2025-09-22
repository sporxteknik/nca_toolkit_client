<?php
require_once 'config.php';
require_once 'api/NcaApiClient.php';
require_once 'api/endpoints/Image.php';

// Initialize API client
$apiClient = new NcaApiClient();

// Initialize image endpoint
$image = new Image($apiClient);

// Test webpage URL
$url = 'https://www.bbc.com';

echo "Testing Webpage Screenshot with URL:
";
echo "- $url
";

// Try the screenshotWebpage function
$result = $image->screenshotWebpage($url);

echo "
API Response:
";
echo json_encode($result, JSON_PRETTY_PRINT);
?>