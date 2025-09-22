<?php
require_once 'config.php';
require_once 'api/NcaApiClient.php';
require_once 'api/endpoints/Media.php';

// Initialize API client
$apiClient = new NcaApiClient();

// Initialize media endpoint
$media = new Media($apiClient);

// Test URL
$mediaUrl = 'https://storage.googleapis.com/maksimum-nca/Dolara_Dikkat.mp4';

echo "Testing Media Transcribe...
";
echo "URL: $mediaUrl

";

// Try the transcribe function
$result = $media->transcribe($mediaUrl);

echo "API Response:
";
print_r($result);
?>
