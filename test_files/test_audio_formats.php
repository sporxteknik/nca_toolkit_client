<?php
require_once 'config.php';
require_once 'api/NcaApiClient.php';
require_once 'api/endpoints/Audio.php';

// Initialize API client
$apiClient = new NcaApiClient();

// Initialize audio endpoint
$audio = new Audio($apiClient);

// Test different formats for audio URLs
$testFormats = [
    // Format 1: Simple array of strings (current implementation)
    [
        'name' => 'Simple strings',
        'data' => [
            'https://download.samplelib.com/mp3/sample-6s.mp3',
            'https://download.samplelib.com/mp3/sample-12s.mp3'
        ]
    ],
    // Format 2: Array of objects with url property
    [
        'name' => 'Objects with url property',
        'data' => [
            ['url' => 'https://download.samplelib.com/mp3/sample-6s.mp3'],
            ['url' => 'https://download.samplelib.com/mp3/sample-12s.mp3']
        ]
    ],
    // Format 3: Array of objects with file_url property
    [
        'name' => 'Objects with file_url property',
        'data' => [
            ['file_url' => 'https://download.samplelib.com/mp3/sample-6s.mp3'],
            ['file_url' => 'https://download.samplelib.com/mp3/sample-12s.mp3']
        ]
    ]
];

echo "Testing Audio Concatenate with different formats:\n\n";

foreach ($testFormats as $format) {
    echo "Testing format: " . $format['name'] . "\n";
    
    // Create a new client and audio endpoint for each test
    $testClient = new NcaApiClient();
    $testAudio = new Audio($testClient);
    
    $result = $testAudio->concatenate($format['data']);
    
    echo "Response:\n";
    echo json_encode($result, JSON_PRETTY_PRINT);
    echo "\n\n" . str_repeat('-', 50) . "\n\n";
}
?>