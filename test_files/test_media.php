<?php
require_once 'config.php';
require_once 'api/NcaApiClient.php';
require_once 'api/endpoints/Media.php';

// Initialize API client
$apiClient = new NcaApiClient();

// Initialize media endpoint
$media = new Media($apiClient);

// Test the media download endpoint
$result = $media->download('https://www.youtube.com/watch?v=KgXd5UROlFg');

echo "Media Download Result:\n";
print_r($result);
?>