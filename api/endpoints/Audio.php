<?php
/**
 * Audio endpoints for NCA Toolkit
 */
class Audio {
    private $client;
    
    public function __construct($client) {
        $this->client = $client;
    }
    
    /**
     * Concatenate multiple audio files
     * 
     * @param array $audioUrls List of audio URLs to concatenate
     * @return array API response
     */
    public function concatenate($audioUrls) {
        // Convert simple URLs to objects if needed
        $formattedUrls = [];
        foreach ($audioUrls as $url) {
            if (is_string($url)) {
                // Convert string URL to object format with correct property name
                $formattedUrls[] = ['audio_url' => $url];
            } else {
                // Already an object, use as is
                $formattedUrls[] = $url;
            }
        }
        
        $data = [
            'audio_urls' => $formattedUrls
        ];
        
        return $this->client->post('/v1/audio/concatenate', $data);
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
     * Download concatenated audio file
     * 
     * @param string $jobId Job ID from concatenate operation
     * @param string $filename Local filename to save as
     * @return array Download result
     */
    public function downloadConcatenatedAudio($jobId, $filename = null) {
        // First get job status to get the download URL
        // This is a simplified version - in practice you might want to check job status first
        $downloadUrl = "https://storage.googleapis.com/maksimum-nca/{$jobId}.mp3";
        
        // Create a temporary directory for the download
        $tempDir = sys_get_temp_dir() . '/audio_concat_' . uniqid();
        if (!mkdir($tempDir, 0777, true)) {
            return [
                'success' => false,
                'error' => 'Failed to create temporary directory'
            ];
        }
        
        // Set default filename if not provided
        if (!$filename) {
            $filename = "concatenated_audio_{$jobId}.mp3";
        }
        
        $localPath = $tempDir . '/' . $filename;
        
        // Download the file
        if ($this->downloadFile($downloadUrl, $localPath)) {
            return [
                'success' => true,
                'filename' => $filename,
                'file_size' => filesize($localPath),
                'temp_dir' => $tempDir,
                'file_path' => $localPath
            ];
        } else {
            // Clean up on failure
            rmdir($tempDir);
            return [
                'success' => false,
                'error' => 'Failed to download file'
            ];
        }
    }
}