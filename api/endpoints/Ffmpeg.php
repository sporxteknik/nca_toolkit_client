<?php
/**
 * FFmpeg endpoints for NCA Toolkit
 */
class Ffmpeg {
    private $client;
    
    public function __construct($client) {
        $this->client = $client;
    }
    
    /**
     * Compose media using FFmpeg
     * 
     * @param array $inputs Input media files
     * @param string $command FFmpeg command to execute
     * @return array API response
     */
    public function compose($inputs, $command) {
        $data = [
            'inputs' => $inputs,
            'command' => $command
        ];
        
        return $this->client->post('/v1/ffmpeg/compose', $data);
    }
}