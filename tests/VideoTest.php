<?php
require_once __DIR__ . '/../api/NcaApiClient.php';
require_once __DIR__ . '/../api/endpoints/Video.php';

class VideoTest {
    private $client;
    private $video;
    
    public function __construct() {
        // Create a client with a test base URL and API key
        $this->client = new NcaApiClient('https://httpbin.org', 'test-key');
        $this->video = new Video($this->client);
    }
    
    public function testAddCaption() {
        // Test that the method exists and can be called
        if (method_exists($this->video, 'addCaption')) {
            echo "✓ addCaption method exists\n";
            return true;
        } else {
            echo "✗ addCaption method missing\n";
            return false;
        }
    }
    
    public function testConcatenate() {
        // Test that the method exists and can be called
        if (method_exists($this->video, 'concatenate')) {
            echo "✓ concatenate method exists\n";
            return true;
        } else {
            echo "✗ concatenate method missing\n";
            return false;
        }
    }
    
    public function testExtractThumbnail() {
        // Test that the method exists and can be called
        if (method_exists($this->video, 'extractThumbnail')) {
            echo "✓ extractThumbnail method exists\n";
            return true;
        } else {
            echo "✗ extractThumbnail method missing\n";
            return false;
        }
    }
    
    public function testCut() {
        // Test that the method exists and can be called
        if (method_exists($this->video, 'cut')) {
            echo "✓ cut method exists\n";
            return true;
        } else {
            echo "✗ cut method missing\n";
            return false;
        }
    }
    
    public function testSplit() {
        // Test that the method exists and can be called
        if (method_exists($this->video, 'split')) {
            echo "✓ split method exists\n";
            return true;
        } else {
            echo "✗ split method missing\n";
            return false;
        }
    }
    
    public function testTrim() {
        // Test that the method exists and can be called
        if (method_exists($this->video, 'trim')) {
            echo "✓ trim method exists\n";
            return true;
        } else {
            echo "✗ trim method missing\n";
            return false;
        }
    }
    
    public function runAllTests() {
        echo "Running Video endpoint tests...\n";
        
        $tests = [
            'testAddCaption',
            'testConcatenate',
            'testExtractThumbnail',
            'testCut',
            'testSplit',
            'testTrim'
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
    $test = new VideoTest();
    $test->runAllTests();
}
?>