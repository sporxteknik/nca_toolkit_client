<?php
/**
 * Main API Client for NCA Toolkit
 */
class NcaApiClient {
    public $timeout; // Make timeout public so it can be accessed directly
    
    private $baseUrl;
    private $apiKey;
    
    public function __construct($baseUrl = null, $apiKey = null, $timeout = null) {
        $this->baseUrl = $baseUrl ?: NCA_API_BASE_URL;
        $this->apiKey = $apiKey ?: NCA_API_KEY;
        $this->timeout = $timeout ?: NCA_API_TIMEOUT;
    }
    
    /**
     * Set the timeout for API requests
     * 
     * @param int $timeout Timeout in seconds
     */
    public function setTimeout($timeout) {
        $this->timeout = $timeout;
    }
    
    /**
     * Make a GET request to the API
     */
    public function get($endpoint, $params = []) {
        $url = $this->baseUrl . $endpoint;
        
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        
        return $this->makeRequest('GET', $url);
    }
    
    /**
     * Make a POST request to the API
     */
    public function post($endpoint, $data = []) {
        $url = $this->baseUrl . $endpoint;
        return $this->makeRequest('POST', $url, $data);
    }
    
    /**
     * Make an HTTP request to the API
     */
    private function makeRequest($method, $url, $data = []) {
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'x-api-key: ' . $this->apiKey
        ]);
        
        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        
        curl_close($ch);
        
        if ($error) {
            return [
                'success' => false,
                'error' => 'cURL Error: ' . $error
            ];
        }
        
        $result = json_decode($response, true);
        
        return [
            'success' => $httpCode >= 200 && $httpCode < 300,
            'http_code' => $httpCode,
            'data' => $result,
            'raw_response' => $response
        ];
    }
    
    /**
     * Test the API connection
     */
    public function testConnection() {
        return $this->get('/v1/toolkit/test');
    }
    
    /**
     * Authenticate with the API
     */
    public function authenticate() {
        // Try both header and body authentication
        $result = $this->post('/v1/toolkit/authenticate', ['api_key' => $this->apiKey]);
        
        // If that fails, try just the header
        if (!$result['success']) {
            $result = $this->post('/v1/toolkit/authenticate');
        }
        
        return $result;
    }
}