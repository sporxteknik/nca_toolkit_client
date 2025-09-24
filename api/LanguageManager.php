<?php

class LanguageManager {
    private $translations;
    private $currentLanguage;
    
    public function __construct() {
        // Define translations for both languages
        $this->translations = [
            'en' => [
                // Header
                'app_title' => 'NCA Toolkit PHP Client',
                'app_description' => 'Access all No-Code Architects Toolkit API endpoints',
                'select_endpoint' => 'Select Endpoint:',
                'choose_endpoint' => '-- Choose an endpoint --',
                
                // Audio endpoints
                'audio_concatenate' => 'Audio Concatenate',
                
                // Image endpoints
                'image_convert_video' => 'Image to Video',
                'image_screenshot_webpage' => 'Webpage Screenshot',
                
                // Media endpoints
                'media_convert_mp3' => 'Convert to MP3',
                'media_download' => 'Media Download',
                'media_transcribe' => 'Media Transcribe',
                
                // YouTube endpoints
                'youtube_download' => 'YouTube Download',
                'youtube_info' => 'YouTube Video Info',
                
                // Video endpoints
                'video_caption' => 'Add Caption',
                'video_concatenate' => 'Video Concatenate',
                'video_thumbnail' => 'Extract Thumbnail',
                'video_cut' => 'Cut Video',
                'video_split' => 'Split Video',
                'video_trim' => 'Trim Video',
                
                // File Upload endpoints
                'file_upload' => 'Upload to GCP Storage',
                
                // Loading text
                'processing_request' => 'Processing your request...',
                
                // Form labels and placeholders
                'audio_urls' => 'Audio URLs (one per line):',
                'audio_urls_placeholder' => "https://example.com/audio1.mp3\nhttps://example.com/audio2.mp3",
                'or_upload_audio_files' => 'Or upload audio files:',
                'drag_drop_audio' => 'Drag & drop audio files here or click to select files',
                'concatenate_audio' => 'Concatenate Audio',
                
                'image_url' => 'Image URL:',
                'image_url_placeholder' => 'https://example.com/image.jpg',
                'or_upload_image_file' => 'Or upload an image file:',
                'drag_drop_image' => 'Drag & drop an image file here or click to select file',
                'convert_to_video' => 'Convert to Video',
                
                'webpage_url' => 'Webpage URL:',
                'webpage_url_placeholder' => 'https://example.com',
                'viewport_width' => 'Viewport Width (optional):',
                'viewport_height' => 'Viewport Height (optional):',
                'take_screenshot' => 'Take Screenshot',
                
                'media_url' => 'Media URL:',
                'media_url_placeholder' => 'https://example.com/audio.wav',
                'or_upload_media_file' => 'Or upload a media file:',
                'drag_drop_media' => 'Drag & drop a media file here or click to select file',
                'convert_to_mp3' => 'Convert to MP3',
                
                'drag_drop_download' => 'Drag & drop a media file here or click to select file',
                'cookies_optional' => 'Cookies (optional):',
                'cookies_placeholder' => 'Paste cookies here for authenticated downloads (e.g., YouTube)',
                'download_media' => 'Download Media',
                
                'drag_drop_transcribe' => 'Drag & drop a media file here or click to select file',
                'get_segmented_srt' => 'Get segmented SRT format',
                'show_direct_output' => 'Show direct output (uncheck to download SRT file)',
                'transcribe_media' => 'Transcribe Media',
                
                'youtube_url' => 'YouTube URL:',
                'youtube_url_placeholder' => 'https://www.youtube.com/watch?v=...',
                'youtube_cookies_placeholder' => 'Paste cookies here for authenticated downloads',
                'download_youtube' => 'Download YouTube Video',
                
                'youtube_info_url_placeholder' => 'https://www.youtube.com/watch?v=...',
                'youtube_info_cookies_placeholder' => 'Paste cookies here for authenticated access',
                'get_youtube_info' => 'Get YouTube Video Info',
                
                'video_url' => 'Video URL:',
                'video_url_placeholder' => 'https://example.com/video.mp4',
                'or_upload_video_file' => 'Or upload a video file:',
                'drag_drop_caption' => 'Drag & drop a video file here or click to select file',
                'external_srt_url' => 'External SRT File URL (optional):',
                'srt_url_placeholder' => 'https://example.com/subtitles.srt',
                'external_srt_note' => 'If provided, this SRT file will be used instead of raw caption text',
                'caption_text' => 'Caption Text (Note: This may be ignored by the API if SRT URL is provided):',
                'caption_position' => 'Caption Position:',
                'font_size' => 'Font Size:',
                'font_family' => 'Font Family:',
                'caption_style' => 'Caption Style:',
                'line_color' => 'Line Color (hex):',
                'outline_color' => 'Outline Color (hex):',
                'word_color' => 'Word Color (hex, for karaoke style):',
                'add_caption' => 'Add Caption',
                
                'video_urls' => 'Video URLs (one per line):',
                'video_urls_placeholder' => "https://example.com/video1.mp4\nhttps://example.com/video2.mp4",
                'or_upload_video_files' => 'Or upload video files:',
                'drag_drop_video' => 'Drag & drop video files here or click to select files',
                'concatenate_videos' => 'Concatenate Videos',
                
                'drag_drop_thumbnail' => 'Drag & drop a video file here or click to select file',
                'timestamp_seconds' => 'Timestamp (seconds):',
                'extract_thumbnail' => 'Extract Thumbnail',
                
                'drag_drop_cut' => 'Drag & drop a video file here or click to select file',
                'segments_json' => 'Segments (JSON format):',
                'segments_placeholder' => '[{"start": 0, "end": 10}, {"start": 20, "end": 30}]',
                'cut_video' => 'Cut Video',
                
                'drag_drop_split' => 'Drag & drop a video file here or click to select file',
                'split_segments_placeholder' => '[{"start": 0, "end": 10}, {"start": 10, "end": 20}, {"start": 20, "end": 30}]',
                'split_video' => 'Split Video',
                
                'drag_drop_trim' => 'Drag & drop a video file here or click to select file',
                'start_time' => 'Start Time (seconds):',
                'end_time' => 'End Time (seconds):',
                'trim_video' => 'Trim Video',
                
                'upload_files_to_bucket' => 'Upload files to maksimum-data bucket:',
                'drag_drop_upload' => 'Drag & drop files here or click to select files',
                'upload_files' => 'Upload Files',
                
                // Documentation section
                'show_documentation' => 'Show Documentation',
                'hide_documentation' => 'Hide Documentation',
                
                // Audio documentation
                'about_audio_concatenate' => 'About Audio Concatenate',
                'audio_concatenate_desc' => 'This endpoint combines multiple audio files into a single audio file. The files are concatenated in the order they are provided.',
                'audio_parameters' => 'Parameters',
                'audio_urls_param' => 'Audio URLs: A list of URLs to audio files you want to concatenate. Enter one URL per line.',
                'audio_upload_param' => 'Upload Audio Files: Alternatively, you can upload audio files directly from your computer. You can select multiple files at once.',
                'audio_formats' => 'Supported Formats',
                'audio_formats_desc' => 'The endpoint supports common audio formats including MP3, WAV, AAC, and more. All files should be in the same format for best results.',
                'audio_tips' => 'Usage Tips',
                'audio_tips_1' => 'Files are processed in the order they appear in the list',
                'audio_tips_2' => 'All files should have the same audio properties (sample rate, bit depth) for optimal results',
                'audio_tips_3' => 'Large files may take longer to process',
                
                // Image documentation
                'about_image_convert_video' => 'About Image to Video',
                'image_convert_video_desc' => 'This endpoint converts a static image into a video file. The resulting video will display the image for a specified duration.',
                'image_parameters' => 'Parameters',
                'image_url_param' => 'Image URL: URL to the image you want to convert to video',
                'image_upload_param' => 'Upload Image File: Alternatively, you can upload an image file directly from your computer',
                'image_formats' => 'Supported Formats',
                'image_formats_desc' => 'Supports common image formats including JPG, PNG, GIF, and more. The output video is typically in MP4 format.',
                'image_tips' => 'Usage Tips',
                'image_tips_1' => 'Higher resolution images will result in higher quality videos',
                'image_tips_2' => 'The default video duration is typically 5 seconds',
                'image_tips_3' => 'For best results, use images with a 16:9 aspect ratio',
                
                // Webpage screenshot documentation
                'about_webpage_screenshot' => 'About Webpage Screenshot',
                'webpage_screenshot_desc' => 'This endpoint captures a screenshot of any webpage. You can specify the viewport dimensions to control the screenshot size.',
                'webpage_parameters' => 'Parameters',
                'webpage_url_param' => 'Webpage URL: The URL of the webpage you want to capture',
                'viewport_width_param' => 'Viewport Width: Width of the browser viewport in pixels (optional, default is 1920)',
                'viewport_height_param' => 'Viewport Height: Height of the browser viewport in pixels (optional, default is 1080)',
                'webpage_formats' => 'Supported Formats',
                'webpage_formats_desc' => 'The screenshot is captured in PNG format, which provides high quality and supports transparency.',
                'webpage_tips' => 'Usage Tips',
                'webpage_tips_1' => 'Make sure the URL includes the protocol (http:// or https://)',
                'webpage_tips_2' => 'Larger viewport dimensions may increase processing time',
                'webpage_tips_3' => 'Websites with heavy JavaScript may take longer to render completely',
                'webpage_tips_4' => 'Some websites may block automated screenshot capture',
                
                // Media convert MP3 documentation
                'about_media_convert_mp3' => 'About Convert to MP3',
                'media_convert_mp3_desc' => 'This endpoint converts audio and video files to MP3 format. It\'s useful for extracting audio from videos or converting between audio formats.',
                'media_convert_mp3_parameters' => 'Parameters',
                'media_url_param' => 'Media URL: URL to the media file you want to convert to MP3',
                'media_upload_param' => 'Upload Media File: Alternatively, you can upload a media file directly from your computer',
                'media_convert_mp3_formats' => 'Supported Formats',
                'media_convert_mp3_formats_desc' => 'Supports a wide range of input formats including WAV, AAC, FLAC, MP4, MOV, AVI, and more. The output is always in MP3 format.',
                'media_convert_mp3_tips' => 'Usage Tips',
                'media_convert_mp3_tips_1' => 'For video files, only the audio track will be extracted and converted',
                'media_convert_mp3_tips_2' => 'MP3 bitrate is typically set to 128 kbps by default',
                'media_convert_mp3_tips_3' => 'Larger files may take longer to process',
                'media_convert_mp3_tips_4' => 'Quality may vary depending on the source file',
                
                // Media download documentation
                'about_media_download' => 'About Media Download',
                'media_download_desc' => 'This endpoint downloads media files from URLs. It supports various protocols and can handle authenticated downloads with cookies.',
                'media_download_parameters' => 'Parameters',
                'media_download_url_param' => 'Media URL: URL to the media file you want to download',
                'media_download_upload_param' => 'Upload Media File: Alternatively, you can upload a media file directly from your computer',
                'media_download_cookies_param' => 'Cookies: Optional cookies for authenticated downloads (e.g., from YouTube or other platforms)',
                'media_download_formats' => 'Supported Formats',
                'media_download_formats_desc' => 'Supports downloading of various media formats including MP4, MOV, AVI, MKV, MP3, WAV, and more.',
                'media_download_tips' => 'Usage Tips',
                'media_download_tips_1' => 'For platforms that require authentication, paste the necessary cookies',
                'media_download_tips_2' => 'Large files may take longer to download and process',
                'media_download_tips_3' => 'Some platforms may have rate limiting or blocking mechanisms',
                'media_download_tips_4' => 'Make sure you have the right to download the content',
                
                // Media transcribe documentation
                'about_media_transcribe' => 'About Media Transcribe',
                'media_transcribe_desc' => 'This endpoint transcribes audio and video content into text. It can generate both plain text transcriptions and SRT subtitle files.',
                'media_transcribe_parameters' => 'Parameters',
                'media_transcribe_url_param' => 'Media URL: URL to the media file you want to transcribe',
                'media_transcribe_upload_param' => 'Upload Media File: Alternatively, you can upload a media file directly from your computer',
                'media_transcribe_srt_param' => 'Get segmented SRT format: When checked, generates an SRT subtitle file with timestamps',
                'media_transcribe_output_param' => 'Show direct output: When checked, displays the transcription in the browser; when unchecked, provides a download link',
                'media_transcribe_formats' => 'Supported Formats',
                'media_transcribe_formats_desc' => 'Supports a wide range of audio and video formats including MP3, WAV, MP4, MOV, AVI, and more.',
                'media_transcribe_tips' => 'Usage Tips',
                'media_transcribe_tips_1' => 'Accuracy depends on audio quality and clarity',
                'media_transcribe_tips_2' => 'Longer files will take more time to process',
                'media_transcribe_tips_3' => 'SRT format is useful for creating subtitles for videos',
                'media_transcribe_tips_4' => 'For best results, use clear audio with minimal background noise',
                
                // YouTube download documentation
                'about_youtube_download' => 'About Direct YouTube Download',
                'youtube_download_desc' => 'This endpoint downloads videos directly from YouTube. It supports various video qualities and can handle authenticated downloads with cookies.',
                'youtube_download_parameters' => 'Parameters',
                'youtube_download_url_param' => 'YouTube URL: The URL of the YouTube video you want to download (required)',
                'youtube_download_cookies_param' => 'Cookies: Optional cookies for authenticated downloads or accessing restricted content',
                'youtube_download_formats' => 'Supported Formats',
                'youtube_download_formats_desc' => 'Downloads videos in MP4 format with the best available quality. Audio is included in the video file.',
                'youtube_download_tips' => 'Usage Tips',
                'youtube_download_tips_1' => 'Make sure you have the right to download the content',
                'youtube_download_tips_2' => 'For age-restricted or private videos, you may need to provide cookies',
                'youtube_download_tips_3' => 'Larger videos may take longer to download and process',
                'youtube_download_tips_4' => 'YouTube may have rate limiting or blocking mechanisms for automated downloads',
                
                // YouTube info documentation
                'about_youtube_info' => 'About Get YouTube Video Info',
                'youtube_info_desc' => 'This endpoint retrieves detailed information about a YouTube video without downloading it. It provides metadata such as title, description, duration, and available formats.',
                'youtube_info_parameters' => 'Parameters',
                'youtube_info_url_param' => 'YouTube URL: The URL of the YouTube video you want information about (required)',
                'youtube_info_cookies_param' => 'Cookies: Optional cookies for accessing restricted or private content',
                'youtube_info_provided' => 'Information Provided',
                'youtube_info_provided_desc' => 'Returns comprehensive metadata including:',
                'youtube_info_provided_1' => 'Video title and description',
                'youtube_info_provided_2' => 'Uploader/channel information',
                'youtube_info_provided_3' => 'Duration and upload date',
                'youtube_info_provided_4' => 'Available formats and quality options',
                'youtube_info_provided_5' => 'View count and engagement metrics',
                'youtube_info_tips' => 'Usage Tips',
                'youtube_info_tips_1' => 'Useful for previewing video information before downloading',
                'youtube_info_tips_2' => 'For age-restricted or private videos, you may need to provide cookies',
                'youtube_info_tips_3' => 'No video content is downloaded, only metadata',
                'youtube_info_tips_4' => 'Information is retrieved quickly without processing the actual video file',
                
                // Video caption documentation
                'about_video_caption' => 'About Add Caption',
                'video_caption_desc' => 'This endpoint adds captions or subtitles to video files. You can use either an external SRT file or specify caption text directly.',
                'video_caption_parameters' => 'Parameters',
                'video_caption_url_param' => 'Video URL: URL to the video file you want to add captions to',
                'video_caption_upload_param' => 'Upload Video File: Alternatively, you can upload a video file directly from your computer',
                'video_caption_srt_url_param' => 'External SRT File URL: URL to an SRT subtitle file (optional, takes precedence over caption text)',
                'video_caption_text_param' => 'Caption Text: Direct caption text (may be ignored if SRT URL is provided)',
                'video_caption_position_param' => 'Caption Position: Where to place the captions on the video',
                'video_caption_font_size_param' => 'Font Size: Size of the caption text in points',
                'video_caption_font_family_param' => 'Font Family: Font to use for the captions (e.g., Arial, Times New Roman)',
                'video_caption_style_param' => 'Caption Style: Special styling options like karaoke or highlight effects',
                'video_caption_line_color_param' => 'Line Color: Color of the caption text in hex format (e.g., #FFFFFF for white)',
                'video_caption_outline_color_param' => 'Outline Color: Color of the text outline in hex format',
                'video_caption_word_color_param' => 'Word Color: Color for individual words in karaoke style',
                'video_caption_formats' => 'Supported Formats',
                'video_caption_formats_desc' => 'Supports common video formats including MP4, MOV, AVI, and more. SRT files should be in standard subtitle format.',
                'video_caption_tips' => 'Usage Tips',
                'video_caption_tips_1' => 'For best results, use high-contrast colors for visibility',
                'video_caption_tips_2' => 'External SRT files take precedence over direct caption text',
                'video_caption_tips_3' => 'Karaoke style is best used with word-by-word SRT files',
                'video_caption_tips_4' => 'Font sizes between 12-24 work well for most videos',
                
                // Video concatenate documentation
                'about_video_concatenate' => 'About Video Concatenate',
                'video_concatenate_desc' => 'This endpoint combines multiple video files into a single video. The videos are concatenated in the order they are provided.',
                'video_concatenate_parameters' => 'Parameters',
                'video_concatenate_urls_param' => 'Video URLs: A list of URLs to video files you want to concatenate. Enter one URL per line.',
                'video_concatenate_upload_param' => 'Upload Video Files: Alternatively, you can upload video files directly from your computer. You can select multiple files at once.',
                'video_concatenate_formats' => 'Supported Formats',
                'video_concatenate_formats_desc' => 'Supports common video formats including MP4, MOV, AVI, and more. All videos should have similar properties for best results.',
                'video_concatenate_tips' => 'Usage Tips',
                'video_concatenate_tips_1' => 'Videos are processed in the order they appear in the list',
                'video_concatenate_tips_2' => 'For best results, all videos should have the same resolution, frame rate, and codec',
                'video_concatenate_tips_3' => 'Large videos may take longer to process',
                'video_concatenate_tips_4' => 'The output video will have the properties of the first video in the sequence',
                
                // Video thumbnail documentation
                'about_video_thumbnail' => 'About Extract Thumbnail',
                'video_thumbnail_desc' => 'This endpoint extracts a single frame from a video file and saves it as an image. You can specify the exact time to extract the frame from.',
                'video_thumbnail_parameters' => 'Parameters',
                'video_thumbnail_url_param' => 'Video URL: URL to the video file you want to extract a thumbnail from',
                'video_thumbnail_upload_param' => 'Upload Video File: Alternatively, you can upload a video file directly from your computer',
                'video_thumbnail_timestamp_param' => 'Timestamp: The time in seconds from which to extract the frame (optional, defaults to middle of video)',
                'video_thumbnail_formats' => 'Supported Formats',
                'video_thumbnail_formats_desc' => 'Supports common video formats including MP4, MOV, AVI, and more. The output thumbnail is typically in JPG or PNG format.',
                'video_thumbnail_tips' => 'Usage Tips',
                'video_thumbnail_tips_1' => 'If no timestamp is provided, a frame from the middle of the video is extracted',
                'video_thumbnail_tips_2' => 'For best results, choose a timestamp where there is clear, representative content',
                'video_thumbnail_tips_3' => 'Higher resolution videos will produce higher quality thumbnails',
                'video_thumbnail_tips_4' => 'Fractional seconds (e.g., 10.5) are supported for precise timing',
                
                // Video cut documentation
                'about_video_cut' => 'About Cut Video',
                'video_cut_desc' => 'This endpoint removes specified segments from a video file, keeping only the parts you want. It\'s useful for editing out unwanted sections.',
                'video_cut_parameters' => 'Parameters',
                'video_cut_url_param' => 'Video URL: URL to the video file you want to cut',
                'video_cut_upload_param' => 'Upload Video File: Alternatively, you can upload a video file directly from your computer',
                'video_cut_segments_param' => 'Segments: JSON array specifying which parts to keep. Each segment has a start and end time in seconds.',
                'video_cut_formats' => 'Supported Formats',
                'video_cut_formats_desc' => 'Supports common video formats including MP4, MOV, AVI, and more.',
                'video_cut_tips' => 'Usage Tips',
                'video_cut_tips_1' => 'Specify segments to keep, not segments to remove',
                'video_cut_tips_2' => 'Segments must be in JSON format with "start" and "end" properties',
                'video_cut_tips_3' => 'Time values are in seconds and can include decimals (e.g., 10.5)',
                'video_cut_tips_4' => 'Multiple segments can be specified to keep non-consecutive parts',
                'video_cut_tips_5' => 'The output will be multiple video files, one for each segment',
                
                // Video split documentation
                'about_video_split' => 'About Split Video',
                'video_split_desc' => 'This endpoint splits a video file into multiple separate video files based on specified time segments.',
                'video_split_parameters' => 'Parameters',
                'video_split_url_param' => 'Video URL: URL to the video file you want to split',
                'video_split_upload_param' => 'Upload Video File: Alternatively, you can upload a video file directly from your computer',
                'video_split_segments_param' => 'Segments: JSON array specifying where to split the video. Each segment has a start and end time in seconds.',
                'video_split_formats' => 'Supported Formats',
                'video_split_formats_desc' => 'Supports common video formats including MP4, MOV, AVI, and more.',
                'video_split_tips' => 'Usage Tips',
                'video_split_tips_1' => 'Each segment will become a separate output video file',
                'video_split_tips_2' => 'Segments must be in JSON format with "start" and "end" properties',
                'video_split_tips_3' => 'Time values are in seconds and can include decimals (e.g., 10.5)',
                'video_split_tips_4' => 'Segments should not overlap and should cover the desired portions of the video',
                'video_split_tips_5' => 'The output will be multiple video files, one for each segment',
                
                // Video trim documentation
                'about_video_trim' => 'About Trim Video',
                'video_trim_desc' => 'This endpoint trims a video file to a specified start and end time, creating a shorter video clip.',
                'video_trim_parameters' => 'Parameters',
                'video_trim_url_param' => 'Video URL: URL to the video file you want to trim',
                'video_trim_upload_param' => 'Upload Video File: Alternatively, you can upload a video file directly from your computer',
                'video_trim_start_param' => 'Start Time: The time in seconds where the trimmed video should begin',
                'video_trim_end_param' => 'End Time: The time in seconds where the trimmed video should end',
                'video_trim_formats' => 'Supported Formats',
                'video_trim_formats_desc' => 'Supports common video formats including MP4, MOV, AVI, and more.',
                'video_trim_tips' => 'Usage Tips',
                'video_trim_tips_1' => 'Start time must be less than end time',
                'video_trim_tips_2' => 'Time values are in seconds and can include decimals (e.g., 10.5)',
                'video_trim_tips_3' => 'The output video will contain only the portion between start and end times',
                'video_trim_tips_4' => 'For best results, choose times that align with scene changes when possible',
                
                // File upload documentation
                'about_file_upload' => 'About Upload to GCP Storage',
                'file_upload_desc' => 'This endpoint uploads files directly to Google Cloud Storage. Files are stored in the configured bucket and made accessible via direct URLs.',
                'file_upload_parameters' => 'Parameters',
                'file_upload_files_param' => 'Upload Files: Select one or more files from your computer to upload to Google Cloud Storage',
                'file_upload_formats' => 'Supported Formats',
                'file_upload_formats_desc' => 'Supports uploading of any file type including images, videos, audio files, documents, and more.',
                'file_upload_tips' => 'Usage Tips',
                'file_upload_tips_1' => 'Uploaded files receive unique names to prevent conflicts',
                'file_upload_tips_2' => 'Each upload provides a direct URL for accessing the file',
                'file_upload_tips_3' => 'Files are stored in the configured Google Cloud Storage bucket',
                'file_upload_tips_4' => 'Multiple files can be uploaded simultaneously',
                'file_upload_tips_5' => 'Uploaded files are publicly accessible via their URLs',
                
                // Dropdown options
                'position_bottom_center' => 'Bottom Center',
                'position_bottom_left' => 'Bottom Left',
                'position_bottom_right' => 'Bottom Right',
                'position_middle_center' => 'Middle Center',
                'position_top_center' => 'Top Center',
                'style_default' => 'Default',
                'style_karaoke' => 'Karaoke',
                'style_highlight' => 'Highlight',
                'style_underline' => 'Underline',
                'style_classic' => 'Classic',
                
                // API Response section
                'api_response' => 'API Response',
                'job_id' => 'Job ID:',
                'title' => 'Title:',
                'file' => 'File:',
                'size' => 'Size:',
                'download_url' => 'Download URL:',
                'download_file' => 'Download File',
                'files' => 'Files:',
                'transcription_srt_format' => 'Transcription (SRT Format)',
                'download_srt_file' => 'Download SRT File',
                'direct_url' => 'Direct URL:',
                'note_srt_url' => 'Note: This is a direct URL to the SRT file hosted on cloud storage.',
                'note_data_url' => 'Note: Direct SRT URL not provided by API, using data URL instead.',
                'file_upload_successful' => 'File Upload Successful!',
                'file_url' => 'File URL:',
                'file_name' => 'File Name:',
                'uploaded_files' => 'Uploaded Files:',
                'transcription_complete' => 'Transcription Complete',
                'video_concatenate_successful' => 'Video Concatenate Successful!',
                'audio_concatenate_successful' => 'Audio Concatenate Successful!',
                'image_convert_video_successful' => 'Image to Video Successful!',
                'media_convert_mp3_successful' => 'Convert to MP3 Successful!',
                'video_thumbnail_successful' => 'Extract Thumbnail Successful!',
                'webpage_screenshot_successful' => 'Webpage Screenshot Successful!',
                'video_caption_successful' => 'Add Caption Successful!',
                'video_cut_successful' => 'Cut Video Successful!',
                'video_trim_successful' => 'Trim Video Successful!',
                'video_split_successful' => 'Split Video Successful!',
                
                // General terms
                'success' => 'Successful!',
                'optional' => 'optional',
                'default' => 'default',
                'required' => 'required',
                'seconds' => 'seconds',
                'points' => 'points',
                'hex' => 'hex',
                'note' => 'Note:',
                'tip' => 'Tip:',
                'url' => 'URL',
                'urls' => 'URLs',
                'file_size_mb' => 'MB',
                'file_size_kb' => 'KB',
                'file_size_bytes' => 'Bytes',
                'file_size_gb' => 'GB',
                
                // Language switcher
                'language' => 'Language',
                'english' => 'English',
                'turkish' => 'Türkçe'
            ],
            'tr' => [
                // Header
                'app_title' => 'NCA Araç Seti PHP İstemcisi',
                'app_description' => 'Tüm No-Code Architects Araç Seti API uç noktalarına erişin',
                'select_endpoint' => 'Uç Nokta Seçin:',
                'choose_endpoint' => '-- Bir uç nokta seçin --',
                
                // Audio endpoints
                'audio_concatenate' => 'Ses Birleştirme',
                
                // Image endpoints
                'image_convert_video' => 'Görüntüyü Videoya Dönüştür',
                'image_screenshot_webpage' => 'Web Sayfası Ekran Görüntüsü',
                
                // Media endpoints
                'media_convert_mp3' => 'MP3\'e Dönüştür',
                'media_download' => 'Medya İndir',
                'media_transcribe' => 'Medya Transkripsiyonu',
                
                // YouTube endpoints
                'youtube_download' => 'YouTube İndir',
                'youtube_info' => 'YouTube Video Bilgisi',
                
                // Video endpoints
                'video_caption' => 'Altyazı Ekle',
                'video_concatenate' => 'Video Birleştirme',
                'video_thumbnail' => 'Küçük Resim Çıkart',
                'video_cut' => 'Video Kes',
                'video_split' => 'Video Böl',
                'video_trim' => 'Video Kırp',
                
                // File Upload endpoints
                'file_upload' => 'GCP Depolama\'ya Yükle',
                
                // Loading text
                'processing_request' => 'İsteğiniz işleniyor...',
                
                // Form labels and placeholders
                'audio_urls' => 'Ses URL\'leri (satır başına bir adet):',
                'audio_urls_placeholder' => "https://example.com/audio1.mp3\nhttps://example.com/audio2.mp3",
                'or_upload_audio_files' => 'Veya ses dosyalarını yükleyin:',
                'drag_drop_audio' => 'Ses dosyalarını buraya sürükleyip bırakın veya seçmek için tıklayın',
                'concatenate_audio' => 'Sesleri Birleştir',
                
                'image_url' => 'Görüntü URL\'si:',
                'image_url_placeholder' => 'https://example.com/image.jpg',
                'or_upload_image_file' => 'Veya bir görüntü dosyası yükleyin:',
                'drag_drop_image' => 'Bir görüntü dosyasını buraya sürükleyip bırakın veya seçmek için tıklayın',
                'convert_to_video' => 'Videoya Dönüştür',
                
                'webpage_url' => 'Web Sayfası URL\'si:',
                'webpage_url_placeholder' => 'https://example.com',
                'viewport_width' => 'Görünüm Alanı Genişliği (isteğe bağlı):',
                'viewport_height' => 'Görünüm Alanı Yüksekliği (isteğe bağlı):',
                'take_screenshot' => 'Ekran Görüntüsü Al',
                
                'media_url' => 'Medya URL\'si:',
                'media_url_placeholder' => 'https://example.com/audio.wav',
                'or_upload_media_file' => 'Veya bir medya dosyası yükleyin:',
                'drag_drop_media' => 'Bir medya dosyasını buraya sürükleyip bırakın veya seçmek için tıklayın',
                'convert_to_mp3' => 'MP3\'e Dönüştür',
                
                'drag_drop_download' => 'Bir medya dosyasını buraya sürükleyip bırakın veya seçmek için tıklayın',
                'cookies_optional' => 'Çerezler (isteğe bağlı):',
                'cookies_placeholder' => 'Kimliği doğrulanmış indirmeler için çerezleri buraya yapıştırın (örn. YouTube)',
                'download_media' => 'Medya İndir',
                
                'drag_drop_transcribe' => 'Bir medya dosyasını buraya sürükleyip bırakın veya seçmek için tıklayın',
                'get_segmented_srt' => 'Segmentli SRT formatı al',
                'show_direct_output' => 'Doğrudan çıktıyı göster (SRT dosyasını indirmek için işareti kaldırın)',
                'transcribe_media' => 'Medya Transkripsiyonu Yap',
                
                'youtube_url' => 'YouTube URL\'si:',
                'youtube_url_placeholder' => 'https://www.youtube.com/watch?v=...',
                'youtube_cookies_placeholder' => 'Kimliği doğrulanmış indirmeler için çerezleri buraya yapıştırın',
                'download_youtube' => 'YouTube Videosunu İndir',
                
                'youtube_info_url_placeholder' => 'https://www.youtube.com/watch?v=...',
                'youtube_info_cookies_placeholder' => 'Kimliği doğrulanmış erişim için çerezleri buraya yapıştırın',
                'get_youtube_info' => 'YouTube Video Bilgisi Al',
                
                'video_url' => 'Video URL\'si:',
                'video_url_placeholder' => 'https://example.com/video.mp4',
                'or_upload_video_file' => 'Veya bir video dosyası yükleyin:',
                'drag_drop_caption' => 'Bir video dosyasını buraya sürükleyip bırakın veya seçmek için tıklayın',
                'external_srt_url' => 'Harici SRT Dosya URL\'si (isteğe bağlı):',
                'srt_url_placeholder' => 'https://example.com/subtitles.srt',
                'external_srt_note' => 'Sağlanırsa, bu SRT dosyası ham altyazı metni yerine kullanılır',
                'caption_text' => 'Altyazı Metni (Not: SRT URL\'si sağlanırsa API tarafından yok sayılabilir):',
                'caption_position' => 'Altyazı Konumu:',
                'font_size' => 'Yazı Tipi Boyutu:',
                'font_family' => 'Yazı Tipi Ailesi:',
                'caption_style' => 'Altyazı Stili:',
                'line_color' => 'Satır Rengi (hex):',
                'outline_color' => 'Anahat Rengi (hex):',
                'word_color' => 'Kelime Rengi (hex, karaoke stili için):',
                'add_caption' => 'Altyazı Ekle',
                
                'video_urls' => 'Video URL\'leri (satır başına bir adet):',
                'video_urls_placeholder' => "https://example.com/video1.mp4\nhttps://example.com/video2.mp4",
                'or_upload_video_files' => 'Veya video dosyalarını yükleyin:',
                'drag_drop_video' => 'Video dosyalarını buraya sürükleyip bırakın veya seçmek için tıklayın',
                'concatenate_videos' => 'Videoları Birleştir',
                
                'drag_drop_thumbnail' => 'Bir video dosyasını buraya sürükleyip bırakın veya seçmek için tıklayın',
                'timestamp_seconds' => 'Zaman Damgası (saniye):',
                'extract_thumbnail' => 'Küçük Resim Çıkart',
                
                'drag_drop_cut' => 'Bir video dosyasını buraya sürükleyip bırakın veya seçmek için tıklayın',
                'segments_json' => 'Segmentler (JSON formatı):',
                'segments_placeholder' => '[{"start": 0, "end": 10}, {"start": 20, "end": 30}]',
                'cut_video' => 'Video Kes',
                
                'drag_drop_split' => 'Bir video dosyasını buraya sürükleyip bırakın veya seçmek için tıklayın',
                'split_segments_placeholder' => '[{"start": 0, "end": 10}, {"start": 10, "end": 20}, {"start": 20, "end": 30}]',
                'split_video' => 'Video Böl',
                
                'drag_drop_trim' => 'Bir video dosyasını buraya sürükleyip bırakın veya seçmek için tıklayın',
                'start_time' => 'Başlangıç Zamanı (saniye):',
                'end_time' => 'Bitiş Zamanı (saniye):',
                'trim_video' => 'Video Kırp',
                
                'upload_files_to_bucket' => 'Dosyaları maksimum-data kovasına yükleyin:',
                'drag_drop_upload' => 'Dosyaları buraya sürükleyip bırakın veya seçmek için tıklayın',
                'upload_files' => 'Dosyaları Yükle',
                
                // Documentation section
                'show_documentation' => 'Belgeleri Göster',
                'hide_documentation' => 'Belgeleri Gizle',
                
                // Audio documentation
                'about_audio_concatenate' => 'Ses Birleştirme Hakkında',
                'audio_concatenate_desc' => 'Bu uç nokta, birden fazla ses dosyasını tek bir ses dosyasında birleştirir. Dosyalar sağlandığı sırayla birleştirilir.',
                'audio_parameters' => 'Parametreler',
                'audio_urls_param' => 'Ses URL\'leri: Birleştirmek istediğiniz ses dosyalarının URL\'lerinin listesi. Satır başına bir URL girin.',
                'audio_upload_param' => 'Ses Dosyalarını Yükle: Alternatif olarak, ses dosyalarını doğrudan bilgisayarınızdan yükleyebilirsiniz. Aynı anda birden fazla dosya seçebilirsiniz.',
                'audio_formats' => 'Desteklenen Formatlar',
                'audio_formats_desc' => 'Uç nokta, MP3, WAV, AAC ve daha fazlasını içeren yaygın ses formatlarını destekler. En iyi sonuçlar için tüm dosyalar aynı formatta olmalıdır.',
                'audio_tips' => 'Kullanım İpuçları',
                'audio_tips_1' => 'Dosyalar listede göründükleri sırayla işlenir',
                'audio_tips_2' => 'En iyi sonuçlar için tüm dosyalar aynı ses özelliklerine (örnekleme oranı, bit derinliği) sahip olmalıdır',
                'audio_tips_3' => 'Büyük dosyaların işlenmesi daha uzun sürebilir',
                
                // Image documentation
                'about_image_convert_video' => 'Görüntüyü Videoya Dönüştürme Hakkında',
                'image_convert_video_desc' => 'Bu uç nokta, statik bir görüntüyü video dosyasına dönüştürür. Ortaya çıkan video, görüntüyü belirli bir süre boyunca görüntüler.',
                'image_parameters' => 'Parametreler',
                'image_url_param' => 'Görüntü URL\'si: Videoya dönüştürmek istediğiniz görüntünün URL\'si',
                'image_upload_param' => 'Görüntü Dosyasını Yükle: Alternatif olarak, bir görüntü dosyasını doğrudan bilgisayarınızdan yükleyebilirsiniz',
                'image_formats' => 'Desteklenen Formatlar',
                'image_formats_desc' => 'JPG, PNG, GIF ve daha fazlasını içeren yaygın görüntü formatlarını destekler. Çıktı videosu genellikle MP4 formatındadır.',
                'image_tips' => 'Kullanım İpuçları',
                'image_tips_1' => 'Daha yüksek çözünürlüklü görüntüler daha yüksek kaliteli videolarla sonuçlanır',
                'image_tips_2' => 'Varsayılan video süresi genellikle 5 saniyedir',
                'image_tips_3' => 'En iyi sonuçlar için 16:9 en-boy oranına sahip görüntüler kullanın',
                
                // Webpage screenshot documentation
                'about_webpage_screenshot' => 'Web Sayfası Ekran Görüntüsü Hakkında',
                'webpage_screenshot_desc' => 'Bu uç nokta, herhangi bir web sayfasının ekran görüntüsünü yakalar. Ekran görüntüsü boyutunu kontrol etmek için görünüm alanı boyutlarını belirleyebilirsiniz.',
                'webpage_parameters' => 'Parametreler',
                'webpage_url_param' => 'Web Sayfası URL\'si: Yakalamak istediğiniz web sayfasının URL\'si',
                'viewport_width_param' => 'Görünüm Alanı Genişliği: Tarayıcı görünüm alanının piksel cinsinden genişliği (isteğe bağlı, varsayılan 1920)',
                'viewport_height_param' => 'Görünüm Alanı Yüksekliği: Tarayıcı görünüm alanının piksel cinsinden yüksekliği (isteğe bağlı, varsayılan 1080)',
                'webpage_formats' => 'Desteklenen Formatlar',
                'webpage_formats_desc' => 'Ekran görüntüsü, yüksek kalite sağlayan ve şeffaflığı destekleyen PNG formatında yakalanır.',
                'webpage_tips' => 'Kullanım İpuçları',
                'webpage_tips_1' => 'URL\'nin protokolü (http:// veya https://) içerdiğinden emin olun',
                'webpage_tips_2' => 'Daha büyük görünüm alanı boyutları işleme süresini artırabilir',
                'webpage_tips_3' => 'Yoğun JavaScript içeren web sitelerinin tamamen işlenmesi daha uzun sürebilir',
                'webpage_tips_4' => 'Bazı web siteleri otomatik ekran görüntüsü yakalamayı engelleyebilir',
                
                // Media convert MP3 documentation
                'about_media_convert_mp3' => 'MP3\'e Dönüştürme Hakkında',
                'media_convert_mp3_desc' => 'Bu uç nokta, ses ve video dosyalarını MP3 formatına dönüştürür. Videolardan ses çıkarmak veya ses formatları arasında dönüştürmek için kullanışlıdır.',
                'media_convert_mp3_parameters' => 'Parametreler',
                'media_url_param' => 'Medya URL\'si: MP3\'e dönüştürmek istediğiniz medya dosyasının URL\'si',
                'media_upload_param' => 'Medya Dosyasını Yükle: Alternatif olarak, bir medya dosyasını doğrudan bilgisayarınızdan yükleyebilirsiniz',
                'media_convert_mp3_formats' => 'Desteklenen Formatlar',
                'media_convert_mp3_formats_desc' => 'WAV, AAC, FLAC, MP4, MOV, AVI ve daha fazlasını içeren çok çeşitli giriş formatlarını destekler. Çıktı her zaman MP3 formatındadır.',
                'media_convert_mp3_tips' => 'Kullanım İpuçları',
                'media_convert_mp3_tips_1' => 'Video dosyaları için yalnızca ses parçası çıkarılır ve dönüştürülür',
                'media_convert_mp3_tips_2' => 'MP3 bit hızı genellikle varsayılan olarak 128 kbps olarak ayarlanır',
                'media_convert_mp3_tips_3' => 'Daha büyük dosyaların işlenmesi daha uzun sürebilir',
                'media_convert_mp3_tips_4' => 'Kalite, kaynak dosyaya bağlı olarak değişebilir',
                
                // Media download documentation
                'about_media_download' => 'Medya İndirme Hakkında',
                'media_download_desc' => 'Bu uç nokta, URL\'lerden medya dosyalarını indirir. Çeşitli protokolleri destekler ve çerezlerle kimliği doğrulanmış indirmeleri işleyebilir.',
                'media_download_parameters' => 'Parametreler',
                'media_download_url_param' => 'Medya URL\'si: İndirmek istediğiniz medya dosyasının URL\'si',
                'media_download_upload_param' => 'Medya Dosyasını Yükle: Alternatif olarak, bir medya dosyasını doğrudan bilgisayarınızdan yükleyebilirsiniz',
                'media_download_cookies_param' => 'Çerezler: Kimliği doğrulanmış indirmeler için isteğe bağlı çerezler (örn. YouTube veya diğer platformlardan)',
                'media_download_formats' => 'Desteklenen Formatlar',
                'media_download_formats_desc' => 'MP4, MOV, AVI, MKV, MP3, WAV ve daha fazlasını içeren çeşitli medya formatlarını indirmeyi destekler.',
                'media_download_tips' => 'Kullanım İpuçları',
                'media_download_tips_1' => 'Kimlik doğrulaması gerektiren platformlar için gerekli çerezleri yapıştırın',
                'media_download_tips_2' => 'Büyük dosyaların indirilmesi ve işlenmesi daha uzun sürebilir',
                'media_download_tips_3' => 'Bazı platformlarda oran sınırlaması veya engelleme mekanizmaları olabilir',
                'media_download_tips_4' => 'İçeriği indirme hakkınız olduğundan emin olun',
                
                // Media transcribe documentation
                'about_media_transcribe' => 'Medya Transkripsiyonu Hakkında',
                'media_transcribe_desc' => 'Bu uç nokta, ses ve video içeriğini metne transkribe eder. Düz metin transkripsiyonları ve SRT altyazı dosyaları oluşturabilir.',
                'media_transcribe_parameters' => 'Parametreler',
                'media_transcribe_url_param' => 'Medya URL\'si: Transkribe etmek istediğiniz medya dosyasının URL\'si',
                'media_transcribe_upload_param' => 'Medya Dosyasını Yükle: Alternatif olarak, bir medya dosyasını doğrudan bilgisayarınızdan yükleyebilirsiniz',
                'media_transcribe_srt_param' => 'Segmentli SRT formatı al: İşaretlendiğinde, zaman damgaları olan bir SRT altyazı dosyası oluşturur',
                'media_transcribe_output_param' => 'Doğrudan çıktıyı göster: İşaretlendiğinde, transkripsiyonu tarayıcıda görüntüler; işaret kaldırıldığında, bir indirme bağlantısı sağlar',
                'media_transcribe_formats' => 'Desteklenen Formatlar',
                'media_transcribe_formats_desc' => 'MP3, WAV, MP4, MOV, AVI ve daha fazlasını içeren çok çeşitli ses ve video formatlarını destekler.',
                'media_transcribe_tips' => 'Kullanım İpuçları',
                'media_transcribe_tips_1' => 'Doğruluk, ses kalitesine ve netliğine bağlıdır',
                'media_transcribe_tips_2' => 'Daha uzun dosyaların işlenmesi daha fazla zaman alır',
                'media_transcribe_tips_3' => 'SRT formatı, videolar için altyazı oluşturmak için kullanışlıdır',
                'media_transcribe_tips_4' => 'En iyi sonuçlar için az arka plan gürültüsü olan net ses kullanın',
                
                // YouTube download documentation
                'about_youtube_download' => 'Doğrudan YouTube İndirme Hakkında',
                'youtube_download_desc' => 'Bu uç nokta, videoları doğrudan YouTube\'dan indirir. Çeşitli video kalitelerini destekler ve çerezlerle kimliği doğrulanmış indirmeleri işleyebilir.',
                'youtube_download_parameters' => 'Parametreler',
                'youtube_download_url_param' => 'YouTube URL\'si: İndirmek istediğiniz YouTube videosunun URL\'si (gerekli)',
                'youtube_download_cookies_param' => 'Çerezler: Kimliği doğrulanmış indirmeler veya kısıtlanmış içeriğe erişmek için isteğe bağlı çerezler',
                'youtube_download_formats' => 'Desteklenen Formatlar',
                'youtube_download_formats_desc' => 'Videoları en iyi mevcut kaliteyle MP4 formatında indirir. Sese video dosyasına dahildir.',
                'youtube_download_tips' => 'Kullanım İpuçları',
                'youtube_download_tips_1' => 'İçeriği indirme hakkınız olduğundan emin olun',
                'youtube_download_tips_2' => 'Yaş kısıtlamalı veya özel videolar için çerezler sağlamanız gerekebilir',
                'youtube_download_tips_3' => 'Daha büyük videoların indirilmesi ve işlenmesi daha uzun sürebilir',
                'youtube_download_tips_4' => 'YouTube, otomatik indirmeler için oran sınırlaması veya engelleme mekanizmalarına sahip olabilir',
                
                // YouTube info documentation
                'about_youtube_info' => 'YouTube Video Bilgisi Alma Hakkında',
                'youtube_info_desc' => 'Bu uç nokta, bir YouTube videosu hakkında ayrıntılı bilgi alır ancak videoyu indirmez. Başlık, açıklama, süre ve mevcut formatlar gibi meta veriler sağlar.',
                'youtube_info_parameters' => 'Parametreler',
                'youtube_info_url_param' => 'YouTube URL\'si: Hakkında bilgi almak istediğiniz YouTube videosunun URL\'si (gerekli)',
                'youtube_info_cookies_param' => 'Çerezler: Kısıtlanmış veya özel içeriğe erişmek için isteğe bağlı çerezler',
                'youtube_info_provided' => 'Sağlanan Bilgiler',
                'youtube_info_provided_desc' => 'Kapsamlı meta veriler döndürür:',
                'youtube_info_provided_1' => 'Video başlığı ve açıklaması',
                'youtube_info_provided_2' => 'Yükleyici/kanal bilgileri',
                'youtube_info_provided_3' => 'Süre ve yükleme tarihi',
                'youtube_info_provided_4' => 'Mevcut formatlar ve kalite seçenekleri',
                'youtube_info_provided_5' => 'Görüntüleme sayısı ve etkileşim metrikleri',
                'youtube_info_tips' => 'Kullanım İpuçları',
                'youtube_info_tips_1' => 'İndirmeden önce video bilgilerini önizlemek için kullanışlıdır',
                'youtube_info_tips_2' => 'Yaş kısıtlamalı veya özel videolar için çerezler sağlamanız gerekebilir',
                'youtube_info_tips_3' => 'Video içeriği indirilmez, yalnızca meta veriler',
                'youtube_info_tips_4' => 'Bilgiler gerçek video dosyasını işlemeden hızlıca alınır',
                
                // Video caption documentation
                'about_video_caption' => 'Altyazı Ekleme Hakkında',
                'video_caption_desc' => 'Bu uç nokta, video dosyalarına altyazı veya alt yazılar ekler. Harici bir SRT dosyası kullanabilir veya doğrudan altyazı metni belirleyebilirsiniz.',
                'video_caption_parameters' => 'Parametreler',
                'video_caption_url_param' => 'Video URL\'si: Altyazı eklemek istediğiniz video dosyasının URL\'si',
                'video_caption_upload_param' => 'Video Dosyasını Yükle: Alternatif olarak, bir video dosyasını doğrudan bilgisayarınızdan yükleyebilirsiniz',
                'video_caption_srt_url_param' => 'Harici SRT Dosya URL\'si: SRT altyazı dosyasının URL\'si (isteğe bağlı, doğrudan altyazı metnine göre önceliklidir)',
                'video_caption_text_param' => 'Altyazı Metni: Doğrudan altyazı metni (SRT URL\'si sağlanırsa API tarafından yok sayılabilir)',
                'video_caption_position_param' => 'Altyazı Konumu: Videodaki altyazıların yerleştirileceği yer',
                'video_caption_font_size_param' => 'Yazı Tipi Boyutu: Puan cinsinden altyazı metninin boyutu',
                'video_caption_font_family_param' => 'Yazı Tipi Ailesi: Altyazılar için kullanılacak yazı tipi (örn. Arial, Times New Roman)',
                'video_caption_style_param' => 'Altyazı Stili: Karaoke veya vurgu efektleri gibi özel stil seçenekleri',
                'video_caption_line_color_param' => 'Satır Rengi: Hex formatında altyazı metninin rengi (örn. beyaz için #FFFFFF)',
                'video_caption_outline_color_param' => 'Anahat Rengi: Hex formatında metin anahattının rengi',
                'video_caption_word_color_param' => 'Kelime Rengi: Karaoke stilindeki bireysel kelimeler için renk',
                'video_caption_formats' => 'Desteklenen Formatlar',
                'video_caption_formats_desc' => 'MP4, MOV, AVI ve daha fazlasını içeren yaygın video formatlarını destekler. SRT dosyaları standart altyazı formatında olmalıdır.',
                'video_caption_tips' => 'Kullanım İpuçları',
                'video_caption_tips_1' => 'En iyi sonuçlar için görünürlük için yüksek kontrastlı renkler kullanın',
                'video_caption_tips_2' => 'Harici SRT dosyaları doğrudan altyazı metnine göre önceliklidir',
                'video_caption_tips_3' => 'Karaoke stili, kelime kelime SRT dosyalarıyla en iyi şekilde kullanılır',
                'video_caption_tips_4' => '12-24 arasındaki yazı tipi boyutları çoğu video için iyi çalışır',
                
                // Video concatenate documentation
                'about_video_concatenate' => 'Video Birleştirme Hakkında',
                'video_concatenate_desc' => 'Bu uç nokta, birden fazla video dosyasını tek bir videoda birleştirir. Videolar sağlandığı sırayla birleştirilir.',
                'video_concatenate_parameters' => 'Parametreler',
                'video_concatenate_urls_param' => 'Video URL\'leri: Birleştirmek istediğiniz video dosyalarının URL\'lerinin listesi. Satır başına bir URL girin.',
                'video_concatenate_upload_param' => 'Video Dosyalarını Yükle: Alternatif olarak, video dosyalarını doğrudan bilgisayarınızdan yükleyebilirsiniz. Aynı anda birden fazla dosya seçebilirsiniz.',
                'video_concatenate_formats' => 'Desteklenen Formatlar',
                'video_concatenate_formats_desc' => 'MP4, MOV, AVI ve daha fazlasını içeren yaygın video formatlarını destekler. En iyi sonuçlar için tüm videolar benzer özelliklere sahip olmalıdır.',
                'video_concatenate_tips' => 'Kullanım İpuçları',
                'video_concatenate_tips_1' => 'Videolar listede göründükleri sırayla işlenir',
                'video_concatenate_tips_2' => 'En iyi sonuçlar için tüm videolar aynı çözünürlüğe, kare hızına ve kodeğe sahip olmalıdır',
                'video_concatenate_tips_3' => 'Büyük videoların işlenmesi daha uzun sürebilir',
                'video_concatenate_tips_4' => 'Çıktı videosu, dizideki ilk videonun özelliklerine sahip olacaktır',
                
                // Video thumbnail documentation
                'about_video_thumbnail' => 'Küçük Resim Çıkartma Hakkında',
                'video_thumbnail_desc' => 'Bu uç nokta, bir video dosyasından tek bir kare çıkarır ve bunu bir görüntü olarak kaydeder. Kareyi çıkaracağınız tam zamanı belirleyebilirsiniz.',
                'video_thumbnail_parameters' => 'Parametreler',
                'video_thumbnail_url_param' => 'Video URL\'si: Küçük resim çıkartmak istediğiniz video dosyasının URL\'si',
                'video_thumbnail_upload_param' => 'Video Dosyasını Yükle: Alternatif olarak, bir video dosyasını doğrudan bilgisayarınızdan yükleyebilirsiniz',
                'video_thumbnail_timestamp_param' => 'Zaman Damgası: Kareyi çıkaracağınız saniye cinsinden zaman (isteğe bağlı, varsayılan olarak videonun ortası)',
                'video_thumbnail_formats' => 'Desteklenen Formatlar',
                'video_thumbnail_formats_desc' => 'MP4, MOV, AVI ve daha fazlasını içeren yaygın video formatlarını destekler. Çıktı küçük resmi genellikle JPG veya PNG formatındadır.',
                'video_thumbnail_tips' => 'Kullanım İpuçları',
                'video_thumbnail_tips_1' => 'Zaman damgası sağlanmazsa, videonun ortasından bir kare çıkarılır',
                'video_thumbnail_tips_2' => 'En iyi sonuçlar için net, temsili içeriğe sahip bir zaman damgası seçin',
                'video_thumbnail_tips_3' => 'Daha yüksek çözünürlüklü videolar daha yüksek kaliteli küçük resimler üretir',
                'video_thumbnail_tips_4' => 'Kesin zamanlama için kesirli saniyeler (örn. 10.5) desteklenir',
                
                // Video cut documentation
                'about_video_cut' => 'Video Kesme Hakkında',
                'video_cut_desc' => 'Bu uç nokta, belirtilen segmentleri bir video dosyasından kaldırır ve yalnızca istediğiniz parçaları tutar. İstenmeyen bölümleri düzenlemek için kullanışlıdır.',
                'video_cut_parameters' => 'Parametreler',
                'video_cut_url_param' => 'Video URL\'si: Kesmek istediğiniz video dosyasının URL\'si',
                'video_cut_upload_param' => 'Video Dosyasını Yükle: Alternatif olarak, bir video dosyasını doğrudan bilgisayarınızdan yükleyebilirsiniz',
                'video_cut_segments_param' => 'Segmentler: Hangi parçaların tutulacağını belirten JSON dizisi. Her segmentin başlangıç ve bitiş zamanı saniye cinsindendir.',
                'video_cut_formats' => 'Desteklenen Formatlar',
                'video_cut_formats_desc' => 'MP4, MOV, AVI ve daha fazlasını içeren yaygın video formatlarını destekler.',
                'video_cut_tips' => 'Kullanım İpuçları',
                'video_cut_tips_1' => 'Kaldırılacak segmentleri değil, tutulacak segmentleri belirtin',
                'video_cut_tips_2' => 'Segmentler "start" ve "end" özelliklerine sahip JSON formatında olmalıdır',
                'video_cut_tips_3' => 'Zaman değerleri saniye cinsindendir ve ondalık içerebilir (örn. 10.5)',
                'video_cut_tips_4' => 'Bitişik olmayan parçaları tutmak için birden fazla segment belirtilebilir',
                'video_cut_tips_5' => 'Çıktı, her segment için bir olmak üzere birden fazla video dosyası olacaktır',
                
                // Video split documentation
                'about_video_split' => 'Video Bölme Hakkında',
                'video_split_desc' => 'Bu uç nokta, bir video dosyasını belirtilen zaman segmentlerine göre birden fazla ayrı video dosyasına böler.',
                'video_split_parameters' => 'Parametreler',
                'video_split_url_param' => 'Video URL\'si: Bölmek istediğiniz video dosyasının URL\'si',
                'video_split_upload_param' => 'Video Dosyasını Yükle: Alternatif olarak, bir video dosyasını doğrudan bilgisayarınızdan yükleyebilirsiniz',
                'video_split_segments_param' => 'Segmentler: Videonun nerede bölüneceğini belirten JSON dizisi. Her segmentin başlangıç ve bitiş zamanı saniye cinsindendir.',
                'video_split_formats' => 'Desteklenen Formatlar',
                'video_split_formats_desc' => 'MP4, MOV, AVI ve daha fazlasını içeren yaygın video formatlarını destekler.',
                'video_split_tips' => 'Kullanım İpuçları',
                'video_split_tips_1' => 'Her segment ayrı bir çıktı video dosyası olacaktır',
                'video_split_tips_2' => 'Segmentler "start" ve "end" özelliklerine sahip JSON formatında olmalıdır',
                'video_split_tips_3' => 'Zaman değerleri saniye cinsindendir ve ondalık içerebilir (örn. 10.5)',
                'video_split_tips_4' => 'Segmentler örtüşmemeli ve videonun istenen bölümlerini kapsamalıdır',
                'video_split_tips_5' => 'Çıktı, her segment için bir olmak üzere birden fazla video dosyası olacaktır',
                
                // Video trim documentation
                'about_video_trim' => 'Video Kırpma Hakkında',
                'video_trim_desc' => 'Bu uç nokta, bir video dosyasını belirtilen başlangıç ve bitiş zamanına göre kırparak daha kısa bir video klibi oluşturur.',
                'video_trim_parameters' => 'Parametreler',
                'video_trim_url_param' => 'Video URL\'si: Kırpmak istediğiniz video dosyasının URL\'si',
                'video_trim_upload_param' => 'Video Dosyasını Yükle: Alternatif olarak, bir video dosyasını doğrudan bilgisayarınızdan yükleyebilirsiniz',
                'video_trim_start_param' => 'Başlangıç Zamanı: Kırpılmış videonun başlayacağı saniye cinsinden zaman',
                'video_trim_end_param' => 'Bitiş Zamanı: Kırpılmış videonun biteceği saniye cinsinden zaman',
                'video_trim_formats' => 'Desteklenen Formatlar',
                'video_trim_formats_desc' => 'MP4, MOV, AVI ve daha fazlasını içeren yaygın video formatlarını destekler.',
                'video_trim_tips' => 'Kullanım İpuçları',
                'video_trim_tips_1' => 'Başlangıç zamanı bitiş zamanından küçük olmalıdır',
                'video_trim_tips_2' => 'Zaman değerleri saniye cinsindendir ve ondalık içerebilir (örn. 10.5)',
                'video_trim_tips_3' => 'Çıktı videosu yalnızca başlangıç ve bitiş zamanları arasındaki bölümü içerecektir',
                'video_trim_tips_4' => 'En iyi sonuçlar için sahne değişiklikleriyle uyumlu zamanlar seçin',
                
                // File upload documentation
                'about_file_upload' => 'GCP Depolama\'ya Yükleme Hakkında',
                'file_upload_desc' => 'Bu uç nokta, dosyaları doğrudan Google Cloud Storage\'a yükler. Dosyalar yapılandırılmış kova içinde saklanır ve doğrudan URL\'ler aracılığıyla erişilebilir hale getirilir.',
                'file_upload_parameters' => 'Parametreler',
                'file_upload_files_param' => 'Dosyaları Yükle: Google Cloud Storage\'a yüklemek için bilgisayarınızdan bir veya daha fazla dosya seçin',
                'file_upload_formats' => 'Desteklenen Formatlar',
                'file_upload_formats_desc' => 'Görüntüler, videolar, ses dosyaları, belgeler ve daha fazlasını içeren herhangi bir dosya türünü yüklemeyi destekler.',
                'file_upload_tips' => 'Kullanım İpuçları',
                'file_upload_tips_1' => 'Yüklenen dosyalar çakışmaları önlemek için benzersiz adlar alır',
                'file_upload_tips_2' => 'Her yükleme, dosyaya erişmek için doğrudan bir URL sağlar',
                'file_upload_tips_3' => 'Dosyalar yapılandırılmış Google Cloud Storage kovasında saklanır',
                'file_upload_tips_4' => 'Birden fazla dosya aynı anda yüklenebilir',
                'file_upload_tips_5' => 'Yüklenen dosyalara URL\'leri aracılığıyla herkese açık olarak erişilebilir',
                
                // Dropdown options
                'position_bottom_center' => 'Alt Orta',
                'position_bottom_left' => 'Alt Sol',
                'position_bottom_right' => 'Alt Sağ',
                'position_middle_center' => 'Orta Merkez',
                'position_top_center' => 'Üst Orta',
                'style_default' => 'Varsayılan',
                'style_karaoke' => 'Karaoke',
                'style_highlight' => 'Vurgu',
                'style_underline' => 'Altı Çizili',
                'style_classic' => 'Klasik',
                
                // API Response section
                'api_response' => 'API Yanıtı',
                'job_id' => 'İş Kimliği:',
                'title' => 'Başlık:',
                'file' => 'Dosya:',
                'size' => 'Boyut:',
                'download_url' => 'İndirme URL\'si:',
                'download_file' => 'Dosyayı İndir',
                'files' => 'Dosyalar:',
                'transcription_srt_format' => 'Transkripsiyon (SRT Formatı)',
                'download_srt_file' => 'SRT Dosyasını İndir',
                'direct_url' => 'Doğrudan URL:',
                'note_srt_url' => 'Not: Bu, bulut depolamada barındırılan SRT dosyasına doğrudan bir URL\'dir.',
                'note_data_url' => 'Not: API tarafından doğrudan SRT URL\'si sağlanmadı, bunun yerine veri URL\'si kullanılıyor.',
                'file_upload_successful' => 'Dosya Yükleme Başarılı!',
                'file_url' => 'Dosya URL\'si:',
                'file_name' => 'Dosya Adı:',
                'uploaded_files' => 'Yüklenen Dosyalar:',
                'transcription_complete' => 'Transkripsiyon Tamamlandı',
                'video_concatenate_successful' => 'Video Birleştirme Başarılı!',
                'audio_concatenate_successful' => 'Ses Birleştirme Başarılı!',
                'image_convert_video_successful' => 'Görüntüyü Videoya Dönüştürme Başarılı!',
                'media_convert_mp3_successful' => 'MP3\'e Dönüştürme Başarılı!',
                'video_thumbnail_successful' => 'Küçük Resim Çıkartma Başarılı!',
                'webpage_screenshot_successful' => 'Web Sayfası Ekran Görüntüsü Başarılı!',
                'video_caption_successful' => 'Altyazı Ekleme Başarılı!',
                'video_cut_successful' => 'Video Kesme Başarılı!',
                'video_trim_successful' => 'Video Kırpma Başarılı!',
                'video_split_successful' => 'Video Bölme Başarılı!',
                
                // General terms
                'success' => 'Başarılı!',
                'optional' => 'isteğe bağlı',
                'default' => 'varsayılan',
                'required' => 'gerekli',
                'seconds' => 'saniye',
                'points' => 'puan',
                'hex' => 'hex',
                'note' => 'Not:',
                'tip' => 'İpucu:',
                'url' => 'URL',
                'urls' => 'URL\'ler',
                'file_size_mb' => 'MB',
                'file_size_kb' => 'KB',
                'file_size_bytes' => 'Bayt',
                'file_size_gb' => 'GB',
                
                // Language switcher
                'language' => 'Dil',
                'english' => 'English',
                'turkish' => 'Türkçe'
            ]
        ];
        
        // Determine current language from cookie or default to Turkish
        $this->currentLanguage = $this->getCurrentLanguage();
    }
    
