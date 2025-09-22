<?php
// Test script to verify GCS multiple file upload functionality

// Include the config file to load environment variables
require_once 'config.php';
require_once 'api/GcsUploader.php';

echo "<h1>GCS Multiple File Upload Test</h1>";

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

// Create test files to upload
echo "<h2>Creating Test Files</h2>";

$files = [];
$testFiles = [];

// Create an image file
$imageFileName = 'test_image_' . time() . '.png';
$imageFilePath = sys_get_temp_dir() . '/' . $imageFileName;

// Create a simple PNG image
$image = imagecreate(100, 100);
$background = imagecolorallocate($image, 255, 255, 255); // White background
$textColor = imagecolorallocate($image, 0, 0, 0); // Black text
imagestring($image, 5, 10, 30, 'Test Image', $textColor);
imagepng($image, $imageFilePath);
imagedestroy($image);

if (file_exists($imageFilePath)) {
    echo "<p style='color: green;'>✓ Test image file created: $imageFilePath</p>";
    $testFiles[] = $imageFilePath;
    $files[] = [
        'path' => $imageFilePath,
        'name' => $imageFileName
    ];
} else {
    echo "<p style='color: red;'>✗ Failed to create test image file</p>";
    exit(1);
}

// Create a video file
$videoFileName = 'test_video_' . time() . '.mp4';
$videoFilePath = sys_get_temp_dir() . '/' . $videoFileName;

// Create a simple MP4 file with some content
$content = "This is a test video file for GCS upload functionality.
Generated at: " . date('Y-m-d H:i:s');
file_put_contents($videoFilePath, $content);

if (file_exists($videoFilePath)) {
    echo "<p style='color: green;'>✓ Test video file created: $videoFilePath</p>";
    $testFiles[] = $videoFilePath;
    $files[] = [
        'path' => $videoFilePath,
        'name' => $videoFileName
    ];
} else {
    echo "<p style='color: red;'>✗ Failed to create test video file</p>";
    exit(1);
}

// Create an audio file
$audioFileName = 'test_audio_' . time() . '.mp3';
$audioFilePath = sys_get_temp_dir() . '/' . $audioFileName;

// Create a simple MP3 file with some content
$content = "This is a test audio file for GCS upload functionality.
Generated at: " . date('Y-m-d H:i:s');
file_put_contents($audioFilePath, $content);

if (file_exists($audioFilePath)) {
    echo "<p style='color: green;'>✓ Test audio file created: $audioFilePath</p>";
    $testFiles[] = $audioFilePath;
    $files[] = [
        'path' => $audioFilePath,
        'name' => $audioFileName
    ];
} else {
    echo "<p style='color: red;'>✗ Failed to create test audio file</p>";
    exit(1);
}

// Test multiple file upload
echo "<h2>Testing Multiple File Upload</h2>";
try {
    $result = $uploader->uploadMultipleFiles($files);
    
    if ($result['success']) {
        echo "<p style='color: green;'>✓ Multiple files uploaded successfully</p>";
        echo "<h3>Uploaded Files:</h3>";
        echo "<ul>";
        foreach ($result['files'] as $file) {
            echo "<li>";
            echo "<strong>Name:</strong> " . htmlspecialchars($file['file_name']) . "<br>";
            echo "<strong>URL:</strong> <a href='" . htmlspecialchars($file['url']) . "'>" . htmlspecialchars($file['url']) . "</a>";
            
            // Verify the file is accessible
            $ch = curl_init($file['url']);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($httpCode == 200) {
                echo " <span style='color: green;'>(Accessible)</span>";
            } else {
                echo " <span style='color: red;'>(Not accessible - HTTP $httpCode)</span>";
            }
            echo "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p style='color: red;'>✗ Multiple file upload failed: " . htmlspecialchars($result['error']) . "</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Exception during multiple file upload: " . htmlspecialchars($e->getMessage()) . "</p>";
}

// Clean up test files
foreach ($testFiles as $filePath) {
    if (file_exists($filePath)) {
        unlink($filePath);
    }
}

echo "<h2>Test Completed</h2>";
echo "<p>All test files have been cleaned up from local system.</p>";
?>