<?php
/**
 * Video endpoints for NCA Toolkit
 */
class Video {
    private $client;
    
    public function __construct($client) {
        $this->client = $client;
    }
    
    /**
     * Add captions to video
     * 
     * Supports both raw caption text and external SRT file URLs.
     * Also supports color customization for captions.
     * 
     * @param string $videoUrl URL of the video
     * @param array $options Additional options
     *   - captions: URL to an external SRT file (optional)
     *   - settings: Array of caption settings including colors (optional)
     * @return array API response
     */
    public function addCaption($videoUrl, $options = []) {
        $data = [
            'video_url' => $videoUrl
        ];
        
        // Add captions parameter if provided (for external SRT files)
        if (!empty($options['captions'])) {
            $data['captions'] = $options['captions'];
        }
        
        // Add settings if provided
        if (!empty($options['settings'])) {
            $data['settings'] = $options['settings'];
        }
        
        // For caption requests, we might need a longer timeout
        $originalTimeout = $this->client->timeout;
        $this->client->setTimeout(600); // 10 minutes timeout (600 seconds)
        
        $result = $this->client->post('/v1/video/caption', $data);
        
        // Restore original timeout
        $this->client->setTimeout($originalTimeout);
        
        return $result;
    }
    
    /**
     * Concatenate multiple videos
     * 
     * @param array $videoUrls List of video URLs to concatenate
     * @return array API response
     */
    public function concatenate($videoUrls) {
        // Convert simple URLs to objects if needed
        $formattedUrls = [];
        foreach ($videoUrls as $url) {
            if (is_string($url)) {
                // Convert string URL to object format with correct property name
                $formattedUrls[] = ['video_url' => $url];
            } else {
                // Already an object, use as is
                $formattedUrls[] = $url;
            }
        }
        
        $data = [
            'video_urls' => $formattedUrls
        ];
        
        return $this->client->post('/v1/video/concatenate', $data);
    }
    
    /**
     * Extract thumbnail from video
     * 
     * @param string $videoUrl URL of the video
     * @return array API response
     */
    public function extractThumbnail($videoUrl) {
        $data = [
            'video_url' => $videoUrl
        ];
        
        return $this->client->post('/v1/video/thumbnail', $data);
    }
    
    /**
     * Cut segments from video
     * 
     * @param string $videoUrl URL of the video
     * @param array $segments List of segments to cut [{start, end}]
     * @return array API response
     */
    public function cut($videoUrl, $segments) {
        // Convert numeric values to strings
        $cuts = [];
        foreach ($segments as $segment) {
            $cuts[] = [
                'start' => (string)$segment['start'],
                'end' => (string)$segment['end']
            ];
        }
        
        $data = [
            'video_url' => $videoUrl,
            'cuts' => $cuts
        ];
        
        return $this->client->post('/v1/video/cut', $data);
    }
    
    /**
     * Download cut video files
     * 
     * @param array $fileUrls URLs of the cut video files
     * @param string $jobId Job ID for the cut operation
     * @return array Download result
     */
    public function downloadCutVideos($fileUrls, $jobId) {
        // Create a temporary directory for the downloads
        $tempDir = sys_get_temp_dir() . '/video_cut_' . uniqid();
        if (!mkdir($tempDir, 0777, true)) {
            return [
                'success' => false,
                'error' => 'Failed to create temporary directory'
            ];
        }
        
        $downloadedFiles = [];
        $totalSize = 0;
        
        // Download each file
        foreach ($fileUrls as $index => $fileInfo) {
            $fileUrl = $fileInfo['file_url'];
            $filename = "cut_{$jobId}_" . ($index + 1) . ".mp4";
            $localPath = $tempDir . '/' . $filename;
            
            if ($this->downloadFile($fileUrl, $localPath)) {
                $fileSize = filesize($localPath);
                $totalSize += $fileSize;
                $downloadedFiles[] = [
                    'filename' => $filename,
                    'file_size' => $fileSize,
                    'file_path' => $localPath
                ];
            } else {
                // Clean up on failure
                $this->deleteDirectory($tempDir);
                return [
                    'success' => false,
                    'error' => 'Failed to download file: ' . $fileUrl
                ];
            }
        }
        
        return [
            'success' => true,
            'job_id' => $jobId,
            'files' => $downloadedFiles,
            'total_size' => $totalSize,
            'temp_dir' => $tempDir
        ];
    }
    
    /**
     * Split video into segments
     * 
     * @param string $videoUrl URL of the video
     * @param array $segments List of segments to split [{start, end}]
     * @return array API response
     */
    public function split($videoUrl, $segments) {
        // Convert numeric values to strings
        $splits = [];
        foreach ($segments as $segment) {
            $splits[] = [
                'start' => (string)$segment['start'],
                'end' => (string)$segment['end']
            ];
        }
        
        $data = [
            'video_url' => $videoUrl,
            'splits' => $splits
        ];
        
        return $this->client->post('/v1/video/split', $data);
    }
    
    /**
     * Download a file from a URL to a local path
     * 
     * @param string $url URL of the file to download
     * @param string $localPath Local path to save the file
     * @return bool Success
     */
    public function downloadFile($url, $localPath) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $data = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode == 200 && $data !== false) {
            return file_put_contents($localPath, $data) !== false;
        }
        
        return false;
    }
    
    /**
     * Download split video files
     * 
     * @param array $fileUrls URLs of the split video files
     * @param string $jobId Job ID for the split operation
     * @return array Download result
     */
    public function downloadSplitVideos($fileUrls, $jobId) {
        // Create a temporary directory for the downloads
        $tempDir = sys_get_temp_dir() . '/video_split_' . uniqid();
        if (!mkdir($tempDir, 0777, true)) {
            return [
                'success' => false,
                'error' => 'Failed to create temporary directory'
            ];
        }
        
        $downloadedFiles = [];
        $totalSize = 0;
        
        // Download each file
        foreach ($fileUrls as $index => $fileInfo) {
            $fileUrl = $fileInfo['file_url'];
            $filename = "split_{$jobId}_" . ($index + 1) . ".mp4";
            $localPath = $tempDir . '/' . $filename;
            
            if ($this->downloadFile($fileUrl, $localPath)) {
                $fileSize = filesize($localPath);
                $totalSize += $fileSize;
                $downloadedFiles[] = [
                    'filename' => $filename,
                    'file_size' => $fileSize,
                    'file_path' => $localPath
                ];
            } else {
                // Clean up on failure
                $this->deleteDirectory($tempDir);
                return [
                    'success' => false,
                    'error' => 'Failed to download file: ' . $fileUrl
                ];
            }
        }
        
        return [
            'success' => true,
            'job_id' => $jobId,
            'files' => $downloadedFiles,
            'total_size' => $totalSize,
            'temp_dir' => $tempDir
        ];
    }
    
    /**
     * Helper function to delete a directory recursively
     * 
     * @param string $dir Directory path
     * @return bool Success
     */
    private function deleteDirectory($dir) {
        if (!is_dir($dir)) {
            return false;
        }
        
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            if (is_dir($path)) {
                $this->deleteDirectory($path);
            } else {
                unlink($path);
            }
        }
        
        return rmdir($dir);
    }
    
    /**
     * Trim video
     * 
     * @param string $videoUrl URL of the video
     * @param float $start Start time
     * @param float $end End time
     * @return array API response
     */
    public function trim($videoUrl, $start, $end) {
        $data = [
            'video_url' => $videoUrl,
            'start' => (string)$start,
            'end' => (string)$end
        ];
        
        return $this->client->post('/v1/video/trim', $data);
    }
}