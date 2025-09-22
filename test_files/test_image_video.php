<?php
require_once 'config.php';
require_once 'api/NcaApiClient.php';
require_once 'api/endpoints/Image.php';

// Initialize API client
$apiClient = new NcaApiClient();

// Initialize image endpoint
$image = new Image($apiClient);

// Test image URL
$imageUrl = 'https://ichef.bbci.co.uk/ace/standard/3840/cpsprodpb/cea1/live/1de105b0-f5a5-11ef-bcea-7b70a14a5556.jpg';

echo "Testing Image to Video with URL:\n";
echo "- $imageUrl\n";

// Try the convertToVideo function
$result = $image->convertToVideo($imageUrl);

echo "\nAPI Response:\n";
echo json_encode($result, JSON_PRETTY_PRINT);
?>