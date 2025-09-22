<?php
/**
 * Main test runner for NCA Toolkit PHP Client
 */

// Include all test files
require_once 'NcaApiClientTest.php';
require_once 'VideoTest.php';

class TestRunner {
    public function runAllTests() {
        echo "Running all tests for NCA Toolkit PHP Client...\n\n";
        
        $testClasses = [
            'NcaApiClientTest',
            'VideoTest'
        ];
        
        $passedTests = 0;
        $totalTests = count($testClasses);
        
        foreach ($testClasses as $testClass) {
            echo "==============================\n";
            echo "Running $testClass...\n";
            echo "==============================\n";
            
            $test = new $testClass();
            if ($test->runAllTests()) {
                $passedTests++;
            }
            
            echo "\n";
        }
        
        echo "==============================\n";
        echo "Final Results: $passedTests/$totalTests test suites passed\n";
        echo "==============================\n";
        
        return $passedTests == $totalTests;
    }
}

// Run all tests if this file is executed directly
if (basename(__FILE__) == basename($_SERVER['SCRIPT_NAME'])) {
    $runner = new TestRunner();
    $runner->runAllTests();
}
?>