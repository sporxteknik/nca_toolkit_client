<?php
// Test script to verify GCS upload functionality

// Include the config file to load environment variables
require_once 'config.php';
require_once 'api/GcsUploader.php';

echo "<h1>GCS Upload Test</h1>";

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

// Create a test file to upload
echo "<h2>Creating Test File</h2>";
$testFileName = 'test_upload_' . time() . '.txt';
$testFilePath = sys_get_temp_dir() . '/' . $testFileName;
$testContent = "This is a test file for GCS upload functionality.
Generated at: " . date('Y-m-d H:i:s');

if (file_put_contents($testFilePath, $testContent)) {
    echo "<p style='color: green;'>✓ Test file created: $testFilePath</p>";
} else {
    echo "<p style='color: red;'>✗ Failed to create test file</p>";
    exit(1);
}

// Test single file upload
echo "<h2>Testing Single File Upload</h2>";
try {
    $result = $uploader->uploadFile($testFilePath, $testFileName);
    
    if ($result['success']) {
        echo "<p style='color: green;'>✓ File uploaded successfully</p>";
        echo "<p>File URL: <a href='" . htmlspecialchars($result['url']) . "'>" . htmlspecialchars($result['url']) . "</a></p>";
        echo "<p>File Name: " . htmlspecialchars($result['file_name']) . "</p>";
    } else {
        echo "<p style='color: red;'>✗ File upload failed: " . htmlspecialchars($result['error']) . "</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Exception during file upload: " . htmlspecialchars($e->getMessage()) . "</p>";
}

// Clean up test file
unlink($testFilePath);

echo "<h2>Test Completed</h2>";
echo "<p>Test file has been cleaned up from local system.</p>";
?>
