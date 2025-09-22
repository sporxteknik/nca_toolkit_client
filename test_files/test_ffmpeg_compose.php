<?php
require_once 'config.php';
require_once 'api/NcaApiClient.php';
require_once 'api/endpoints/Ffmpeg.php';

// Initialize API client
$apiClient = new NcaApiClient();

// Initialize ffmpeg endpoint
$ffmpeg = new Ffmpeg($apiClient);

// Test input files
$inputs = [
    'https://samplelib.com/lib/ffmpeg/sample_1280x720.mp4',
    'https://samplelib.com/lib/ffmpeg/sample_960x540.mp4'
];

$command = 'ffmpeg -i input.mp4 output.mp4';

echo "Testing FFmpeg Compose with inputs:\n";
foreach ($inputs as $input) {
    echo "- $input\n";
}
echo "Command: $command\n";

// Try the compose function
$result = $ffmpeg->compose($inputs, $command);

echo "\nAPI Response:\n";
echo json_encode($result, JSON_PRETTY_PRINT);
?>