# NCA Toolkit PHP Client - Usage Examples

This document provides usage examples for all endpoints implemented in the NCA Toolkit PHP Client.

## Table of Contents
1. [Audio Endpoints](#audio-endpoints)
2. [Code Endpoints](#code-endpoints)
3. [FFmpeg Endpoints](#ffmpeg-endpoints)
4. [Image Endpoints](#image-endpoints)
5. [Media Endpoints](#media-endpoints)
6. [S3 Endpoints](#s3-endpoints)
7. [Toolkit Endpoints](#toolkit-endpoints)
8. [Video Endpoints](#video-endpoints)
9. [YouTube Downloader](#youtube-downloader)

## Audio Endpoints

### Concatenate Audio Files

```php
<?php
require_once 'config.php';
require_once 'api/NcaApiClient.php';
require_once 'api/endpoints/Audio.php';

// Initialize API client
$apiClient = new NcaApiClient();

// Initialize audio endpoint
$audio = new Audio($apiClient);

// Concatenate multiple audio files
$audioUrls = [
    'https://example.com/audio1.mp3',
    'https://example.com/audio2.mp3'
];

$result = $audio->concatenate($audioUrls);

if ($result['success']) {
    echo "Audio concatenation successful!";
    // Handle the result (job ID, download URL, etc.)
} else {
    echo "Failed to concatenate audio: " . $result['error'];
}
?>
```

## Code Endpoints

### Execute Python Code

```php
<?php
require_once 'config.php';
require_once 'api/NcaApiClient.php';
require_once 'api/endpoints/Code.php';

// Initialize API client
$apiClient = new NcaApiClient();

// Initialize code endpoint
$code = new Code($apiClient);

// Execute Python code
$pythonCode = "
print('Hello, World!')
x = 5
y = 10
print(f'The sum of {x} and {y} is {x + y}')
";

$result = $code->executePython($pythonCode);

if ($result['success']) {
    echo "Python code executed successfully!";
    // Handle the result
} else {
    echo "Failed to execute Python code: " . $result['error'];
}
?>
```

## FFmpeg Endpoints

### Compose Media

```php
<?php
require_once 'config.php';
require_once 'api/NcaApiClient.php';
require_once 'api/endpoints/Ffmpeg.php';

// Initialize API client
$apiClient = new NcaApiClient();

// Initialize FFmpeg endpoint
$ffmpeg = new Ffmpeg($apiClient);

// Define inputs
$inputs = [
    ['url' => 'https://example.com/video1.mp4'],
    ['url' => 'https://example.com/video2.mp4']
];

// Define FFmpeg command
$command = '-filter_complex "[0:v] [1:v] concat=n=2:v=1:a=0 [v]" -map "[v]"';

$result = $ffmpeg->compose($inputs, $command);

if ($result['success']) {
    echo "Media composition successful!";
    // Handle the result
} else {
    echo "Failed to compose media: " . $result['error'];
}
?>
```

## Image Endpoints

### Convert Image to Video

```php
<?php
require_once 'config.php';
require_once 'api/NcaApiClient.php';
require_once 'api/endpoints/Image.php';

// Initialize API client
$apiClient = new NcaApiClient();

// Initialize image endpoint
$image = new Image($apiClient);

// Convert image to video
$imageUrl = 'https://example.com/image.jpg';

$result = $image->convertToVideo($imageUrl);

if ($result['success']) {
    echo "Image converted to video successfully!";
    // Handle the result
} else {
    echo "Failed to convert image to video: " . $result['error'];
}
?>
```

### Take Screenshot of Webpage

```php
<?php
require_once 'config.php';
require_once 'api/NcaApiClient.php';
require_once 'api/endpoints/Image.php';

// Initialize API client
$apiClient = new NcaApiClient();

// Initialize image endpoint
$image = new Image($apiClient);

// Take screenshot of webpage
$url = 'https://example.com';

// Optional options
$options = [
    'viewport_width' => 1920,
    'viewport_height' => 1080
];

$result = $image->screenshotWebpage($url, $options);

if ($result['success']) {
    echo "Webpage screenshot taken successfully!";
    // Handle the result
} else {
    echo "Failed to take webpage screenshot: " . $result['error'];
}
?>
```

## Media Endpoints

### Convert Media to MP3

```php
<?php
require_once 'config.php';
require_once 'api/NcaApiClient.php';
require_once 'api/endpoints/Media.php';

// Initialize API client
$apiClient = new NcaApiClient();

// Initialize media endpoint
$media = new Media($apiClient);

// Convert media to MP3
$mediaUrl = 'https://example.com/audio.wav';

$result = $media->convertToMp3($mediaUrl);

if ($result['success']) {
    echo "Media converted to MP3 successfully!";
    // Handle the result
} else {
    echo "Failed to convert media to MP3: " . $result['error'];
}
?>
```

### Download Media

```php
<?php
require_once 'config.php';
require_once 'api/NcaApiClient.php';
require_once 'api/endpoints/Media.php';

// Initialize API client
$apiClient = new NcaApiClient();

// Initialize media endpoint
$media = new Media($apiClient);

// Download media
$mediaUrl = 'https://example.com/video.mp4';

// Optional cookies for authenticated downloads
$cookies = 'sessionid=abc123; loggedin=true';

$result = $media->download($mediaUrl, $cookies);

if ($result['success']) {
    echo "Media downloaded successfully!";
    // Handle the result
} else {
    echo "Failed to download media: " . $result['error'];
}
?>
```

### Transcribe Media

```php
<?php
require_once 'config.php';
require_once 'api/NcaApiClient.php';
require_once 'api/endpoints/Media.php';

// Initialize API client
$apiClient = new NcaApiClient();

// Initialize media endpoint
$media = new Media($apiClient);

// Transcribe media
$mediaUrl = 'https://example.com/audio.mp3';

// Optional translation
$translate = true;

$result = $media->transcribe($mediaUrl, $translate);

if ($result['success']) {
    echo "Media transcribed successfully!";
    // Handle the result
} else {
    echo "Failed to transcribe media: " . $result['error'];
}
?>
```

## S3 Endpoints

### Upload File to S3

```php
<?php
require_once 'config.php';
require_once 'api/NcaApiClient.php';
require_once 'api/endpoints/S3.php';

// Initialize API client
$apiClient = new NcaApiClient();

// Initialize S3 endpoint
$s3 = new S3($apiClient);

// Upload file to S3
$fileUrl = 'https://example.com/file.mp4';
$bucket = 'my-bucket';
$key = 'uploads/file.mp4';

// Optional options
$options = [
    'acl' => 'public-read'
];

$result = $s3->upload($fileUrl, $bucket, $key, $options);

if ($result['success']) {
    echo "File uploaded to S3 successfully!";
    // Handle the result
} else {
    echo "Failed to upload file to S3: " . $result['error'];
}
?>
```

## Toolkit Endpoints

### Authenticate

```php
<?php
require_once 'config.php';
require_once 'api/NcaApiClient.php';
require_once 'api/endpoints/Toolkit.php';

// Initialize API client
$apiClient = new NcaApiClient();

// Initialize toolkit endpoint
$toolkit = new Toolkit($apiClient);

// Authenticate with the API
$result = $toolkit->authenticate();

if ($result['success']) {
    echo "Authentication successful!";
    // Handle the result
} else {
    echo "Authentication failed: " . $result['error'];
}
?>
```

### Test API

```php
<?php
require_once 'config.php';
require_once 'api/NcaApiClient.php';
require_once 'api/endpoints/Toolkit.php';

// Initialize API client
$apiClient = new NcaApiClient();

// Initialize toolkit endpoint
$toolkit = new Toolkit($apiClient);

// Test the API
$result = $toolkit->test();

if ($result['success']) {
    echo "API test successful!";
    // Handle the result
} else {
    echo "API test failed: " . $result['error'];
}
?>
```

### Get Job Status

```php
<?php
require_once 'config.php';
require_once 'api/NcaApiClient.php';
require_once 'api/endpoints/Toolkit.php';

// Initialize API client
$apiClient = new NcaApiClient();

// Initialize toolkit endpoint
$toolkit = new Toolkit($apiClient);

// Get job status
$jobId = 'job-123456';

$result = $toolkit->getJobStatus($jobId);

if ($result['success']) {
    echo "Job status retrieved successfully!";
    // Handle the result
} else {
    echo "Failed to get job status: " . $result['error'];
}
?>
```

## Video Endpoints

### Add Caption to Video

```php
<?php
require_once 'config.php';
require_once 'api/NcaApiClient.php';
require_once 'api/endpoints/Video.php';

// Initialize API client
$apiClient = new NcaApiClient();

// Initialize video endpoint
$video = new Video($apiClient);

// Add caption to video
$videoUrl = 'https://example.com/video.mp4';

// Optional options
$options = [
    'settings' => [
        'position' => 'bottom_center',
        'font_size' => 16,
        'font_family' => 'Arial'
    ]
];

$result = $video->addCaption($videoUrl, $options);

if ($result['success']) {
    echo "Caption added to video successfully!";
    // Handle the result
} else {
    echo "Failed to add caption to video: " . $result['error'];
}
?>
```

### Concatenate Videos

```php
<?php
require_once 'config.php';
require_once 'api/NcaApiClient.php';
require_once 'api/endpoints/Video.php';

// Initialize API client
$apiClient = new NcaApiClient();

// Initialize video endpoint
$video = new Video($apiClient);

// Concatenate videos
$videoUrls = [
    'https://example.com/video1.mp4',
    'https://example.com/video2.mp4'
];

$result = $video->concatenate($videoUrls);

if ($result['success']) {
    echo "Videos concatenated successfully!";
    // Handle the result
} else {
    echo "Failed to concatenate videos: " . $result['error'];
}
?>
```

### Extract Thumbnail

```php
<?php
require_once 'config.php';
require_once 'api/NcaApiClient.php';
require_once 'api/endpoints/Video.php';

// Initialize API client
$apiClient = new NcaApiClient();

// Initialize video endpoint
$video = new Video($apiClient);

// Extract thumbnail
$videoUrl = 'https://example.com/video.mp4';

$result = $video->extractThumbnail($videoUrl);

if ($result['success']) {
    echo "Thumbnail extracted successfully!";
    // Handle the result
} else {
    echo "Failed to extract thumbnail: " . $result['error'];
}
?>
```

### Cut Video

```php
<?php
require_once 'config.php';
require_once 'api/NcaApiClient.php';
require_once 'api/endpoints/Video.php';

// Initialize API client
$apiClient = new NcaApiClient();

// Initialize video endpoint
$video = new Video($apiClient);

// Cut video
$videoUrl = 'https://example.com/video.mp4';

// Define segments to cut
$segments = [
    ['start' => '0', 'end' => '10'],
    ['start' => '20', 'end' => '30']
];

$result = $video->cut($videoUrl, $segments);

if ($result['success']) {
    echo "Video cut successfully!";
    // Handle the result
} else {
    echo "Failed to cut video: " . $result['error'];
}
?>
```

### Split Video

```php
<?php
require_once 'config.php';
require_once 'api/NcaApiClient.php';
require_once 'api/endpoints/Video.php';

// Initialize API client
$apiClient = new NcaApiClient();

// Initialize video endpoint
$video = new Video($apiClient);

// Split video
$videoUrl = 'https://example.com/video.mp4';

// Define segments to split
$segments = [
    ['start' => '0', 'end' => '10'],
    ['start' => '10', 'end' => '20'],
    ['start' => '20', 'end' => '30']
];

$result = $video->split($videoUrl, $segments);

if ($result['success']) {
    echo "Video split successfully!";
    // Handle the result
} else {
    echo "Failed to split video: " . $result['error'];
}
?>
```

### Trim Video

```php
<?php
require_once 'config.php';
require_once 'api/NcaApiClient.php';
require_once 'api/endpoints/Video.php';

// Initialize API client
$apiClient = new NcaApiClient();

// Initialize video endpoint
$video = new Video($apiClient);

// Trim video
$videoUrl = 'https://example.com/video.mp4';
$start = 5.0;
$end = 15.0;

$result = $video->trim($videoUrl, $start, $end);

if ($result['success']) {
    echo "Video trimmed successfully!";
    // Handle the result
} else {
    echo "Failed to trim video: " . $result['error'];
}
?>
```

## YouTube Downloader

### Download YouTube Video

```php
<?php
require_once 'config.php';
require_once 'api/YouTubeDownloader.php';

// Initialize YouTube downloader
$youtube = new YouTubeDownloader();

// Download YouTube video
$url = 'https://www.youtube.com/watch?v=example';

// Optional cookies for authenticated downloads
$cookies = 'SID=abc123; SSID=def456';

$result = $youtube->download($url, $cookies);

if ($result['success']) {
    echo "YouTube video downloaded successfully!";
    echo "Title: " . $result['title'];
    echo "File: " . $result['filename'];
    // Handle the downloaded file
} else {
    echo "Failed to download YouTube video: " . $result['error'];
}
?>
```

### Get YouTube Video Info

```php
<?php
require_once 'config.php';
require_once 'api/YouTubeDownloader.php';

// Initialize YouTube downloader
$youtube = new YouTubeDownloader();

// Get YouTube video info
$url = 'https://www.youtube.com/watch?v=example';

// Optional cookies for authenticated access
$cookies = 'SID=abc123; SSID=def456';

$result = $youtube->getVideoInfo($url, $cookies);

if ($result['success']) {
    echo "YouTube video info retrieved successfully!";
    // Handle the video info
    print_r($result['info']);
} else {
    echo "Failed to get YouTube video info: " . $result['error'];
}
?>
```