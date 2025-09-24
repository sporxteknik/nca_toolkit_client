<?php
require_once 'config.php';
require_once 'api/NcaApiClient.php';
require_once 'api/endpoints/Audio.php';
require_once 'api/endpoints/Code.php';
require_once 'api/endpoints/Ffmpeg.php';
require_once 'api/endpoints/Image.php';
require_once 'api/endpoints/Media.php';
require_once 'api/endpoints/S3.php';
require_once 'api/endpoints/Toolkit.php';
require_once 'api/endpoints/Video.php';
require_once 'api/YouTubeDownloader.php';

// Helper function to convert HH:MM:SS format to seconds
function timeToSeconds($timeStr) {
    if (!$timeStr) return 0;
    
    $parts = explode(':', $timeStr);
    if (count($parts) !== 3) {
        // If not in HH:MM:SS format, try MM:SS
        $shortParts = explode(':', $timeStr);
        if (count($shortParts) === 2) {
            return (int)$shortParts[0] * 60 + (int)$shortParts[1];
        }
        return 0;
    }
    
    $hours = (int)$parts[0];
    $minutes = (int)$parts[1];
    $seconds = (int)$parts[2];
    
    return $hours * 3600 + $minutes * 60 + $seconds;
}

// Initialize API client
$apiClient = new NcaApiClient();

// Initialize endpoint classes
$audio = new Audio($apiClient);
$code = new Code($apiClient);
$ffmpeg = new Ffmpeg($apiClient);
$image = new Image($apiClient);
$media = new Media($apiClient);
$s3 = new S3($apiClient);
$toolkit = new Toolkit($apiClient);
$video = new Video($apiClient);
$youtube = new YouTubeDownloader();

// Handle file download requests
if (isset($_GET['download']) && isset($_GET['file']) && isset($_GET['temp_dir'])) {
    $filePath = $_GET['temp_dir'] . '/' . basename($_GET['file']);
    if (file_exists($filePath)) {
        $youtube->serveFile($filePath, $_GET['file']);
        exit;
    }
}

