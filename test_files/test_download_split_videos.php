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

// Define segments to split
$segments = [
    ['start' => 0, 'end' => 10],
    ['start' => 20, 'end' => 30]
];

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
    echo "Job ID: " . $result['data']['job_id'] . "\n";
    
    // Check if we have file URLs to download
    if (isset($result['data']['response']) && is_array($result['data']['response'])) {
        echo "File URLs:\n";
        $fileUrls = [];
        foreach ($result['data']['response'] as $index => $fileInfo) {
            if (isset($fileInfo['file_url'])) {
                echo "  File " . ($index + 1) . ": " . $fileInfo['file_url'] . "\n";
                $fileUrls[] = $fileInfo;
            }
        }
        
        // Try to download the files
        echo "\nDownloading split videos...\n";
        $downloadResult = $video->downloadSplitVideos($fileUrls, $result['data']['job_id']);
        
        if ($downloadResult['success']) {
            echo "Videos downloaded successfully!\n";
            echo "Job ID: " . $downloadResult['job_id'] . "\n";
            echo "Total size: " . round($downloadResult['total_size'] / 1024 / 1024, 2) . " MB\n";
            echo "Temporary directory: " . $downloadResult['temp_dir'] . "\n";
            echo "Files:\n";
            foreach ($downloadResult['files'] as $file) {
                echo "  " . $file['filename'] . " (" . round($file['file_size'] / 1024 / 1024, 2) . " MB)\n";
            }
            
            // Note: We can't clean up the downloaded files here because deleteDirectory is private
            // In a real application, you would want to clean up these files after the user downloads them
            echo "\nVideos are available in: " . $downloadResult['temp_dir'] . "\n";
        } else {
            echo "Failed to download videos:\n";
            echo "Error: " . $downloadResult['error'] . "\n";
        }
    }
} else {
    echo "Failed to split video:\n";
    print_r($result);
}
?>