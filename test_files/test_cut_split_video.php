<?php
require_once 'config.php';
require_once 'api/NcaApiClient.php';
require_once 'api/endpoints/Video.php';

// Initialize API client
$apiClient = new NcaApiClient();

// Initialize video endpoint
$video = new Video($apiClient);

// Test URL - using a sample video URL
$videoUrl = 'https://storage.googleapis.com/maksimum-nca/Dolara_Dikkat.mp4';

// Define segments to cut
$segments = [
    ['start' => 0, 'end' => 10],
    ['start' => 20, 'end' => 30]
];

echo "Testing Cut Video functionality...\n";
echo "Video URL: $videoUrl\n";
echo "Segments to cut:\n";
foreach ($segments as $segment) {
    echo "  {$segment['start']} - {$segment['end']}\n";
}
echo "\n";

// Try the cut video function
$result = $video->cut($videoUrl, $segments);

if ($result['success']) {
    echo "Video cut successfully!\n";
    print_r($result);
} else {
    echo "Failed to cut video:\n";
    print_r($result);
}

echo "\n" . str_repeat("-", 50) . "\n\n";

// Test Split Video functionality
echo "Testing Split Video functionality...\n";
echo "Video URL: $videoUrl\n";
echo "Segments to split:\n";
foreach ($segments as $segment) {
    echo "  {$segment['start']} - {$segment['end']}\n";
}
echo "\n";

// Try the split video function
$result = $video->split($videoUrl, $segments);

if ($result['success']) {
    echo "Video split successfully!\n";
    print_r($result);
} else {
    echo "Failed to split video:\n";
    print_r($result);
}
?>