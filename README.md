# NCA Toolkit PHP Client with Docker

This PHP client for the NCA Toolkit API is containerized using Docker for easy deployment and testing.

## Prerequisites

- Docker installed on your system
- Docker Compose (optional but recommended)
- NCA Toolkit API key (obtain from the NCA Toolkit dashboard)

## Quick Start with Docker Compose

1. Copy the `.env.example` file to `.env` and update with your actual API key:
   ```bash
   cp .env.example .env
   # Edit .env file to add your NCA_API_KEY
   ```

2. Update the `.env` file with your actual API key:
   ```env
   NCA_API_KEY=your_actual_api_key_here
   ```

3. Build and run the container:
   ```bash
   docker-compose up -d
   ```

4. Access the application in your browser at `http://localhost:8081`

## Quick Start with Docker Only

1. Build the Docker image:
   ```bash
   docker build -t nca-client .
   ```

2. Run the container with your API key:
   ```bash
   docker run -d -p 8081:80 -e NCA_API_KEY=your_actual_api_key_here nca-client
   ```

3. Access the application in your browser at `http://localhost:8081`

## Environment Variables

- `NCA_API_BASE_URL`: Base URL for the NCA Toolkit API (default: https://no-code-architects-toolkit-18628757896.europe-west1.run.app)
- `NCA_API_KEY`: Your API key for the NCA Toolkit (required)
- `NCA_API_TIMEOUT`: API timeout in seconds (default: 30)

## Available Endpoints

The web interface provides access to the following NCA Toolkit API endpoints:

- **Audio**: Concatenate audio files
- **Image**: Convert images to video, take webpage screenshots
- **Media**: Convert media to MP3, download media, transcribe audio/video
- **YouTube**: Direct YouTube video download and info retrieval
- **Video**: Add captions, concatenate videos, extract thumbnails, cut/split/trim videos

## Testing

This project includes comprehensive Playwright tests to verify the functionality of the web interface, including drag and drop functionality.

### Running Tests

To run the Playwright tests:

```bash
# Run all tests
npm test

# Run tests in headed mode (UI visible)
npm run test:ui

# Run tests with debugging
npm run test:debug

# View test report
npm run test:report
```

### Test Coverage

The tests verify:
- Main page loads correctly
- Endpoint selection works
- Drag and drop areas are properly initialized
- File selection via click works
- All endpoints have proper drag and drop functionality

## Test Files

Individual test files are located in the `test_files` directory. These can be run directly with PHP:

```bash
# Run a specific test file
cd test_files
php test_api.php

# Or use the provided test runner scripts
./run_test.sh test_api.php  # On Linux/Mac
run_test.bat test_api.php   # On Windows
```

## Stopping the Container

If you used Docker Compose:
```bash
docker-compose down
```

If you used Docker only:
```bash
docker stop <container_id>
docker rm <container_id>
```

## Development

To develop with live reloading, you can mount your local files into the container:

```bash
docker run -d -p 8081:80 -v $(pwd):/var/www/html -e NCA_API_KEY=your_actual_api_key_here nca-client
```