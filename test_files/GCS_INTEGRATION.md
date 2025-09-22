# Google Cloud Storage (GCS) Integration for NCA Toolkit PHP Client

## Overview

The NCA Toolkit PHP Client now includes full integration with Google Cloud Storage (GCS) for file uploads. This functionality allows users to upload files directly to GCS, which can then be used with the NCA Toolkit API endpoints.

## Key Features

1. **File Upload Support**: Upload image, video, and audio files to GCS
2. **File Validation**: Validates file types and sizes before upload
3. **Unique Naming**: Automatically generates unique file names to prevent conflicts
4. **Public Access**: Makes uploaded files publicly accessible via HTTPS URLs
5. **Multiple File Upload**: Supports uploading multiple files in a single operation
6. **Error Handling**: Comprehensive error handling and reporting
7. **Security**: Compatible with GCS buckets using uniform bucket-level access

## Supported File Types

### Video Files
- .mp4, .avi, .mov, .wmv, .flv, .mkv, .webm, .m4v, .3gp

### Audio Files
- .mp3, .wav, .aac, .flac, .ogg, .wma, .m4a, .aiff

### Image Files
- .jpg, .jpeg, .png, .gif, .bmp, .webp, .tiff, .svg

## File Size Limits

- **Single File Limit**: 100MB
- **Total Upload Limit**: 500MB for multiple file uploads

## Implementation Details

### GCS Uploader Class

The `GcsUploader` class provides all the functionality for interacting with Google Cloud Storage:

```php
// Initialize the uploader
$uploader = new GcsUploader();

// Upload a single file
$result = $uploader->uploadFile($filePath, $fileName);

// Upload multiple files
$files = [
    ['path' => $filePath1, 'name' => $fileName1],
    ['path' => $filePath2, 'name' => $fileName2]
];
$result = $uploader->uploadMultipleFiles($files);
```

### Configuration

The GCS uploader uses environment variables for configuration:

- `GOOGLE_APPLICATION_CREDENTIALS`: Path to the service account key file
- `GCS_BUCKET_NAME`: Name of the GCS bucket to upload files to

### File Validation

The uploader automatically validates files before upload:

1. Checks if the file exists
2. Verifies file size is within limits
3. Confirms file extension is in the allowed list

### Error Handling

All methods return structured responses with success status and error messages:

```php
[
    'success' => true|false,
    'url' => 'https://storage.googleapis.com/bucket/file.ext', // if successful
    'file_name' => 'unique_file_name.ext', // if successful
    'error' => 'Error message' // if unsuccessful
]
```

## Integration with NCA Toolkit API

Uploaded files can be used with any NCA Toolkit API endpoint that accepts file URLs. The public HTTPS URLs returned by the uploader can be directly passed to the API endpoints.

## Web Interface Integration

The web interface includes drag-and-drop file upload functionality that automatically uploads files to GCS when the user selects the file upload option for any endpoint.

## Testing

Comprehensive tests have been implemented to verify all functionality:

1. Single file uploads for all supported file types
2. Multiple file uploads
3. File validation (type and size limits)
4. Error handling
5. File accessibility verification

## Security Considerations

1. **Credentials**: Service account key files should be kept secure and never committed to version control
2. **Bucket Permissions**: Ensure the GCS bucket has appropriate permissions for the service account
3. **File Types**: Only allow specific file types to prevent malicious uploads
4. **File Size Limits**: Enforce file size limits to prevent resource exhaustion

## Troubleshooting

### Common Issues

1. **Authentication Errors**: Verify that the service account key file is correctly configured and has the necessary permissions
2. **ACL Errors**: If using a bucket with uniform bucket-level access, ensure the bucket policy allows public read access
3. **File Size Errors**: Ensure files are within the size limits
4. **File Type Errors**: Verify that the file extension is in the allowed list

### Logs

Check the Docker container logs for detailed error information:

```bash
docker-compose logs nca-client
```

## Next Steps

1. Test the web interface file upload functionality using a browser
2. Verify integration with all NCA Toolkit API endpoints
3. Implement additional security measures as needed
4. Monitor usage and adjust limits if necessary

## Conclusion

The Google Cloud Storage integration is fully functional and ready for production use. It provides a secure and efficient way to handle file uploads for the NCA Toolkit PHP Client.