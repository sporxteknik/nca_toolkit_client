<?php
// Test script to verify GCP credentials setup

// Include the config file to load environment variables
require_once 'config.php';

echo "<h1>GCP Credentials Test</h1>";

// Check if environment variables are set
echo "<h2>Environment Variables</h2>";
echo "<p>GOOGLE_APPLICATION_CREDENTIALS: " . getenv('GOOGLE_APPLICATION_CREDENTIALS') . "</p>";
echo "<p>GCS_BUCKET_NAME: " . getenv('GCS_BUCKET_NAME') . "</p>";

// Check if the key file exists
$keyFilePath = getenv('GOOGLE_APPLICATION_CREDENTIALS');
echo "<h2>Key File Check</h2>";
if ($keyFilePath && file_exists($keyFilePath)) {
    echo "<p style='color: green;'>✓ Key file exists at: $keyFilePath</p>";
    
    // Try to read the key file
    $keyContent = file_get_contents($keyFilePath);
    if ($keyContent) {
        echo "<p style='color: green;'>✓ Key file is readable</p>";
        
        // Try to decode JSON
        $keyData = json_decode($keyContent, true);
        if ($keyData && isset($keyData['project_id'])) {
            echo "<p style='color: green;'>✓ Key file is valid JSON</p>";
            echo "<p>Project ID: " . htmlspecialchars($keyData['project_id']) . "</p>";
        } else {
            echo "<p style='color: red;'>✗ Key file is not valid JSON</p>";
        }
    } else {
        echo "<p style='color: red;'>✗ Key file is not readable</p>";
    }
} else {
    echo "<p style='color: red;'>✗ Key file does not exist at: $keyFilePath</p>";
}

// Try to initialize Google Cloud Storage client
echo "<h2>Google Cloud Storage Client Test</h2>";
try {
    // Make sure the autoloader is included
    if (file_exists('vendor/autoload.php')) {
        require_once 'vendor/autoload.php';
        
        // Initialize the Storage client
        $storage = new Google\Cloud\Storage\StorageClient();
        echo "<p style='color: green;'>✓ Google Cloud Storage client initialized successfully</p>";
    } else {
        echo "<p style='color: red;'>✗ Vendor autoload file not found</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Failed to initialize Google Cloud Storage client: " . htmlspecialchars($e->getMessage()) . "</p>";
}

echo "<h2>PHP Info</h2>";
echo "<p>PHP Version: " . phpversion() . "</p>";
?>