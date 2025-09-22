<?php
require_once __DIR__ . '/../api/NcaApiClient.php';

class NcaApiClientTest {
    private $client;
    
    public function __construct() {
        // Create a client with a test base URL and API key
        $this->client = new NcaApiClient('https://httpbin.org', 'test-key');
    }
    
    public function testConstructor() {
        // Test that the client is properly initialized
        if ($this->client) {
            echo "✓ Constructor test passed\n";
            return true;
        } else {
            echo "✗ Constructor test failed\n";
            return false;
        }
    }
    
    public function testGetRequest() {
        // Test a simple GET request
        $result = $this->client->get('/get');
        
        if ($result['success'] && $result['http_code'] == 200) {
            echo "✓ GET request test passed\n";
            return true;
        } else {
            echo "✗ GET request test failed\n";
            return false;
        }
    }
    
    public function testPostRequest() {
        // Test a simple POST request
        $result = $this->client->post('/post', ['test' => 'data']);
        
        if ($result['success'] && $result['http_code'] == 200) {
            echo "✓ POST request test passed\n";
            return true;
        } else {
            echo "✗ POST request test failed\n";
            return false;
        }
    }
    
    public function testTimeoutSetting() {
        // Test timeout setting
        $originalTimeout = $this->client->timeout;
        $this->client->setTimeout(10);
        
        if ($this->client->timeout == 10) {
            // Reset to original
            $this->client->setTimeout($originalTimeout);
            echo "✓ Timeout setting test passed\n";
            return true;
        } else {
            echo "✗ Timeout setting test failed\n";
            return false;
        }
    }
    
    public function runAllTests() {
        echo "Running NcaApiClient tests...\n";
        
        $tests = [
            'testConstructor',
            'testGetRequest',
            'testPostRequest',
            'testTimeoutSetting'
        ];
        
        $passed = 0;
        $total = count($tests);
        
        foreach ($tests as $test) {
            if ($this->$test()) {
                $passed++;
            }
        }
        
        echo "\nResults: $passed/$total tests passed\n";
        return $passed == $total;
    }
}

// Run the tests if this file is executed directly
if (basename(__FILE__) == basename($_SERVER['SCRIPT_NAME'])) {
    $test = new NcaApiClientTest();
    $test->runAllTests();
}
?>