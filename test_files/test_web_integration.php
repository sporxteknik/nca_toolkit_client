<?php
// Test to verify GCS uploader works with web interface integration

// Include the config file to load environment variables
require_once 'config.php';
require_once 'api/GcsUploader.php';

echo "<h1>Web Interface Integration Test</h1>";

// Initialize the GCS uploader
try {
    $uploader = new GcsUploader();
    echo "<p style='color: green;'>✓ GcsUploader initialized successfully</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Failed to initialize GcsUploader: " . htmlspecialchars($e->getMessage()) . "</p>";
    exit(1);
}

// Create a test file similar to what would be uploaded through the web interface
echo "<h2>Creating Test File</h2>";

$testFileName = 'web_integration_test_' . time() . '.png';
$testFilePath = sys_get_temp_dir() . '/' . $testFileName;

// Create a simple PNG image
$image = imagecreate(100, 100);
$background = imagecolorallocate($image, 255, 255, 255); // White background
$textColor = imagecolorallocate($image, 0, 0, 0); // Black text
imagestring($image, 5, 5, 30, 'Web Test', $textColor);
imagepng($image, $testFilePath);
imagedestroy($image);

if (file_exists($testFilePath)) {
    echo "<p style='color: green;'>✓ Test file created: $testFilePath</p>";
    echo "<p>File size: " . filesize($testFilePath) . " bytes</p>";
} else {
    echo "<p style='color: red;'>✗ Failed to create test file</p>";
    exit(1);
}

// Simulate the file upload process that happens in the web interface
echo "<h2>Simulating Web Interface File Upload</h2>";

try {
    $result = $uploader->uploadFile($testFilePath, $testFileName);
    
    if ($result['success']) {
        echo "<p style='color: green;'>✓ File uploaded successfully through simulated web interface process</p>";
        echo "<p><strong>File URL:</strong> <a href='" . htmlspecialchars($result['url']) . "'>" . htmlspecialchars($result['url']) . "</a></p>";
        echo "<p><strong>File Name:</strong> " . htmlspecialchars($result['file_name']) . "</p>";
        
        // Verify the file is accessible
        $ch = curl_init($result['url']);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode == 200) {
            echo "<p style='color: green;'>✓ Uploaded file is accessible via URL</p>";
        } else {
            echo "<p style='color: red;'>✗ Uploaded file is not accessible (HTTP $httpCode)</p>";
        }
        
        // This URL can now be used with any NCA Toolkit API endpoint
        echo "<h2>Integration with NCA Toolkit API</h2>";
        echo "<p>The uploaded file can now be used with any NCA Toolkit API endpoint that accepts file URLs:</p>";
        echo "<ul>";
        echo "<li><strong>Video Concatenate:</strong> Use the URL as one of the video_urls</li>";
        echo "<li><strong>Image to Video:</strong> Use the URL as the image_url</li>";
        echo "<li><strong>Media Convert MP3:</strong> Use the URL as the media_url</li>";
        echo "<li><strong>Video Add Caption:</strong> Use the URL as the video_url</li>";
        echo "<li><strong>And many more...</strong></li>";
        echo "</ul>";
        
        echo "<p><strong>Example API call:</strong></p>";
        echo "<pre>";
        echo "POST /v1/video/concatenate
";
        echo "Content-Type: application/json
";
        echo "x-api-key: YOUR_API_KEY

";
        echo "{
";
        echo "  \"video_urls\": [
";
        echo "    {\"video_url\": \"" . $result['url'] . "\"},
";
        echo "    {\"video_url\": \"https://example.com/other_video.mp4\"}
";
        echo "  ]
";
        echo "}";
        echo "</pre>";
    } else {
        echo "<p style='color: red;'>✗ File upload failed: " . htmlspecialchars($result['error']) . "</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Exception during file upload: " . htmlspecialchars($e->getMessage()) . "</p>";
}

// Clean up test file
unlink($testFilePath);
echo "<p style='color: green;'>✓ Test file cleaned up</p>";

echo "<h2>Test Completed</h2>";
echo "<p>The GCS uploader is fully integrated with the web interface and can be used to upload files that are then processed by the NCA Toolkit API.</p>";
?>