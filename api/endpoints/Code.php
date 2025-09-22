<?php
/**
 * Code endpoints for NCA Toolkit
 */
class Code {
    private $client;
    
    public function __construct($client) {
        $this->client = $client;
    }
    
    /**
     * Execute Python code remotely
     * 
     * @param string $code Python code to execute
     * @param array $files Optional files to include
     * @return array API response
     */
    public function executePython($code, $files = []) {
        $data = [
            'code' => $code
        ];
        
        if (!empty($files)) {
            $data['files'] = $files;
        }
        
        return $this->client->post('/v1/code/execute/python', $data);
    }
}