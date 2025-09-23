<?php
require_once 'vendor/autoload.php';

use Google\Cloud\Storage\StorageClient;

class GcsUploader {
    private $storage;
    private $bucketName;
    private $dataBucketName;
    
    public function __construct() {
        $this->bucketName = getenv('GCS_BUCKET_NAME') ?: 'maksimum-nca';
        $this->dataBucketName = getenv('GCS_DATA_BUCKET_NAME') ?: 'maksimum-data';
        
        // Initialize the Storage client
        if (getenv('GOOGLE_APPLICATION_CREDENTIALS')) {
            // Use service account key file
            $this->storage = new StorageClient([
                'keyFilePath' => getenv('GOOGLE_APPLICATION_CREDENTIALS')
            ]);
        } else {
            // Use default credentials (for Cloud Run)
            $this->storage = new StorageClient();
        }
    }
    
    /**
     * Upload a single file to the maksimum-data GCS bucket
     * 
     * @param string $filePath Local file path
     * @param string $fileName Original file name
     * @return array Upload result with URL or error
     */
    public function uploadFileToDataBucket($filePath, $fileName) {
        try {
            // Validate file
            $validation = $this->validateFile($filePath, $fileName);
            if (!$validation['valid']) {
                return [
                    'success' => false,
                    'error' => $validation['error']
                ];
            }
            
            // Generate unique file name with timestamp
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
            $baseName = pathinfo($fileName, PATHINFO_FILENAME);
            $uniqueFileName = $baseName . '_' . time() . '.' . $fileExtension;
            
            // Get the data bucket
            $bucket = $this->storage->bucket($this->dataBucketName);
            
            // Upload the file without predefined ACL to avoid issues with uniform bucket-level access
            $object = $bucket->upload(
                fopen($filePath, 'r'),
                [
                    'name' => $uniqueFileName
                ]
            );
            
            // For buckets with uniform bucket-level access, we can't set ACLs on objects
            // The bucket policy should already make objects publicly readable
            // or the user will need to configure the bucket policy accordingly
            
            // Get the public URL
            $url = $object->gcsUri();
            // Convert gs:// URI to HTTPS URL
            $httpsUrl = str_replace('gs://', 'https://storage.googleapis.com/', $url);
            
            return [
                'success' => true,
                'url' => $httpsUrl,
                'file_name' => $uniqueFileName
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'Upload failed: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Upload multiple files to the maksimum-data GCS bucket
     * 
     * @param array $files Array of file paths and names
     * @return array Upload results
     */
    public function uploadMultipleFilesToDataBucket($files) {
        $results = [];
        $totalSize = 0;
        
        // First validate all files
        foreach ($files as $file) {
            $validation = $this->validateFile($file['path'], $file['name']);
            if (!$validation['valid']) {
                return [
                    'success' => false,
                    'error' => 'File validation failed for ' . $file['name'] . ': ' . $validation['error']
                ];
            }
            $totalSize += filesize($file['path']);
        }
        
        // Check total size limit
        if ($totalSize > 500 * 1024 * 1024) { // 500MB
            return [
                'success' => false,
                'error' => 'Total file size exceeds 500MB limit'
            ];
        }
        
        // Upload each file
        foreach ($files as $file) {
            $result = $this->uploadFileToDataBucket($file['path'], $file['name']);
            if (!$result['success']) {
                return $result; // Return the first error
            }
            $results[] = $result;
        }
        
        // Sort results by file name
        usort($results, function($a, $b) {
            return strcmp($a['file_name'], $b['file_name']);
        });
        
        return [
            'success' => true,
            'files' => $results
        ];
    }
    
    /**
     * Upload a single file to GCS
     * 
     * @param string $filePath Local file path
     * @param string $fileName Original file name
     * @return array Upload result with URL or error
     */
    public function uploadFile($filePath, $fileName) {
        try {
            // Validate file
            $validation = $this->validateFile($filePath, $fileName);
            if (!$validation['valid']) {
                return [
                    'success' => false,
                    'error' => $validation['error']
                ];
            }
            
            // Generate unique file name with timestamp
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
            $baseName = pathinfo($fileName, PATHINFO_FILENAME);
            $uniqueFileName = $baseName . '_' . time() . '.' . $fileExtension;
            
            // Get the bucket
            $bucket = $this->storage->bucket($this->bucketName);
            
            // Upload the file without predefined ACL to avoid issues with uniform bucket-level access
            $object = $bucket->upload(
                fopen($filePath, 'r'),
                [
                    'name' => $uniqueFileName
                ]
            );
            
            // For buckets with uniform bucket-level access, we can't set ACLs on objects
            // The bucket policy should already make objects publicly readable
            // or the user will need to configure the bucket policy accordingly
            
            // Get the public URL
            $url = $object->gcsUri();
            // Convert gs:// URI to HTTPS URL
            $httpsUrl = str_replace('gs://', 'https://storage.googleapis.com/', $url);
            
            return [
                'success' => true,
                'url' => $httpsUrl,
                'file_name' => $uniqueFileName
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'Upload failed: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Upload multiple files to GCS
     * 
     * @param array $files Array of file paths and names
     * @return array Upload results
     */
    public function uploadMultipleFiles($files) {
        $results = [];
        $totalSize = 0;
        
        // First validate all files
        foreach ($files as $file) {
            $validation = $this->validateFile($file['path'], $file['name']);
            if (!$validation['valid']) {
                return [
                    'success' => false,
                    'error' => 'File validation failed for ' . $file['name'] . ': ' . $validation['error']
                ];
            }
            $totalSize += filesize($file['path']);
        }
        
        // Check total size limit
        if ($totalSize > 500 * 1024 * 1024) { // 500MB
            return [
                'success' => false,
                'error' => 'Total file size exceeds 500MB limit'
            ];
        }
        
        // Upload each file
        foreach ($files as $file) {
            $result = $this->uploadFile($file['path'], $file['name']);
            if (!$result['success']) {
                return $result; // Return the first error
            }
            $results[] = $result;
        }
        
        // Sort results by file name
        usort($results, function($a, $b) {
            return strcmp($a['file_name'], $b['file_name']);
        });
        
        return [
            'success' => true,
            'files' => $results
        ];
    }
    
    /**
     * Validate file before upload
     * 
     * @param string $filePath Local file path
     * @param string $fileName Original file name
     * @return array Validation result
     */
    private function validateFile($filePath, $fileName) {
        // Check if file exists
        if (!file_exists($filePath)) {
            return [
                'valid' => false,
                'error' => 'File does not exist'
            ];
        }
        
        // Check file size
        $fileSize = filesize($filePath);
        if ($fileSize > 100 * 1024 * 1024) { // 100MB
            return [
                'valid' => false,
                'error' => 'File size exceeds 100MB limit'
            ];
        }
        
        // Check file extension
        $allowedExtensions = [
            // Video
            'mp4', 'avi', 'mov', 'wmv', 'flv', 'mkv', 'webm', 'm4v', '3gp',
            // Audio
            'mp3', 'wav', 'aac', 'flac', 'ogg', 'wma', 'm4a', 'aiff',
            // Image
            'jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'tiff', 'svg'
        ];
        
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        if (!in_array($fileExtension, $allowedExtensions)) {
            return [
                'valid' => false,
                'error' => 'File type not allowed: ' . $fileExtension
            ];
        }
        
        return [
            'valid' => true
        ];
    }
}
?>