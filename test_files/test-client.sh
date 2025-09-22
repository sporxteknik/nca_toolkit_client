#!/bin/bash
# Test script for NCA Toolkit PHP Client

echo "Starting NCA Toolkit PHP Client tests..."

# Start Docker container if not running
echo "Checking Docker container status..."
if ! docker-compose ps | grep -q "Up"; then
  echo "Starting Docker container..."
  docker-compose up -d
  sleep 10 # Wait for container to start
fi

# Run Playwright tests
echo "Running Playwright tests..."
npx playwright test --reporter=list

echo "Tests completed!"