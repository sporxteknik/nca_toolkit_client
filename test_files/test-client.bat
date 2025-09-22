@echo off
REM Test script for NCA Toolkit PHP Client

echo Starting NCA Toolkit PHP Client tests...

REM Start Docker container if not running
echo Checking Docker container status...
docker-compose ps | findstr "Up" >nul
if %errorlevel% neq 0 (
  echo Starting Docker container...
  docker-compose up -d
  timeout /t 10 /nobreak >nul
)

REM Run Playwright tests
echo Running Playwright tests...
npx playwright test --reporter=list

echo Tests completed!