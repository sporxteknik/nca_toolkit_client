<?php
/**
 * Media endpoints for NCA Toolkit
 */
class Media {
    private $client;
    
    public function __construct($client) {
        $this->client = $client;
    }
    
    /**
     * Convert media from one format to another
     * 
     * @param string $mediaUrl URL of the media to convert
     * @param string $outputFormat Desired output format
     * @param array $options Conversion options
     * @return array API response
     */
    public function convert($mediaUrl, $outputFormat, $options = []) {
        $data = [
            'media_url' => $mediaUrl,
            'output_format' => $outputFormat
        ];
        
        if (!empty($options)) {
            $data = array_merge($data, $options);
        }
        
        return $this->client->post('/v1/media/convert', $data);
    }
    
    /**
     * Convert media to MP3
     * 
     * @param string $mediaUrl URL of the media to convert
     * @return array API response
     */
    public function convertToMp3($mediaUrl) {
        $data = [
            'media_url' => $mediaUrl
        ];
        
        return $this->client->post('/v1/media/convert/mp3', $data);
    }
    
    /**
     * Download media from URL
     * 
     * @param string $mediaUrl URL of the media to download
     * @param string $cookies Optional cookies for authentication
     * @return array API response
     */
    public function download($mediaUrl, $cookies = null) {
        $data = [
            'media_url' => $mediaUrl
        ];
        
        if ($cookies) {
            $data['cookies'] = $cookies;
        }
        
        return $this->client->post('/v1/BETA/media/download', $data);
    }
    
    /**
     * Provide feedback on media
     * 
     * @param string $mediaUrl URL of the media
     * @param string $feedback Feedback text
     * @return array API response
     */
    public function feedback($mediaUrl, $feedback) {
        $data = [
            'media_url' => $mediaUrl,
            'feedback' => $feedback
        ];
        
        return $this->client->post('/v1/media/feedback', $data);
    }
    
    /**
     * Transcribe media
     * 
     * @param string $mediaUrl URL of the media to transcribe
     * @param bool $translate Whether to translate the transcription
     * @param array $options Additional options for transcription
     * @return array API response
     */
    public function transcribe($mediaUrl, $translate = false, $options = []) {
        $data = [
            'media_url' => $mediaUrl
        ];
        
        // Only include translate parameter if it's true
        if ($translate) {
            $data['translate'] = $translate;
        }
        
        // Add additional options
        if (!empty($options)) {
            $data = array_merge($data, $options);
        }
        
        return $this->client->post('/v1/media/transcribe', $data);
    }
    
    /**
     * Transcribe media and get SRT format with segments
     * 
     * @param string $mediaUrl URL of the media to transcribe
     * @param bool $translate Whether to translate the transcription
     * @return array API response
     */
    public function transcribeToSrt($mediaUrl, $translate = false) {
        $options = [
            'task' => 'transcribe',
            'include_text' => false,
            'include_srt' => true,
            'include_segments' => false,
            'word_timestamps' => false
        ];
        
        return $this->transcribe($mediaUrl, $translate, $options);
    }
    
    /**
     * Convert transcription text to SRT format
     * 
     * @param string $text Transcription text
     * @param int $duration Video duration in seconds
     * @return string SRT formatted text
     */
    public function textToSrt($text, $duration) {
        // This is a simple implementation that creates one subtitle for the entire text
        // In a real implementation, you would want to split the text into smaller chunks
        // and calculate timestamps for each chunk
        
        $srt = "1\n";
        $srt .= "00:00:00,000 --> " . $this->secondsToHms($duration) . ",000\n";
        $srt .= $text . "\n\n";
        
        return $srt;
    }
    
    /**
     * Convert seconds to HH:MM:SS,mmm format
     * 
     * @param int $seconds Seconds
     * @return string Time in HH:MM:SS,mmm format
     */
    private function secondsToHms($seconds) {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $secs = $seconds % 60;
        
        return sprintf("%02d:%02d:%02d", $hours, $minutes, $secs);
    }
    
    /**
     * Detect silence in media
     * 
     * @param string $mediaUrl URL of the media
     * @return array API response
     */
    public function detectSilence($mediaUrl) {
        $data = [
            'media_url' => $mediaUrl
        ];
        
        return $this->client->post('/v1/media/silence', $data);
    }
    
    /**
     * Get media metadata
     * 
     * @param string $mediaUrl URL of the media
     * @return array API response
     */
    public function getMetadata($mediaUrl) {
        $data = [
            'media_url' => $mediaUrl
        ];
        
        return $this->client->post('/v1/media/metadata', $data);
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
}