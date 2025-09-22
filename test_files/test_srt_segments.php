<?php
require_once 'config.php';
require_once 'api/NcaApiClient.php';
require_once 'api/endpoints/Media.php';

// Initialize API client
$apiClient = new NcaApiClient();

// Initialize media endpoint
$media = new Media($apiClient);

// Test URL
$mediaUrl = 'https://storage.googleapis.com/maksimum-nca/Camping.mp4';

echo "Testing Media Transcribe to SRT with segments...\n";
echo "URL: $mediaUrl\n\n";

// Set a longer timeout for transcription
$apiClient->setTimeout(120); // 2 minutes

// Try the SRT transcription function
$result = $media->transcribeToSrt($mediaUrl);

if ($result['success']) {
    echo "SRT Transcription successful!\n";
    
    // Check if we have SRT content in the response
    if (isset($result['data']['response']['srt'])) {
        echo "SRT Content:\n";
        echo $result['data']['response']['srt'];
        
        // Save SRT to file
        file_put_contents('camping_transcription.srt', $result['data']['response']['srt']);
        echo "\nSRT file saved as camping_transcription.srt\n";
    } else {
        echo "No SRT content in response:\n";
        print_r($result);
    }
} else {
    echo "SRT Transcription failed:\n";
    print_r($result);
}
?>