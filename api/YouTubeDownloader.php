<?php
/**
 * Direct YouTube download using yt-dlp
 */
class YouTubeDownloader {
    private $timeout;
    
    public function __construct($timeout = 30) {
        $this->timeout = $timeout;
    }
    
    /**
     * Download YouTube video using yt-dlp
     * 
     * @param string $url YouTube video URL
     * @param string $cookies Optional cookies file content
     * @param array $options Additional options for yt-dlp
     * @return array Download result
     */
    public function download($url, $cookies = null, $options = []) {
        // Create a temporary directory for the download
        $tempDir = sys_get_temp_dir() . '/yt_downloads_' . uniqid();
        if (!mkdir($tempDir, 0777, true)) {
            return [
                'success' => false,
                'error' => 'Failed to create temporary directory'
            ];
        }
        
        // Change to the temporary directory
        $oldCwd = getcwd();
        chdir($tempDir);
        
        try {
            // Prepare cookies file if provided
            $cookiesFile = null;
            if ($cookies) {
                $cookiesFile = $tempDir . '/cookies.txt';
                file_put_contents($cookiesFile, $cookies);
            }
            
            // First, try to get basic video information
            $infoCmd = 'yt-dlp --no-check-certificate --print-json --simulate';
            
            // Add cookies if provided
            if ($cookiesFile) {
                $infoCmd .= ' --cookies ' . escapeshellarg($cookiesFile);
            }
            
            // Add URL
            $infoCmd .= ' ' . escapeshellarg($url);
            
            // Get video information
            $infoOutput = [];
            $infoReturnCode = 0;
            exec($infoCmd . ' 2>/dev/null', $infoOutput, $infoReturnCode);
            
            // If we can't get JSON info, try with verbose output for debugging
            if ($infoReturnCode !== 0 || empty($infoOutput)) {
                $verboseCmd = 'yt-dlp --no-check-certificate -v';
                if ($cookiesFile) {
                    $verboseCmd .= ' --cookies ' . escapeshellarg($cookiesFile);
                }
                $verboseCmd .= ' ' . escapeshellarg($url) . ' 2>&1';
                
                $verboseOutput = [];
                $verboseReturnCode = 0;
                exec($verboseCmd, $verboseOutput, $verboseReturnCode);
                
                return [
                    'success' => false,
                    'error' => 'Failed to get video information',
                    'debug_info' => [
                        'info_cmd' => $infoCmd,
                        'info_return_code' => $infoReturnCode,
                        'info_output' => $infoOutput,
                        'verbose_cmd' => $verboseCmd,
                        'verbose_return_code' => $verboseReturnCode,
                        'verbose_output' => $verboseOutput
                    ]
                ];
            }
            
            // Try to parse the JSON output
            $jsonOutput = implode("", $infoOutput);
            $videoInfo = json_decode($jsonOutput, true);
            
            if (!$videoInfo) {
                // Try to find JSON in the output (sometimes there's extra text)
                $jsonStart = strpos($jsonOutput, '{');
                $jsonEnd = strrpos($jsonOutput, '}');
                
                if ($jsonStart !== false && $jsonEnd !== false) {
                    $jsonString = substr($jsonOutput, $jsonStart, $jsonEnd - $jsonStart + 1);
                    $videoInfo = json_decode($jsonString, true);
                }
            }
            
            if (!$videoInfo) {
                return [
                    'success' => false,
                    'error' => 'Failed to parse video information',
                    'raw_output' => $jsonOutput,
                    'info_output' => $infoOutput
                ];
            }
            
            // Get the title for the filename
            $title = isset($videoInfo['title']) ? $videoInfo['title'] : 'video';
            
            // Sanitize filename (remove special characters that might cause issues)
            $sanitizedTitle = preg_replace('/[^a-zA-Z0-9\-_\.]/', '_', $title);
            // Limit filename length to prevent issues
            if (strlen($sanitizedTitle) > 100) {
                $sanitizedTitle = substr($sanitizedTitle, 0, 100);
            }
            
            // Build download command with improved quality selection
            // Try to get 720p but allow some flexibility
            $downloadCmd = 'yt-dlp --no-check-certificate -f "bestvideo[height>=720][height<=720][ext=mp4]+bestaudio[ext=m4a]/bestvideo[height>=700][height<=800][ext=mp4]+bestaudio[ext=m4a]/best[ext=mp4]/best" --merge-output-format mp4';
            
            // Add cookies if provided
            if ($cookiesFile) {
                $downloadCmd .= ' --cookies ' . escapeshellarg($cookiesFile);
            }
            
            // Add re-encode option to ensure audio is properly included
            $downloadCmd .= ' --recode-video mp4';
            
            // Add other options
            foreach ($options as $key => $value) {
                $downloadCmd .= ' --' . escapeshellarg($key) . ' ' . escapeshellarg($value);
            }
            
            // Add output template
            $downloadCmd .= ' --output ' . escapeshellarg($sanitizedTitle . '.%(ext)s');
            
            // Add URL
            $downloadCmd .= ' ' . escapeshellarg($url);
            
            // Execute the download command
            $output = [];
            $returnCode = 0;
            exec($downloadCmd . ' 2>&1', $output, $returnCode);
            
            // Check if download was successful
            if ($returnCode !== 0) {
                return [
                    'success' => false,
                    'error' => 'Download failed',
                    'yt_dlp_output' => $output,
                    'command' => $downloadCmd
                ];
            }
            
            // List all files in the directory to find the downloaded file
            $files = scandir($tempDir);
            $downloadedFiles = array_diff($files, ['.', '..']);
            
            // Look for the final MP4 file (after re-encoding)
            $finalFile = null;
            
            foreach ($downloadedFiles as $file) {
                // Check for re-encoded MP4 files first
                if (preg_match('/\.mp4$/', $file) && !preg_match('/\.(f\d+|part)/', $file)) {
                    $finalFile = $file;
                    break;
                }
            }
            
            // If no MP4 file found, look for any video file
            if (!$finalFile) {
                foreach ($downloadedFiles as $file) {
                    if (preg_match('/\.(mp4|webm|mkv)$/', $file) && !preg_match('/\.(f\d+|part)/', $file)) {
                        $finalFile = $file;
                        break;
                    }
                }
            }
            
            // If we still don't have a file, return error
            if (!$finalFile) {
                return [
                    'success' => false,
                    'error' => 'Downloaded file not found',
                    'files_in_dir' => array_values($downloadedFiles),
                    'yt_dlp_output' => $output,
                    'command' => $downloadCmd
                ];
            }
            
            // Full path to the downloaded file
            $downloadedFile = $tempDir . '/' . $finalFile;
            
            // Get file size
            $fileSize = filesize($downloadedFile);
            
            // Return result with file information
            return [
                'success' => true,
                'title' => $title,
                'filename' => $finalFile,
                'file_size' => $fileSize,
                'temp_dir' => $tempDir,
                'file_path' => $downloadedFile,
                'yt_dlp_output' => $output,
                'video_info' => $videoInfo,
                'all_files' => array_values($downloadedFiles)
            ];
        } catch (Exception $e) {
            // Clean up on error
            chdir($oldCwd);
            $this->deleteDirectory($tempDir);
            
            return [
                'success' => false,
                'error' => 'Exception: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get video information using yt-dlp
     * 
     * @param string $url YouTube video URL
     * @param string $cookies Optional cookies file content
     * @return array Video information
     */
    public function getVideoInfo($url, $cookies = null) {
        // Create a temporary directory
        $tempDir = sys_get_temp_dir() . '/yt_info_' . uniqid();
        if (!mkdir($tempDir, 0777, true)) {
            return [
                'success' => false,
                'error' => 'Failed to create temporary directory'
            ];
        }
        
        // Change to the temporary directory
        $oldCwd = getcwd();
        chdir($tempDir);
        
        try {
            // Prepare cookies file if provided
            $cookiesFile = null;
            if ($cookies) {
                $cookiesFile = $tempDir . '/cookies.txt';
                file_put_contents($cookiesFile, $cookies);
            }
            
            // Build yt-dlp command to get JSON info
            $cmd = 'yt-dlp --no-check-certificate --print-json --simulate';
            
            // Add cookies if provided
            if ($cookiesFile) {
                $cmd .= ' --cookies ' . escapeshellarg($cookiesFile);
            }
            
            // Add URL
            $cmd .= ' ' . escapeshellarg($url);
            
            // Execute the command
            $output = [];
            $returnCode = 0;
            exec($cmd . ' 2>/dev/null', $output, $returnCode);
            
            // Try to parse the JSON output
            $info = null;
            if ($returnCode === 0 && !empty($output)) {
                $jsonOutput = implode("", $output);
                $info = json_decode($jsonOutput, true);
                
                // If direct parsing failed, try to extract JSON from output
                if (!$info) {
                    $jsonStart = strpos($jsonOutput, '{');
                    $jsonEnd = strrpos($jsonOutput, '}');
                    
                    if ($jsonStart !== false && $jsonEnd !== false) {
                        $jsonString = substr($jsonOutput, $jsonStart, $jsonEnd - $jsonStart + 1);
                        $info = json_decode($jsonString, true);
                    }
                }
            }
            
            // Return result
            return [
                'success' => $returnCode === 0,
                'return_code' => $returnCode,
                'output' => $output,
                'info' => $info,
                'raw_json' => !empty($output) ? implode("", $output) : null
            ];
        } finally {
            // Change back to the original directory
            chdir($oldCwd);
            
            // Clean up temporary directory
            $this->deleteDirectory($tempDir);
        }
    }
    
    /**
     * Serve a downloaded file for download
     * 
     * @param string $filePath Path to the file
     * @param string $filename Name for the downloaded file
     * @return bool Success
     */
    public function serveFile($filePath, $filename) {
        if (!file_exists($filePath)) {
            return false;
        }
        
        // Clear any previous output buffers
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        // Set headers for file download
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));
        
        // Read and output the file
        readfile($filePath);
        
        return true;
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
}