    public function getCurrentLanguage() {
        // Check if language is set in cookie
        if (isset($_COOKIE['language'])) {
            $lang = $_COOKIE['language'];
            // Validate language
            if (array_key_exists($lang, $this->translations)) {
                return $lang;
            }
        }
        
        // Default to Turkish
        return 'tr';
    }
    
    public function setCurrentLanguage($lang) {
        // Validate language
        if (array_key_exists($lang, $this->translations)) {
            $this->currentLanguage = $lang;
            // Set cookie for 30 days
            setcookie('language', $lang, time() + (30 * 24 * 60 * 60), '/');
            return true;
        }
        return false;
    }
    
    public function get($key) {
        // Return translated string if available, otherwise return key
        if (isset($this->translations[$this->currentLanguage][$key])) {
            return $this->translations[$this->currentLanguage][$key];
        }
        return $key;
    }
    
    public function getCurrentLanguageCode() {
        return $this->currentLanguage;
    }
    
    public function isCurrentLanguage($lang) {
        return $this->currentLanguage === $lang;
    }
    
    public function getJavaScriptTranslations() {
        $jsTranslations = "const translations = {\n";
        foreach ($this->translations[$this->currentLanguage] as $key => $value) {
            // Properly escape the value for JavaScript
            $escapedValue = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP);
            $escapedKey = json_encode($key, JSON_UNESCAPED_UNICODE);
            $jsTranslations .= "    " . $escapedKey . ": " . $escapedValue . ",\n";
        }
        $jsTranslations .= "};\n";
        return $jsTranslations;
    }
}