<?php
/**
 * Image endpoints for NCA Toolkit
 */
class Image {
    private $client;
    
    public function __construct($client) {
        $this->client = $client;
    }
    
    /**
     * Convert image to video
     * 
     * @param string $imageUrl URL of the image to convert
     * @return array API response
     */
    public function convertToVideo($imageUrl) {
        $data = [
            'image_url' => $imageUrl
        ];
        
        return $this->client->post('/v1/image/convert/video', $data);
    }
    
    /**
     * Take screenshot of webpage
     * 
     * @param string $url URL of the webpage
     * @param array $options Screenshot options
     * @return array API response
     */
    public function screenshotWebpage($url, $options = []) {
        $data = [
            'url' => $url
        ];
        
        if (!empty($options)) {
            $data = array_merge($data, $options);
        }
        
        return $this->client->post('/v1/image/screenshot/webpage', $data);
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