// Handle form submissions
$result = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $endpoint = $_POST['endpoint'] ?? '';
    
    switch ($endpoint) {
        // Audio endpoints
        case 'audio_concatenate':
            $audioUrls = explode("
", trim($_POST['audio_urls']));
            // Convert simple URLs to objects with the correct property name
            $audioUrlObjects = [];
            foreach ($audioUrls as $url) {
                $audioUrlObjects[] = ['audio_url' => trim($url)];
            }
            $result = $audio->concatenate($audioUrlObjects);
            // If successful, we don't want to display the result on the page
            // The user will be redirected to download the file
            if ($result['success']) {
                // We'll handle this in the display section
            }
            break;
            
        // Image endpoints
        case 'image_convert_video':
            $imageUrl = $_POST['image_url'];
            $result = $image->convertToVideo($imageUrl);
            // If successful, we'll handle this in the display section
            if ($result['success']) {
                // We'll handle this in the display section
            }
            break;
            
        case 'image_screenshot_webpage':
            $url = $_POST['url'];
            $options = [];
            if (!empty($_POST['viewport_width'])) {
                $options['viewport_width'] = (int)$_POST['viewport_width'];
            }
            if (!empty($_POST['viewport_height'])) {
                $options['viewport_height'] = (int)$_POST['viewport_height'];
            }
            $result = $image->screenshotWebpage($url, $options);
            // If successful, we'll handle this in the display section
            if ($result['success']) {
                // We'll handle this in the display section
            }
            break;
            
        // Media endpoints
        case 'media_convert_mp3':
            $mediaUrl = $_POST['media_url'];
            $result = $media->convertToMp3($mediaUrl);
            break;
            
        case 'media_download':
            $url = $_POST['url'];
            $cookies = !empty($_POST['cookies']) ? $_POST['cookies'] : null;
            $result = $media->download($url, $cookies);
            break;
            
        case 'media_transcribe':
            $mediaUrl = $_POST['media_url'];
            $translate = isset($_POST['translate']);
            $srtFormat = isset($_POST['srt_format']);
            $result = $media->transcribe($mediaUrl, $translate);
            
            // If SRT format is requested, convert the transcription to SRT
            if ($srtFormat && $result['success'] && isset($result['data']['response']['text'])) {
                // For now, we'll use a fixed duration of 120 seconds (2 minutes)
                // In a real implementation, you would get the actual duration of the media
                $srtText = $media->textToSrt($result['data']['response']['text'], 120);
                
                // Modify the result to include the SRT text
                $result['data']['response']['srt'] = $srtText;
            }
            break;
            
        // YouTube endpoints
        case 'youtube_download':
            $url = $_POST['url'];
            $cookies = !empty($_POST['cookies']) ? $_POST['cookies'] : null;
            $result = $youtube->download($url, $cookies);
            // If successful, we don't want to display the result on the page
            // The user will be redirected to download the file
            if ($result['success']) {
                // We'll handle this in the display section
            }
            break;
            
        case 'youtube_info':
            $url = $_POST['url'];
            $cookies = !empty($_POST['cookies']) ? $_POST['cookies'] : null;
            $result = $youtube->getVideoInfo($url, $cookies);
            break;
            
        // Video endpoints
        case 'video_caption':
            $videoUrl = $_POST['video_url'];
            
            // Prepare options for the API request
            $options = [];
            if (!empty($_POST['caption_text'])) {
                $options['captions'] = $_POST['caption_text'];
            }
            
            // Add settings based on form input
            $settings = [];
            if (!empty($_POST['position'])) {
                $settings['position'] = $_POST['position'];
            }
            if (!empty($_POST['font_size']) && is_numeric($_POST['font_size'])) {
                $settings['font_size'] = (int)$_POST['font_size'];
            }
            if (!empty($_POST['font_family'])) {
                $settings['font_family'] = $_POST['font_family'];
            }
            if (!empty($_POST['style'])) {
                $settings['style'] = $_POST['style'];
            }
            if (!empty($_POST['line_color'])) {
                $settings['line_color'] = $_POST['line_color'];
            }
            if (!empty($_POST['outline_color'])) {
                $settings['outline_color'] = $_POST['outline_color'];
            }
            if (!empty($_POST['word_color'])) {
                $settings['word_color'] = $_POST['word_color'];
            }
            
            // Only add settings if we have any
            if (!empty($settings)) {
                $options['settings'] = $settings;
            }
            
            $result = $video->addCaption($videoUrl, $options);
            break;
            
        case 'video_concatenate':
            $videoUrls = explode("
", trim($_POST['video_urls']));
            // Convert simple URLs to objects with the correct property name
            $videoUrlObjects = [];
            foreach ($videoUrls as $url) {
                $videoUrlObjects[] = ['video_url' => trim($url)];
            }
            $result = $video->concatenate($videoUrlObjects);
            // If successful, we don't want to display the result on the page
            // The user will be redirected to download the file
            if ($result['success']) {
                // We'll handle this in the display section
            }
            break;
            
        case 'video_thumbnail':
            $videoUrl = $_POST['video_url'];
            $timestamp = (float)$_POST['timestamp'];
            $result = $video->extractThumbnail($videoUrl, $timestamp);
            break;
            
        case 'video_cut':
            $videoUrl = $_POST['video_url'];
            
            // Convert time inputs to segments array
            $startTimes = $_POST['start_times'] ?? [];
            $endTimes = $_POST['end_times'] ?? [];
            
            $segments = [];
            for ($i = 0; $i < count($startTimes); $i++) {
                $start = $startTimes[$i];
                $end = $endTimes[$i];
                
                // Convert HH:MM:SS format to seconds
                $startSeconds = timeToSeconds($start);
                $endSeconds = timeToSeconds($end);
                
                $segments[] = [
                    'start' => $startSeconds,
                    'end' => $endSeconds
                ];
            }
            
            $result = $video->cut($videoUrl, $segments);
            break;
            
        case 'video_split':
            $videoUrl = $_POST['video_url'];
            
            // Convert time inputs to segments array
            $startTimes = $_POST['start_times'] ?? [];
            $endTimes = $_POST['end_times'] ?? [];
            
            $segments = [];
            for ($i = 0; $i < count($startTimes); $i++) {
                $start = $startTimes[$i];
                $end = $endTimes[$i];
                
                // Convert HH:MM:SS format to seconds
                $startSeconds = timeToSeconds($start);
                $endSeconds = timeToSeconds($end);
                
                $segments[] = [
                    'start' => $startSeconds,
                    'end' => $endSeconds
                ];
            }
            
            $result = $video->split($videoUrl, $segments);
            break;
            
        case 'video_trim':
            $videoUrl = $_POST['video_url'];
            $start = (float)$_POST['start'];
            $end = (float)$_POST['end'];
            $result = $video->trim($videoUrl, $start, $end);
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NCA Toolkit PHP Client</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>NCA Toolkit PHP Client</h1>
            <p>Access all No-Code Architects Toolkit API endpoints</p>
        </header>
        
        <main>
            <div class="endpoint-selector">
                <label for="endpoint">Select Endpoint:</label>
                <select id="endpoint" name="endpoint">
                    <option value="">-- Choose an endpoint --</option>
                    <optgroup label="Audio">
                        <option value="audio_concatenate">Audio Concatenate</option>
                    </optgroup>
                    <optgroup label="Image">
                        <option value="image_convert_video">Image to Video</option>
                        <option value="image_screenshot_webpage">Webpage Screenshot</option>
                    </optgroup>
                    <optgroup label="Media">
                        <option value="media_convert_mp3">Convert to MP3</option>
                        <option value="media_download">Media Download</option>
                        <option value="media_transcribe">Media Transcribe</option>
                    </optgroup>
                    <optgroup label="YouTube">
                        <option value="youtube_download">YouTube Download</option>
                        <option value="youtube_info">YouTube Video Info</option>
                    </optgroup>
                    <optgroup label="Video">
                        <option value="video_caption">Add Caption</option>
                        <option value="video_concatenate">Video Concatenate</option>
                        <option value="video_thumbnail">Extract Thumbnail</option>
                        <option value="video_cut">Cut Video</option>
                        <option value="video_trim">Trim Video</option>
                    </optgroup>
                </select>
            </div>
            
            <div class="endpoint-form" id="endpoint-form">
                <!-- Form content will be loaded here dynamically -->
            </div>
            
            <?php if ($result): ?>
                <div class="result">
                    <h3>API Response</h3>
                    <?php if (($endpoint === 'youtube_download' || $endpoint === 'audio_concatenate' || $endpoint === 'video_concatenate' || $endpoint === 'image_convert_video' || $endpoint === 'media_convert_mp3' || $endpoint === 'video_thumbnail' || $endpoint === 'image_screenshot_webpage') && $result['success']): ?>
                        <div class="download-result">
                            <h4><?php echo ucfirst(str_replace('_', ' ', $endpoint)); ?> Successful!</h4>
                            <?php if (isset($result['data']['job_id'])): ?>
                                <p><strong>Job ID:</strong> <?php echo htmlspecialchars($result['data']['job_id']); ?></p>
                            <?php endif; ?>
                            <?php if (isset($result['title'])): ?>
                                <p><strong>Title:</strong> <?php echo htmlspecialchars($result['title']); ?></p>
                            <?php endif; ?>
                            <?php if (isset($result['filename'])): ?>
                                <p><strong>File:</strong> <?php echo htmlspecialchars($result['filename']); ?></p>
                            <?php endif; ?>
                            <?php if (isset($result['file_size'])): ?>
                                <p><strong>Size:</strong> <?php echo round($result['file_size'] / 1024 / 1024, 2); ?> MB</p>
                            <?php endif; ?>
                            <?php if (isset($result['data']['response'])): ?>
                                <p><strong>Download URL:</strong> <?php echo htmlspecialchars($result['data']['response']); ?></p>
                                <a href="<?php echo htmlspecialchars($result['data']['response']); ?>" class="btn" download>Download File</a>
                            <?php elseif (isset($result['filename']) && isset($result['temp_dir'])): ?>
                                <a href="?download=1&file=<?php echo urlencode($result['filename']); ?>&temp_dir=<?php echo urlencode($result['temp_dir']); ?>" class="btn">Download File</a>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <pre><?php echo htmlspecialchars(json_encode($result, JSON_PRETTY_PRINT)); ?></pre>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </main>
    </div>
    
    <script src="js/script.js"></script>
</body>
</html>