<?php
/**
 * S3 endpoints for NCA Toolkit
 */
class S3 {
    private $client;
    
    public function __construct($client) {
        $this->client = $client;
    }
    
    /**
     * Upload file to S3
     * 
     * @param string $fileUrl URL of the file to upload
     * @param string $bucket S3 bucket name
     * @param string $key S3 key
     * @param array $options Additional options
     * @return array API response
     */
    public function upload($fileUrl, $bucket, $key, $options = []) {
        $data = [
            'file_url' => $fileUrl,
            'bucket' => $bucket,
            'key' => $key
        ];
        
        if (!empty($options)) {
            $data = array_merge($data, $options);
        }
        
        return $this->client->post('/v1/s3/upload', $data);
    }
}