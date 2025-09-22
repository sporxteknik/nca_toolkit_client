# NCA Toolkit PHP Client - Drag and Drop Implementation

## Project Overview
This project implements a PHP client for the NCA Toolkit API with enhanced drag-and-drop file upload functionality. The application is containerized using Docker for easy deployment.

## Features Implemented

### 1. Drag-and-Drop File Upload
- Implemented HTML5 drag-and-drop functionality for all file upload forms
- Visual feedback during drag operations with highlighting effects
- File list display showing selected files with size information
- Support for multiple file selections
- Proper MIME type filtering for different file types:
  - Audio files: audio/*
  - Video files: video/*
  - Image files: image/*

### 2. Enhanced User Interface
- Custom CSS styling for drop areas with visual feedback
- Responsive design that works on all device sizes
- File size formatting for better user experience
- Improved form layouts with clear instructions

### 3. Backend Integration
- Proper handling of multipart form data for file uploads
- Integration with NCA Toolkit API for processing uploaded files
- Temporary file storage and cleanup
- Error handling for file upload failures

### 4. Docker Containerization
- Complete Docker setup with all required dependencies
- Apache web server with PHP 8.1
- Composer for PHP dependency management
- FFmpeg for media processing
- yt-dlp for YouTube downloads

## Technical Implementation Details

### Frontend (JavaScript)
- Dynamic form generation based on selected endpoint
- Event listeners for drag-enter, drag-over, drag-leave, and drop events
- File input handling for both drag-and-drop and traditional click-to-select
- Real-time file list updates with size information
- Visual feedback during drag operations

### Backend (PHP)
- RESTful API client implementation
- Multipart form data handling
- File validation and temporary storage
- Integration with NCA Toolkit endpoints
- Proper error handling and response formatting

### Styling (CSS)
- Custom drop area styles with hover and active states
- File list presentation with clear visual hierarchy
- Responsive design for mobile and desktop views
- Consistent color scheme and typography

## Supported Endpoints
The application supports drag-and-drop file uploads for the following endpoints:
- Audio Concatenate
- Image to Video
- Convert to MP3
- Media Download
- Media Transcribe
- Add Caption
- Video Concatenate
- Extract Thumbnail
- Cut Video
- Split Video
- Trim Video

## Deployment
The application can be easily deployed using Docker:

```bash
# Build the Docker image
docker build -t nca-client .

# Run the container
docker run -d -p 8080:80 nca-client

# Access the application at http://localhost:8080
```

## Future Enhancements
Potential areas for future development:
1. Progress bars for file uploads
2. File type validation on the client-side
3. Batch processing for multiple files
4. Integration with cloud storage services
5. Advanced file preview functionality
6. Upload history and management

## Conclusion
The NCA Toolkit PHP Client now features a robust drag-and-drop file upload system that significantly improves the user experience. The implementation follows modern web development practices and integrates seamlessly with the existing API client functionality.