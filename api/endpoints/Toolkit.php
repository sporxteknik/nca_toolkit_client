<?php
/**
 * Toolkit endpoints for NCA Toolkit
 */
class Toolkit {
    private $client;
    
    public function __construct($client) {
        $this->client = $client;
    }
    
    /**
     * Authenticate with the API
     * 
     * @param string $apiKey API key to validate
     * @return array API response
     */
    public function authenticate($apiKey = null) {
        $data = [];
        
        if ($apiKey) {
            $data['api_key'] = $apiKey;
        }
        
        return $this->client->post('/v1/toolkit/authenticate', $data);
    }
    
    /**
     * Test the API
     * 
     * @return array API response
     */
    public function test() {
        return $this->client->get('/v1/toolkit/test');
    }
    
    /**
     * Get job status
     * 
     * @param string $jobId Job ID to check
     * @return array API response
     */
    public function getJobStatus($jobId) {
        $params = [
            'job_id' => $jobId
        ];
        
        return $this->client->get('/v1/toolkit/job/status', $params);
    }
    
    /**
     * Get statuses of all jobs
     * 
     * @param string $startTime Start time in ISO 8601 format
     * @param string $endTime End time in ISO 8601 format
     * @return array API response
     */
    public function getAllJobsStatus($startTime, $endTime) {
        $params = [
            'start_time' => $startTime,
            'end_time' => $endTime
        ];
        
        return $this->client->get('/v1/toolkit/jobs/status', $params);
    }
}