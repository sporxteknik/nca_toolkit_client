<?php
require_once 'config.php';
require_once 'api/NcaApiClient.php';
require_once 'api/LanguageManager.php';
require_once 'api/endpoints/Audio.php';
require_once 'api/endpoints/Image.php';
require_once 'api/endpoints/Media.php';
require_once 'api/endpoints/Video.php';
require_once 'api/YouTubeDownloader.php';
require_once 'api/GcsUploader.php';

// Initialize language manager
$languageManager = new LanguageManager();

// Check if language change request
if (isset($_GET['lang'])) {
    $languageManager->setCurrentLanguage($_GET['lang']);
    // Redirect to remove the lang parameter from URL
    $redirectUrl = strtok($_SERVER['REQUEST_URI'], '?');
    if (!empty($_GET) && count($_GET) > 1) {
        // Remove lang parameter and rebuild query string
        $params = $_GET;
        unset($params['lang']);
        if (!empty($params)) {
            $redirectUrl .= '?' . http_build_query($params);
        }
    }
    header('Location: ' . $redirectUrl);
    exit;
}

// Initialize API client
$apiClient = new NcaApiClient();

// Initialize endpoint classes
$audio = new Audio($apiClient);
$image = new Image($apiClient);
$media = new Media($apiClient);
$video = new Video($apiClient);
$youtube = new YouTubeDownloader();
$gcsUploader = new GcsUploader();

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
    
    // Handle file uploads if any
    $uploadedFiles = null;
    if (!empty($_FILES)) {
        // Debug output
        error_log("FILES array: " . print_r($_FILES, true));
        
        $filesToUpload = [];
        
        // Handle single file uploads
        foreach ($_FILES as $key => $file) {
            error_log("Processing file key: " . $key);
            error_log("File data: " . print_r($file, true));
            error_log("File name is array: " . (is_array($file['name']) ? 'true' : 'false'));
            
            if (is_array($file['name'])) {
                // Multiple files
                error_log("Processing as multiple files, count: " . count($file['name']));
                for ($i = 0; $i < count($file['name']); $i++) {
                    error_log("File $i - name: " . $file['name'][$i] . ", error: " . $file['error'][$i] . ", tmp_name: " . $file['tmp_name'][$i]);
                    if ($file['error'][$i] === UPLOAD_ERR_OK) {
                        $filesToUpload[] = [
                            'path' => $file['tmp_name'][$i],
                            'name' => $file['name'][$i]
                        ];
                    }
                }
            } else {
                // Single file
                error_log("Processing as single file - name: " . $file['name'] . ", error: " . $file['error'] . ", tmp_name: " . $file['tmp_name']);
                if ($file['error'] === UPLOAD_ERR_OK) {
                    $filesToUpload[] = [
                        'path' => $file['tmp_name'],
                        'name' => $file['name']
                    ];
                }
            }
        }
        
        error_log("Final filesToUpload array: " . print_r($filesToUpload, true));
        
        // Upload files to GCS
        if (!empty($filesToUpload)) {
            // Debug output
            error_log("Files to upload: " . print_r($filesToUpload, true));
            
            if ($endpoint === 'file_upload') {
                // Use the data bucket for file uploads
                if (count($filesToUpload) === 1) {
                    $uploadResult = $gcsUploader->uploadFileToDataBucket($filesToUpload[0]['path'], $filesToUpload[0]['name']);
                    if ($uploadResult['success']) {
                        $uploadedFiles = ['success' => true, 'url' => $uploadResult['url'], 'file_name' => $uploadResult['file_name']];
                    } else {
                        $result = [
                            'success' => false,
                            'error' => 'File upload failed: ' . $uploadResult['error']
                        ];
                    }
                } else {
                    $uploadResult = $gcsUploader->uploadMultipleFilesToDataBucket($filesToUpload);
                    // Debug output
                    error_log("Upload result: " . print_r($uploadResult, true));
                    if ($uploadResult['success']) {
                        $uploadedFiles = $uploadResult;
                    } else {
                        $result = [
                            'success' => false,
                            'error' => 'File upload failed: ' . $uploadResult['error']
                        ];
                    }
                }
            } else {
                // Use the default bucket for other endpoints
                if (count($filesToUpload) === 1) {
                    $uploadResult = $gcsUploader->uploadFile($filesToUpload[0]['path'], $filesToUpload[0]['name']);
                    if ($uploadResult['success']) {
                        $uploadedFiles = ['success' => true, 'url' => $uploadResult['url']];
                    } else {
                        $result = [
                            'success' => false,
                            'error' => 'File upload failed: ' . $uploadResult['error']
                        ];
                    }
                } else {
                    $uploadResult = $gcsUploader->uploadMultipleFiles($filesToUpload);
                    // Debug output
                    error_log("Upload result: " . print_r($uploadResult, true));
                    if ($uploadResult['success']) {
                        $uploadedFiles = $uploadResult;
                    } else {
                        $result = [
                            'success' => false,
                            'error' => 'File upload failed: ' . $uploadResult['error']
                        ];
                    }
                }
            }
        }
    }
    
    // Process the endpoint request only if file upload was successful or not needed
    if ($result === null) {
        switch ($endpoint) {
        // Audio endpoints
        case 'audio_concatenate':
            // Use uploaded files if available, otherwise use URLs from form
            if (!empty($uploadedFiles)) {
                if ($uploadedFiles['success']) {
                    $audioUrls = [];
                    if (isset($uploadedFiles['files'])) {
                        // Multiple files
                        foreach ($uploadedFiles['files'] as $file) {
                            $audioUrls[] = ['audio_url' => $file['url']];
                        }
                    } else {
                        // Single file
                        $audioUrls = [['audio_url' => $uploadedFiles['url']]];
                    }
                } else {
                    $result = $uploadedFiles;
                    break;
                }
            } else {
                $audioUrls = explode("\n", trim($_POST['audio_urls']));
                // Convert simple URLs to objects with the correct property name
                $audioUrlObjects = [];
                foreach ($audioUrls as $url) {
                    $audioUrlObjects[] = ['audio_url' => trim($url)];
                }
                $audioUrls = $audioUrlObjects;
            }
            $result = $audio->concatenate($audioUrls);
            // If successful, we don't want to display the result on the page
            // The user will be redirected to download the file
            if ($result['success']) {
                // We'll handle this in the display section
            }
            break;
            
        // Image endpoints
        case 'image_convert_video':
            // Use uploaded file if available, otherwise use URL from form
            if (!empty($uploadedFiles)) {
                if ($uploadedFiles['success']) {
                    if (isset($uploadedFiles['url'])) {
                        $imageUrl = $uploadedFiles['url'];
                    } else {
                        $imageUrl = $uploadedFiles['files'][0]['url'];
                    }
                } else {
                    $result = $uploadedFiles;
                    break;
                }
            } else {
                $imageUrl = $_POST['image_url'];
            }
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
            // Use uploaded file if available, otherwise use URL from form
            if (!empty($uploadedFiles)) {
                if ($uploadedFiles['success']) {
                    if (isset($uploadedFiles['url'])) {
                        $mediaUrl = $uploadedFiles['url'];
                    } else {
                        $mediaUrl = $uploadedFiles['files'][0]['url'];
                    }
                } else {
                    $result = $uploadedFiles;
                    break;
                }
            } else {
                $mediaUrl = $_POST['media_url'];
            }
            $result = $media->convertToMp3($mediaUrl);
            break;
            
        case 'media_download':
            // Use uploaded file if available, otherwise use URL from form
            if (!empty($uploadedFiles)) {
                if ($uploadedFiles['success']) {
                    if (isset($uploadedFiles['url'])) {
                        $url = $uploadedFiles['url'];
                    } else {
                        $url = $uploadedFiles['files'][0]['url'];
                    }
                } else {
                    $result = $uploadedFiles;
                    break;
                }
            } else {
                $url = $_POST['url'];
            }
            $cookies = !empty($_POST['cookies']) ? $_POST['cookies'] : null;
            $result = $media->download($url, $cookies);
            break;
            
        case 'media_transcribe':
            // Use uploaded file if available, otherwise use URL from form
            if (!empty($uploadedFiles)) {
                if ($uploadedFiles['success']) {
                    if (isset($uploadedFiles['url'])) {
                        $mediaUrl = $uploadedFiles['url'];
                    } else {
                        $mediaUrl = $uploadedFiles['files'][0]['url'];
                    }
                } else {
                    $result = $uploadedFiles;
                    break;
                }
            } else {
                $mediaUrl = $_POST['media_url'];
            }
            $srtFormat = isset($_POST['srt_format']);
            $directOutput = isset($_POST['direct_output']);
            
            // Store the direct output preference in a global variable so we can use it in the display section
            $GLOBALS['transcribe_direct_output'] = $directOutput;
            
            if ($srtFormat) {
                // Use the SRT transcription method without translation
                // If user doesn't want direct output, request a cloud URL for the SRT file
                $useCloudUrl = !$directOutput;
                $result = $media->transcribeToSrt($mediaUrl, false, $useCloudUrl);
            } else {
                // Use the regular transcription method without translation
                $result = $media->transcribe($mediaUrl, false);
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
            // Use uploaded file if available, otherwise use URL from form
            if (!empty($uploadedFiles)) {
                if ($uploadedFiles['success']) {
                    if (isset($uploadedFiles['url'])) {
                        $videoUrl = $uploadedFiles['url'];
                    } else {
                        $videoUrl = $uploadedFiles['files'][0]['url'];
                    }
                } else {
                    $result = $uploadedFiles;
                    break;
                }
            } else {
                $videoUrl = $_POST['video_url'];
            }
            
            // Prepare options for the API request
            $options = [];
            
            // Check if an SRT URL was provided
            if (!empty($_POST['srt_url'])) {
                $options['captions'] = $_POST['srt_url'];
            } elseif (!empty($_POST['caption_text'])) {
                // Only add caption text if no SRT URL was provided
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
            
            // Debug output to see what settings are being sent
            error_log("Caption settings: " . print_r($settings, true));
            
            // Only add settings if we have any
            if (!empty($settings)) {
                $options['settings'] = $settings;
            }
            
            error_log("Caption options: " . print_r($options, true));
            
            $result = $video->addCaption($videoUrl, $options);
            break;
            
        case 'video_concatenate':
            // Use uploaded files if available, otherwise use URLs from form
            if (!empty($uploadedFiles)) {
                if ($uploadedFiles['success']) {
                    $videoUrls = [];
                    if (isset($uploadedFiles['files'])) {
                        // Multiple files
                        foreach ($uploadedFiles['files'] as $file) {
                            $videoUrls[] = ['video_url' => $file['url']];
                        }
                    } else {
                        // Single file
                        $videoUrls = [['video_url' => $uploadedFiles['url']]];
                    }
                } else {
                    $result = $uploadedFiles;
                    break;
                }
            } else {
                $videoUrls = explode("\n", trim($_POST['video_urls']));
                // Convert simple URLs to objects with the correct property name
                $videoUrlObjects = [];
                foreach ($videoUrls as $url) {
                    $videoUrlObjects[] = ['video_url' => trim($url)];
                }
                $videoUrls = $videoUrlObjects;
            }
            $result = $video->concatenate($videoUrls);
            // Debug output
            error_log("Video URLs being sent to concatenate: " . print_r($videoUrls, true));
            // If successful, we don't want to display the result on the page
            // The user will be redirected to download the file
            if ($result['success']) {
                // We'll handle this in the display section
            }
            break;
            
        case 'video_thumbnail':
            // Use uploaded file if available, otherwise use URL from form
            if (!empty($uploadedFiles)) {
                if ($uploadedFiles['success']) {
                    if (isset($uploadedFiles['url'])) {
                        $videoUrl = $uploadedFiles['url'];
                    } else {
                        $videoUrl = $uploadedFiles['files'][0]['url'];
                    }
                } else {
                    $result = $uploadedFiles;
                    break;
                }
            } else {
                $videoUrl = $_POST['video_url'];
            }
            $timestamp = (float)$_POST['timestamp'];
            // For now, we'll ignore the timestamp as the API doesn't accept it
            $result = $video->extractThumbnail($videoUrl);
            break;
            
        case 'video_cut':
            // Use uploaded file if available, otherwise use URL from form
            if (!empty($uploadedFiles)) {
                if ($uploadedFiles['success']) {
                    if (isset($uploadedFiles['url'])) {
                        $videoUrl = $uploadedFiles['url'];
                    } else {
                        $videoUrl = $uploadedFiles['files'][0]['url'];
                    }
                } else {
                    $result = $uploadedFiles;
                    break;
                }
            } else {
                $videoUrl = $_POST['video_url'];
            }
            $segments = json_decode($_POST['segments'], true);
            $result = $video->cut($videoUrl, $segments);
            break;
            
        case 'video_split':
            // Use uploaded file if available, otherwise use URL from form
            if (!empty($uploadedFiles)) {
                if ($uploadedFiles['success']) {
                    if (isset($uploadedFiles['url'])) {
                        $videoUrl = $uploadedFiles['url'];
                    } else {
                        $videoUrl = $uploadedFiles['files'][0]['url'];
                    }
                } else {
                    $result = $uploadedFiles;
                    break;
                }
            } else {
                $videoUrl = $_POST['video_url'];
            }
            $segments = json_decode($_POST['segments'], true);
            $result = $video->split($videoUrl, $segments);
            break;
            
        case 'video_trim':
            // Use uploaded file if available, otherwise use URL from form
            if (!empty($uploadedFiles)) {
                if ($uploadedFiles['success']) {
                    if (isset($uploadedFiles['url'])) {
                        $videoUrl = $uploadedFiles['url'];
                    } else {
                        $videoUrl = $uploadedFiles['files'][0]['url'];
                    }
                } else {
                    $result = $uploadedFiles;
                    break;
                }
            } else {
                $videoUrl = $_POST['video_url'];
            }
            $start = (float)$_POST['start'];
            $end = (float)$_POST['end'];
            $result = $video->trim($videoUrl, $start, $end);
            break;
            
        // File Upload endpoint
        case 'file_upload':
            // Handle file uploads to GCP Storage (maksimum-data bucket)
            if (!empty($uploadedFiles)) {
                if ($uploadedFiles['success']) {
                    // Return the uploaded file URLs
                    $result = $uploadedFiles;
                } else {
                    $result = $uploadedFiles;
                }
            } else {
                $result = [
                    'success' => false,
                    'error' => 'No files were uploaded'
                ];
            }
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="<?php echo $languageManager->getCurrentLanguageCode(); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $languageManager->get('app_title'); ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1><?php echo $languageManager->get('app_title'); ?></h1>
            <p><?php echo $languageManager->get('app_description'); ?></p>
            <div class="language-switcher">
                <label for="language-select"><?php echo $languageManager->get('language'); ?>:</label>
                <select id="language-select" onchange="changeLanguage(this.value)">
                    <option value="en" <?php echo $languageManager->isCurrentLanguage('en') ? 'selected' : ''; ?>><?php echo $languageManager->get('english'); ?></option>
                    <option value="tr" <?php echo $languageManager->isCurrentLanguage('tr') ? 'selected' : ''; ?>><?php echo $languageManager->get('turkish'); ?></option>
                </select>
            </div>
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
                        <option value="video_split">Split Video</option>
                        <option value="video_trim">Trim Video</option>
                    </optgroup>
                    <optgroup label="File Upload">
                        <option value="file_upload">Upload to GCP Storage</option>
                    </optgroup>
                </select>
            </div>
            
            <div class="endpoint-form" id="endpoint-form">
                <!-- Form content will be loaded here dynamically -->
            </div>
            
            <!-- Loading animation -->
            <div class="loading" id="loading">
                <div class="spinner"></div>
                <p class="loading-text"><?php echo $languageManager->get('processing_request'); ?></p>
            </div>
            
            <?php if ($result): ?>
                <div class="result">
                    <h3>API Response</h3>
                    <?php if (($endpoint === 'youtube_download' || $endpoint === 'audio_concatenate' || $endpoint === 'video_concatenate' || $endpoint === 'image_convert_video' || $endpoint === 'media_convert_mp3' || $endpoint === 'video_thumbnail' || $endpoint === 'image_screenshot_webpage' || $endpoint === 'video_caption') && $result['success']): ?>
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
                    <?php elseif (($endpoint === 'video_cut' || $endpoint === 'video_trim') && $result['success']): ?>
                        <div class="download-result">
                            <h4><?php echo ucfirst(str_replace('_', ' ', $endpoint)); ?> Successful!</h4>
                            <?php if (isset($result['data']['job_id'])): ?>
                                <p><strong>Job ID:</strong> <?php echo htmlspecialchars($result['data']['job_id']); ?></p>
                            <?php endif; ?>
                            <?php if (isset($result['data']['response'])): ?>
                                <p><strong>Download URL:</strong> <?php echo htmlspecialchars($result['data']['response']); ?></p>
                                <a href="<?php echo htmlspecialchars($result['data']['response']); ?>" class="btn" download>Download File</a>
                            <?php endif; ?>
                        </div>
                    <?php elseif (($endpoint === 'video_split') && $result['success']): ?>
                        <div class="download-result">
                            <h4><?php echo ucfirst(str_replace('_', ' ', $endpoint)); ?> Successful!</h4>
                            <?php if (isset($result['data']['job_id'])): ?>
                                <p><strong>Job ID:</strong> <?php echo htmlspecialchars($result['data']['job_id']); ?></p>
                            <?php endif; ?>
                            <?php if (isset($result['data']['response']) && is_array($result['data']['response'])): ?>
                                <p><strong>Files:</strong></p>
                                <ul>
                                    <?php foreach ($result['data']['response'] as $index => $fileInfo): ?>
                                        <li>
                                            <?php if (isset($fileInfo['file_url'])): ?>
                                                <a href="<?php echo htmlspecialchars($fileInfo['file_url']); ?>" download>Download File <?php echo $index + 1; ?></a>
                                            <?php endif; ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    <?php elseif ($endpoint === 'media_transcribe' && isset($_POST['srt_format']) && $result['success'] && isset($result['data']['response'])): ?>
                        <?php if (!isset($GLOBALS['transcribe_direct_output']) || $GLOBALS['transcribe_direct_output']): ?>
                            <div class="transcription-result">
                                <h4>Transcription (SRT Format)</h4>
                                <?php if (isset($result['data']['response']['srt_url']) && !empty($result['data']['response']['srt_url'])): ?>
                                    <p><a href="<?php echo htmlspecialchars($result['data']['response']['srt_url']); ?>" download="transcription.srt" class="btn">Download SRT File</a></p>
                                    <p><strong>Direct URL:</strong></p>
                                    <input type="text" style="width: 100%;" readonly value="<?php echo htmlspecialchars($result['data']['response']['srt_url']); ?>">
                                    <p><small>Note: This is a direct URL to the SRT file hosted on cloud storage.</small></p>
                                <?php elseif (isset($result['data']['response']['srt']) && !empty($result['data']['response']['srt'])): ?>
                                    <textarea style="width: 100%; height: 300px; font-family: monospace;"><?php echo htmlspecialchars($result['data']['response']['srt']); ?></textarea>
                                    <?php 
                                    // Create the data URL for download
                                    $dataUrl = "data:text/plain;charset=utf-8," . urlencode($result['data']['response']['srt']);
                                    ?>
                                    <p><a href="<?php echo $dataUrl; ?>" download="transcription.srt" class="btn">Download SRT File</a></p>
                                    <p><strong>Direct URL:</strong></p>
                                    <input type="text" style="width: 100%;" readonly value="<?php echo $dataUrl; ?>">
                                    <p><small>Note: Direct SRT URL not provided by API, using data URL instead.</small></p>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <div class="download-result">
                                <h4>Transcription Complete</h4>
                                <?php if (isset($result['data']['response']['srt_url']) && !empty($result['data']['response']['srt_url'])): ?>
                                    <p><a href="<?php echo htmlspecialchars($result['data']['response']['srt_url']); ?>" download="transcription.srt" class="btn">Download SRT File</a></p>
                                    <p><strong>Direct URL:</strong></p>
                                    <input type="text" style="width: 100%;" readonly value="<?php echo htmlspecialchars($result['data']['response']['srt_url']); ?>">
                                    <p><small>Note: This is a direct URL to the SRT file hosted on cloud storage.</small></p>
                                <?php elseif (isset($result['data']['response']['srt']) && !empty($result['data']['response']['srt'])): ?>
                                    <?php 
                                    // Create the data URL for download
                                    $dataUrl = "data:text/plain;charset=utf-8," . urlencode($result['data']['response']['srt']);
                                    ?>
                                    <p><a href="<?php echo $dataUrl; ?>" download="transcription.srt" class="btn">Download SRT File</a></p>
                                    <p><strong>Direct URL:</strong></p>
                                    <input type="text" style="width: 100%;" readonly value="<?php echo $dataUrl; ?>">
                                    <p><small>Note: Direct SRT URL not provided by API, using data URL instead.</small></p>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    <?php elseif ($endpoint === 'file_upload' && $result['success']): ?>
                        <div class="download-result">
                            <h4>File Upload Successful!</h4>
                            <?php if (isset($result['url'])): ?>
                                <p><strong>File URL:</strong> <a href="<?php echo htmlspecialchars($result['url']); ?>" target="_blank"><?php echo htmlspecialchars($result['url']); ?></a></p>
                                <p><strong>File Name:</strong> <?php echo htmlspecialchars($result['file_name']); ?></p>
                            <?php elseif (isset($result['files']) && is_array($result['files'])): ?>
                                <p><strong>Uploaded Files:</strong></p>
                                <ul>
                                    <?php foreach ($result['files'] as $file): ?>
                                        <li>
                                            <a href="<?php echo htmlspecialchars($file['url']); ?>" target="_blank"><?php echo htmlspecialchars($file['file_name']); ?></a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <pre><?php echo htmlspecialchars(json_encode($result, JSON_PRETTY_PRINT)); ?></pre>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </main>
    </div>
    
    <script src="js/script.js?v=1.1"></script>
    <script>
        function changeLanguage(lang) {
            window.location.href = '?lang=' + lang;
        }
        
        // Pass translations to JavaScript
        <?php echo $languageManager->getJavaScriptTranslations(); ?>
    </script>
</body>
</html>