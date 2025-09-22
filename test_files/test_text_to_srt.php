<?php
require_once 'config.php';
require_once 'api/NcaApiClient.php';
require_once 'api/endpoints/Media.php';

// Initialize API client
$apiClient = new NcaApiClient();

// Initialize media endpoint
$media = new Media($apiClient);

// Test text
$text = "This is a sample transcription text. It contains multiple sentences. This is the third sentence.";

echo "Testing text to SRT conversion...
";
echo "Text: $text

";

// Convert text to SRT
$srt = $media->textToSrt($text, 120); // 2 minutes duration

echo "SRT Format:
";
echo $srt;
?>
