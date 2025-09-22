@echo off
REM Test runner script for NCA Toolkit PHP Client

REM Check if a test file was provided
if "%~1"=="" (
    echo Usage: %0 ^<test_file.php^>
    echo Example: %0 test_api.php
    echo Available test files:
    dir /b test_*.php
    exit /b 1
)

REM Run the specified test file
php "%~1"