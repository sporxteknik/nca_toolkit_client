<?php
// Test script to verify GCS upload functionality with a video file

// Include the config file to load environment variables
require_once 'config.php';
require_once 'api/GcsUploader.php';

echo "<h1>GCS Video Upload Test</h1>";

// Check if GCS configuration is set
echo "<h2>Configuration Check</h2>";
echo "<p>GCS_BUCKET_NAME: " . getenv('GCS_BUCKET_NAME') . "</p>";
echo "<p>GOOGLE_APPLICATION_CREDENTIALS: " . getenv('GOOGLE_APPLICATION_CREDENTIALS') . "</p>";

// Check if we have valid credentials
$keyFilePath = getenv('GOOGLE_APPLICATION_CREDENTIALS');
if (!$keyFilePath || !file_exists($keyFilePath)) {
    echo "<p style='color: red;'>✗ Google Cloud credentials not found. Please set GOOGLE_APPLICATION_CREDENTIALS environment variable.</p>";
    exit(1);
}

echo "<p style='color: green;'>✓ Google Cloud credentials found</p>";

// Initialize the GCS uploader
try {
    $uploader = new GcsUploader();
    echo "<p style='color: green;'>✓ GcsUploader initialized successfully</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Failed to initialize GcsUploader: " . htmlspecialchars($e->getMessage()) . "</p>";
    exit(1);
}

// Create a test video file to upload (MP4 format)
echo "<h2>Creating Test Video File</h2>";
$testFileName = 'test_upload_' . time() . '.mp4';
$testFilePath = sys_get_temp_dir() . '/' . $testFileName;

// Create a simple MP4 file with some content
$content = "This is a test video file for GCS upload functionality.
Generated at: " . date('Y-m-d H:i:s');
file_put_contents($testFilePath, $content);

if (file_exists($testFilePath)) {
    echo "<p style='color: green;'>✓ Test video file created: $testFilePath</p>";
    echo "<p>File size: " . filesize($testFilePath) . " bytes</p>";
} else {
    echo "<p style='color: red;'>✗ Failed to create test video file</p>";
    exit(1);
}

// Test single file upload
echo "<h2>Testing Single File Upload</h2>";
try {
    $result = $uploader->uploadFile($testFilePath, $testFileName);
    
    if ($result['success']) {
        echo "<p style='color: green;'>✓ Video file uploaded successfully</p>";
        echo "<p>File URL: <a href='" . htmlspecialchars($result['url']) . "'>" . htmlspecialchars($result['url']) . "</a></p>";
        echo "<p>File Name: " . htmlspecialchars($result['file_name']) . "</p>";
        
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
    } else {
        echo "<p style='color: red;'>✗ Video file upload failed: " . htmlspecialchars($result['error']) . "</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Exception during file upload: " . htmlspecialchars($e->getMessage()) . "</p>";
}

// Clean up test file
unlink($testFilePath);

echo "<h2>Test Completed</h2>";
echo "<p>Test file has been cleaned up from local system.</p>";
?>