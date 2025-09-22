<?php
require_once 'config.php';
require_once 'api/NcaApiClient.php';
require_once 'api/endpoints/Toolkit.php';

// Initialize API client
$apiClient = new NcaApiClient();

// Initialize toolkit endpoint
$toolkit = new Toolkit($apiClient);

// Test Job ID - using a sample job ID
$jobId = 'c70bd18f-abd4-40f6-8a54-894c9326bfe8'; // This is a sample job ID from our previous test

echo "Testing Job Status functionality...\n";
echo "Job ID: $jobId\n\n";

// Try the get job status function
$result = $toolkit->getJobStatus($jobId);

if ($result['success']) {
    echo "Job status retrieved successfully!\n";
    print_r($result);
} else {
    echo "Failed to get job status:\n";
    print_r($result);
}

echo "\n" . str_repeat("-", 50) . "\n\n";

// Let's also try to manually construct the URL to see if that works
echo "Testing manual URL construction...\n";
$url = NCA_API_BASE_URL . '/v1/toolkit/job/status?job_id=' . urlencode($jobId);
echo "URL: $url\n\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'x-api-key: ' . NCA_API_KEY
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
    echo "cURL Error: $error\n";
} else {
    echo "HTTP Code: $httpCode\n";
    echo "Response: $response\n";
    $result = json_decode($response, true);
    if ($httpCode >= 200 && $httpCode < 300) {
        echo "Success!\n";
        print_r($result);
    } else {
        echo "Failed!\n";
        print_r($result);
    }
}
?>
