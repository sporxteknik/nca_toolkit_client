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

echo "Testing Media Transcribe with SRT format option...\n";
echo "URL: $mediaUrl\n\n";

// Set a short timeout to see what the API returns immediately
$apiClient->setTimeout(10); // 10 seconds

// Try the transcribe function
$result = $media->transcribe($mediaUrl);

if ($result['success']) {
    echo "Transcription successful!\n";
    
    // Convert text to SRT
    $srtText = $media->textToSrt($result['data']['response']['text'], 120); // 2 minutes duration
    
    echo "SRT Format:\n";
    echo $srtText;
    
    // Save SRT to file
    file_put_contents('transcription.srt', $srtText);
    echo "SRT file saved as transcription.srt\n";
} else {
    echo "Transcription failed:\n";
    print_r($result);
}
?>