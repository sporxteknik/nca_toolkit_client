// JavaScript functionality for NCA Toolkit PHP Client

document.addEventListener('DOMContentLoaded', function() {
    const endpointSelect = document.getElementById('endpoint');
    const endpointForm = document.getElementById('endpoint-form');
    const loadingDiv = document.getElementById('loading');
    const resultDiv = document.querySelector('.result');
    
    // Form templates for each endpoint with drag-and-drop support
    const formTemplates = {
        'audio_concatenate': `
            <h2>Audio Concatenate</h2>
            <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="audio_urls">Audio URLs (one per line):</label>
                <textarea id="audio_urls" name="audio_urls" placeholder="https://example.com/audio1.mp3
https://example.com/audio2.mp3"></textarea>
            </div>
            <div class="form-group">
                <label>Or upload audio files:</label>
                <div class="drop-area" id="audio-drop-area">
                    <p>Drag & drop audio files here or click to select files</p>
                    <input type="file" id="audio_files" name="audio_files[]" multiple accept="audio/*" style="display: none;">
                </div>
                <div class="file-list" id="audio-file-list"></div>
            </div>
            <input type="hidden" name="endpoint" value="audio_concatenate">
            <button type="submit" class="btn">Concatenate Audio</button>
            </form>
            
            <div class="doc-section">
                <button type="button" class="doc-toggle">Show Documentation</button>
                <div class="doc-content hidden">
                    <h4>About Audio Concatenate</h4>
                    <p>This endpoint combines multiple audio files into a single audio file. The files are concatenated in the order they are provided.</p>
                    
                    <h4>Parameters</h4>
                    <ul>
                        <li><strong>Audio URLs</strong>: A list of URLs to audio files you want to concatenate. Enter one URL per line.</li>
                        <li><strong>Upload Audio Files</strong>: Alternatively, you can upload audio files directly from your computer. You can select multiple files at once.</li>
                    </ul>
                    
                    <h4>Supported Formats</h4>
                    <p>The endpoint supports common audio formats including MP3, WAV, AAC, and more. All files should be in the same format for best results.</p>
                    
                    <h4>Usage Tips</h4>
                    <ul>
                        <li>Files are processed in the order they appear in the list</li>
                        <li>All files should have the same audio properties (sample rate, bit depth) for optimal results</li>
                        <li>Large files may take longer to process</li>
                    </ul>
                </div>
            </div>
        `,
        
        'image_convert_video': `
            <h2>Image to Video</h2>
            <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="image_url">Image URL:</label>
                <input type="url" id="image_url" name="image_url" placeholder="https://example.com/image.jpg">
            </div>
            <div class="form-group">
                <label>Or upload an image file:</label>
                <div class="drop-area" id="image-drop-area">
                    <p>Drag & drop an image file here or click to select file</p>
                    <input type="file" id="image_file" name="image_file" accept="image/*" style="display: none;">
                </div>
                <div class="file-list" id="image-file-list"></div>
            </div>
            <input type="hidden" name="endpoint" value="image_convert_video">
            <button type="submit" class="btn">Convert to Video</button>
            </form>
            
            <div class="doc-section">
                <button type="button" class="doc-toggle">Show Documentation</button>
                <div class="doc-content hidden">
                    <h4>About Image to Video</h4>
                    <p>This endpoint converts a static image into a video file. The resulting video will display the image for a specified duration.</p>
                    
                    <h4>Parameters</h4>
                    <ul>
                        <li><strong>Image URL</strong>: URL to the image you want to convert to video</li>
                        <li><strong>Upload Image File</strong>: Alternatively, you can upload an image file directly from your computer</li>
                    </ul>
                    
                    <h4>Supported Formats</h4>
                    <p>Supports common image formats including JPG, PNG, GIF, and more. The output video is typically in MP4 format.</p>
                    
                    <h4>Usage Tips</h4>
                    <ul>
                        <li>Higher resolution images will result in higher quality videos</li>
                        <li>The default video duration is typically 5 seconds</li>
                        <li>For best results, use images with a 16:9 aspect ratio</li>
                    </ul>
                </div>
            </div>
        `,
        
        'image_screenshot_webpage': `
            <h2>Webpage Screenshot</h2>
            <form method="post">
            <div class="form-group">
                <label for="url">Webpage URL:</label>
                <input type="url" id="url" name="url" placeholder="https://example.com">
            </div>
            <div class="form-group">
                <label for="viewport_width">Viewport Width (optional):</label>
                <input type="number" id="viewport_width" name="viewport_width" placeholder="1920">
            </div>
            <div class="form-group">
                <label for="viewport_height">Viewport Height (optional):</label>
                <input type="number" id="viewport_height" name="viewport_height" placeholder="1080">
            </div>
            <input type="hidden" name="endpoint" value="image_screenshot_webpage">
            <button type="submit" class="btn">Take Screenshot</button>
            </form>
            
            <div class="doc-section">
                <button type="button" class="doc-toggle">Show Documentation</button>
                <div class="doc-content hidden">
                    <h4>About Webpage Screenshot</h4>
                    <p>This endpoint captures a screenshot of any webpage. You can specify the viewport dimensions to control the screenshot size.</p>
                    
                    <h4>Parameters</h4>
                    <ul>
                        <li><strong>Webpage URL</strong>: The URL of the webpage you want to capture</li>
                        <li><strong>Viewport Width</strong>: Width of the browser viewport in pixels (optional, default is 1920)</li>
                        <li><strong>Viewport Height</strong>: Height of the browser viewport in pixels (optional, default is 1080)</li>
                    </ul>
                    
                    <h4>Supported Formats</h4>
                    <p>The screenshot is captured in PNG format, which provides high quality and supports transparency.</p>
                    
                    <h4>Usage Tips</h4>
                    <ul>
                        <li>Make sure the URL includes the protocol (http:// or https://)</li>
                        <li>Larger viewport dimensions may increase processing time</li>
                        <li>Websites with heavy JavaScript may take longer to render completely</li>
                        <li>Some websites may block automated screenshot capture</li>
                    </ul>
                </div>
            </div>
        `,
        
        'media_convert_mp3': `
            <h2>Convert to MP3</h2>
            <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="media_url">Media URL:</label>
                <input type="url" id="media_url" name="media_url" placeholder="https://example.com/audio.wav">
            </div>
            <div class="form-group">
                <label>Or upload a media file:</label>
                <div class="drop-area" id="media-drop-area">
                    <p>Drag & drop a media file here or click to select file</p>
                    <input type="file" id="media_file" name="media_file" accept="audio/*,video/*" style="display: none;">
                </div>
                <div class="file-list" id="media-file-list"></div>
            </div>
            <input type="hidden" name="endpoint" value="media_convert_mp3">
            <button type="submit" class="btn">Convert to MP3</button>
            </form>
            
            <div class="doc-section">
                <button type="button" class="doc-toggle">Show Documentation</button>
                <div class="doc-content hidden">
                    <h4>About Convert to MP3</h4>
                    <p>This endpoint converts audio and video files to MP3 format. It's useful for extracting audio from videos or converting between audio formats.</p>
                    
                    <h4>Parameters</h4>
                    <ul>
                        <li><strong>Media URL</strong>: URL to the media file you want to convert to MP3</li>
                        <li><strong>Upload Media File</strong>: Alternatively, you can upload a media file directly from your computer</li>
                    </ul>
                    
                    <h4>Supported Formats</h4>
                    <p>Supports a wide range of input formats including WAV, AAC, FLAC, MP4, MOV, AVI, and more. The output is always in MP3 format.</p>
                    
                    <h4>Usage Tips</h4>
                    <ul>
                        <li>For video files, only the audio track will be extracted and converted</li>
                        <li>MP3 bitrate is typically set to 128 kbps by default</li>
                        <li>Larger files may take longer to process</li>
                        <li>Quality may vary depending on the source file</li>
                    </ul>
                </div>
            </div>
        `,
        
        'media_download': `
            <h2>Media Download</h2>
            <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="url">Media URL:</label>
                <input type="url" id="url" name="url" placeholder="https://example.com/video.mp4">
            </div>
            <div class="form-group">
                <label>Or upload a media file:</label>
                <div class="drop-area" id="download-drop-area">
                    <p>Drag & drop a media file here or click to select file</p>
                    <input type="file" id="download_file" name="download_file" accept="audio/*,video/*" style="display: none;">
                </div>
                <div class="file-list" id="download-file-list"></div>
            </div>
            <div class="form-group">
                <label for="cookies">Cookies (optional):</label>
                <textarea id="cookies" name="cookies" placeholder="Paste cookies here for authenticated downloads (e.g., YouTube)"></textarea>
            </div>
            <input type="hidden" name="endpoint" value="media_download">
            <button type="submit" class="btn">Download Media</button>
            </form>
            
            <div class="doc-section">
                <button type="button" class="doc-toggle">Show Documentation</button>
                <div class="doc-content hidden">
                    <h4>About Media Download</h4>
                    <p>This endpoint downloads media files from URLs. It supports various protocols and can handle authenticated downloads with cookies.</p>
                    
                    <h4>Parameters</h4>
                    <ul>
                        <li><strong>Media URL</strong>: URL to the media file you want to download</li>
                        <li><strong>Upload Media File</strong>: Alternatively, you can upload a media file directly from your computer</li>
                        <li><strong>Cookies</strong>: Optional cookies for authenticated downloads (e.g., from YouTube or other platforms)</li>
                    </ul>
                    
                    <h4>Supported Formats</h4>
                    <p>Supports downloading of various media formats including MP4, MOV, AVI, MKV, MP3, WAV, and more.</p>
                    
                    <h4>Usage Tips</h4>
                    <ul>
                        <li>For platforms that require authentication, paste the necessary cookies</li>
                        <li>Large files may take longer to download and process</li>
                        <li>Some platforms may have rate limiting or blocking mechanisms</li>
                        <li>Make sure you have the right to download the content</li>
                    </ul>
                </div>
            </div>
        `,
        
        'media_transcribe': `
            <h2>Media Transcribe</h2>
            <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="media_url">Media URL:</label>
                <input type="url" id="media_url" name="media_url" placeholder="https://example.com/audio.mp3">
            </div>
            <div class="form-group">
                <label>Or upload a media file:</label>
                <div class="drop-area" id="transcribe-drop-area">
                    <p>Drag & drop a media file here or click to select file</p>
                    <input type="file" id="transcribe_file" name="transcribe_file" accept="audio/*,video/*" style="display: none;">
                </div>
                <div class="file-list" id="transcribe-file-list"></div>
            </div>
            <div class="form-group">
                <label for="srt_format">
                    <input type="checkbox" id="srt_format" name="srt_format" value="1" checked>
                    Get segmented SRT format
                </label>
            </div>
            <div class="form-group">
                <label for="direct_output">
                    <input type="checkbox" id="direct_output" name="direct_output" value="1">
                    Show direct output (uncheck to download SRT file)
                </label>
            </div>
            <input type="hidden" name="endpoint" value="media_transcribe">
            <button type="submit" class="btn">Transcribe Media</button>
            </form>
            
            <div class="doc-section">
                <button type="button" class="doc-toggle">Show Documentation</button>
                <div class="doc-content hidden">
                    <h4>About Media Transcribe</h4>
                    <p>This endpoint transcribes audio and video content into text. It can generate both plain text transcriptions and SRT subtitle files.</p>
                    
                    <h4>Parameters</h4>
                    <ul>
                        <li><strong>Media URL</strong>: URL to the media file you want to transcribe</li>
                        <li><strong>Upload Media File</strong>: Alternatively, you can upload a media file directly from your computer</li>
                        <li><strong>Get segmented SRT format</strong>: When checked, generates an SRT subtitle file with timestamps</li>
                        <li><strong>Show direct output</strong>: When checked, displays the transcription in the browser; when unchecked, provides a download link</li>
                    </ul>
                    
                    <h4>Supported Formats</h4>
                    <p>Supports a wide range of audio and video formats including MP3, WAV, MP4, MOV, AVI, and more.</p>
                    
                    <h4>Usage Tips</h4>
                    <ul>
                        <li>Accuracy depends on audio quality and clarity</li>
                        <li>Longer files will take more time to process</li>
                        <li>SRT format is useful for creating subtitles for videos</li>
                        <li>For best results, use clear audio with minimal background noise</li>
                    </ul>
                </div>
            </div>
        `,
        
        'youtube_download': `
            <h2>Direct YouTube Download</h2>
            <form method="post">
            <div class="form-group">
                <label for="url">YouTube URL:</label>
                <input type="url" id="url" name="url" placeholder="https://www.youtube.com/watch?v=..." required>
            </div>
            <div class="form-group">
                <label for="cookies">Cookies (optional):</label>
                <textarea id="cookies" name="cookies" placeholder="Paste cookies here for authenticated downloads"></textarea>
            </div>
            <input type="hidden" name="endpoint" value="youtube_download">
            <button type="submit" class="btn">Download YouTube Video</button>
            </form>
            
            <div class="doc-section">
                <button type="button" class="doc-toggle">Show Documentation</button>
                <div class="doc-content hidden">
                    <h4>About Direct YouTube Download</h4>
                    <p>This endpoint downloads videos directly from YouTube. It supports various video qualities and can handle authenticated downloads with cookies.</p>
                    
                    <h4>Parameters</h4>
                    <ul>
                        <li><strong>YouTube URL</strong>: The URL of the YouTube video you want to download (required)</li>
                        <li><strong>Cookies</strong>: Optional cookies for authenticated downloads or accessing restricted content</li>
                    </ul>
                    
                    <h4>Supported Formats</h4>
                    <p>Downloads videos in MP4 format with the best available quality. Audio is included in the video file.</p>
                    
                    <h4>Usage Tips</h4>
                    <ul>
                        <li>Make sure you have the right to download the content</li>
                        <li>For age-restricted or private videos, you may need to provide cookies</li>
                        <li>Larger videos may take longer to download and process</li>
                        <li>YouTube may have rate limiting or blocking mechanisms for automated downloads</li>
                    </ul>
                </div>
            </div>
        `,
        
        'youtube_info': `
            <h2>Get YouTube Video Info</h2>
            <form method="post">
            <div class="form-group">
                <label for="url">YouTube URL:</label>
                <input type="url" id="url" name="url" placeholder="https://www.youtube.com/watch?v=..." required>
            </div>
            <div class="form-group">
                <label for="cookies">Cookies (optional):</label>
                <textarea id="cookies" name="cookies" placeholder="Paste cookies here for authenticated access"></textarea>
            </div>
            <input type="hidden" name="endpoint" value="youtube_info">
            <button type="submit" class="btn">Get YouTube Video Info</button>
            </form>
            
            <div class="doc-section">
                <button type="button" class="doc-toggle">Show Documentation</button>
                <div class="doc-content hidden">
                    <h4>About Get YouTube Video Info</h4>
                    <p>This endpoint retrieves detailed information about a YouTube video without downloading it. It provides metadata such as title, description, duration, and available formats.</p>
                    
                    <h4>Parameters</h4>
                    <ul>
                        <li><strong>YouTube URL</strong>: The URL of the YouTube video you want information about (required)</li>
                        <li><strong>Cookies</strong>: Optional cookies for accessing restricted or private content</li>
                    </ul>
                    
                    <h4>Information Provided</h4>
                    <p>Returns comprehensive metadata including:
                    <ul>
                        <li>Video title and description</li>
                        <li>Uploader/channel information</li>
                        <li>Duration and upload date</li>
                        <li>Available formats and quality options</li>
                        <li>View count and engagement metrics</li>
                    </ul>
                    </p>
                    
                    <h4>Usage Tips</h4>
                    <ul>
                        <li>Useful for previewing video information before downloading</li>
                        <li>For age-restricted or private videos, you may need to provide cookies</li>
                        <li>No video content is downloaded, only metadata</li>
                        <li>Information is retrieved quickly without processing the actual video file</li>
                    </ul>
                </div>
            </div>
        `,
        
        'video_caption': `
            <h2>Add Caption</h2>
            <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="video_url">Video URL:</label>
                <input type="url" id="video_url" name="video_url" placeholder="https://example.com/video.mp4">
            </div>
            <div class="form-group">
                <label>Or upload a video file:</label>
                <div class="drop-area" id="caption-drop-area">
                    <p>Drag & drop a video file here or click to select file</p>
                    <input type="file" id="caption_file" name="caption_file" accept="video/*" style="display: none;">
                </div>
                <div class="file-list" id="caption-file-list"></div>
            </div>
            <div class="form-group">
                <label for="srt_url">External SRT File URL (optional):</label>
                <input type="url" id="srt_url" name="srt_url" placeholder="https://example.com/subtitles.srt">
                <small>If provided, this SRT file will be used instead of raw caption text</small>
            </div>
            <div class="form-group">
                <label for="caption_text">Caption Text (Note: This may be ignored by the API if SRT URL is provided):</label>
                <textarea id="caption_text" name="caption_text" placeholder="Enter caption text..."></textarea>
            </div>
            <div class="form-group">
                <label for="position">Caption Position:</label>
                <select id="position" name="position">
                    <option value="bottom_center">Bottom Center</option>
                    <option value="bottom_left">Bottom Left</option>
                    <option value="bottom_right">Bottom Right</option>
                    <option value="middle_center">Middle Center</option>
                    <option value="top_center">Top Center</option>
                </select>
            </div>
            <div class="form-group">
                <label for="font_size">Font Size:</label>
                <input type="number" id="font_size" name="font_size" placeholder="16" value="16">
            </div>
            <div class="form-group">
                <label for="font_family">Font Family:</label>
                <input type="text" id="font_family" name="font_family" placeholder="Arial" value="Arial">
            </div>
            <div class="form-group">
                <label for="style">Caption Style:</label>
                <select id="style" name="style">
                    <option value="">Default</option>
                    <option value="karaoke">Karaoke</option>
                    <option value="highlight">Highlight</option>
                    <option value="underline">Underline</option>
                    <option value="classic">Classic</option>
                </select>
            </div>
            <div class="form-group">
                <label for="line_color">Line Color (hex):</label>
                <input type="text" id="line_color" name="line_color" placeholder="#FFFFFF" value="#FFFFFF">
            </div>
            <div class="form-group">
                <label for="outline_color">Outline Color (hex):</label>
                <input type="text" id="outline_color" name="outline_color" placeholder="#000000" value="#000000">
            </div>
            <div class="form-group">
                <label for="word_color">Word Color (hex, for karaoke style):</label>
                <input type="text" id="word_color" name="word_color" placeholder="#FFFF00">
            </div>
            <input type="hidden" name="endpoint" value="video_caption">
            <button type="submit" class="btn">Add Caption</button>
            </form>
            
            <div class="doc-section">
                <button type="button" class="doc-toggle">Show Documentation</button>
                <div class="doc-content hidden">
                    <h4>About Add Caption</h4>
                    <p>This endpoint adds captions or subtitles to video files. You can use either an external SRT file or specify caption text directly.</p>
                    
                    <h4>Parameters</h4>
                    <ul>
                        <li><strong>Video URL</strong>: URL to the video file you want to add captions to</li>
                        <li><strong>Upload Video File</strong>: Alternatively, you can upload a video file directly from your computer</li>
                        <li><strong>External SRT File URL</strong>: URL to an SRT subtitle file (optional, takes precedence over caption text)</li>
                        <li><strong>Caption Text</strong>: Direct caption text (may be ignored if SRT URL is provided)</li>
                        <li><strong>Caption Position</strong>: Where to place the captions on the video</li>
                        <li><strong>Font Size</strong>: Size of the caption text in points</li>
                        <li><strong>Font Family</strong>: Font to use for the captions (e.g., Arial, Times New Roman)</li>
                        <li><strong>Caption Style</strong>: Special styling options like karaoke or highlight effects</li>
                        <li><strong>Line Color</strong>: Color of the caption text in hex format (e.g., #FFFFFF for white)</li>
                        <li><strong>Outline Color</strong>: Color of the text outline in hex format</li>
                        <li><strong>Word Color</strong>: Color for individual words in karaoke style</li>
                    </ul>
                    
                    <h4>Supported Formats</h4>
                    <p>Supports common video formats including MP4, MOV, AVI, and more. SRT files should be in standard subtitle format.</p>
                    
                    <h4>Usage Tips</h4>
                    <ul>
                        <li>For best results, use high-contrast colors for visibility</li>
                        <li>External SRT files take precedence over direct caption text</li>
                        <li>Karaoke style is best used with word-by-word SRT files</li>
                        <li>Font sizes between 12-24 work well for most videos</li>
                    </ul>
                </div>
            </div>
        `,
        
        'video_concatenate': `
            <h2>Video Concatenate</h2>
            <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="video_urls">Video URLs (one per line):</label>
                <textarea id="video_urls" name="video_urls" placeholder="https://example.com/video1.mp4
https://example.com/video2.mp4"></textarea>
            </div>
            <div class="form-group">
                <label>Or upload video files:</label>
                <div class="drop-area" id="video-drop-area">
                    <p>Drag & drop video files here or click to select files</p>
                    <input type="file" id="video_files" name="video_files[]" multiple accept="video/*" style="display: none;">
                </div>
                <div class="file-list" id="video-file-list"></div>
            </div>
            <input type="hidden" name="endpoint" value="video_concatenate">
            <button type="submit" class="btn">Concatenate Videos</button>
            </form>
            
            <div class="doc-section">
                <button type="button" class="doc-toggle">Show Documentation</button>
                <div class="doc-content hidden">
                    <h4>About Video Concatenate</h4>
                    <p>This endpoint combines multiple video files into a single video. The videos are concatenated in the order they are provided.</p>
                    
                    <h4>Parameters</h4>
                    <ul>
                        <li><strong>Video URLs</strong>: A list of URLs to video files you want to concatenate. Enter one URL per line.</li>
                        <li><strong>Upload Video Files</strong>: Alternatively, you can upload video files directly from your computer. You can select multiple files at once.</li>
                    </ul>
                    
                    <h4>Supported Formats</h4>
                    <p>Supports common video formats including MP4, MOV, AVI, and more. All videos should have similar properties for best results.</p>
                    
                    <h4>Usage Tips</h4>
                    <ul>
                        <li>Videos are processed in the order they appear in the list</li>
                        <li>For best results, all videos should have the same resolution, frame rate, and codec</li>
                        <li>Large videos may take longer to process</li>
                        <li>The output video will have the properties of the first video in the sequence</li>
                    </ul>
                </div>
            </div>
        `,
        
        'video_thumbnail': `
            <h2>Extract Thumbnail</h2>
            <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="video_url">Video URL:</label>
                <input type="url" id="video_url" name="video_url" placeholder="https://example.com/video.mp4">
            </div>
            <div class="form-group">
                <label>Or upload a video file:</label>
                <div class="drop-area" id="thumbnail-drop-area">
                    <p>Drag & drop a video file here or click to select file</p>
                    <input type="file" id="thumbnail_file" name="thumbnail_file" accept="video/*" style="display: none;">
                </div>
                <div class="file-list" id="thumbnail-file-list"></div>
            </div>
            <div class="form-group">
                <label for="timestamp">Timestamp (seconds):</label>
                <input type="number" id="timestamp" name="timestamp" placeholder="10.5" step="0.1">
            </div>
            <input type="hidden" name="endpoint" value="video_thumbnail">
            <button type="submit" class="btn">Extract Thumbnail</button>
            </form>
            
            <div class="doc-section">
                <button type="button" class="doc-toggle">Show Documentation</button>
                <div class="doc-content hidden">
                    <h4>About Extract Thumbnail</h4>
                    <p>This endpoint extracts a single frame from a video file and saves it as an image. You can specify the exact time to extract the frame from.</p>
                    
                    <h4>Parameters</h4>
                    <ul>
                        <li><strong>Video URL</strong>: URL to the video file you want to extract a thumbnail from</li>
                        <li><strong>Upload Video File</strong>: Alternatively, you can upload a video file directly from your computer</li>
                        <li><strong>Timestamp</strong>: The time in seconds from which to extract the frame (optional, defaults to middle of video)</li>
                    </ul>
                    
                    <h4>Supported Formats</h4>
                    <p>Supports common video formats including MP4, MOV, AVI, and more. The output thumbnail is typically in JPG or PNG format.</p>
                    
                    <h4>Usage Tips</h4>
                    <ul>
                        <li>If no timestamp is provided, a frame from the middle of the video is extracted</li>
                        <li>For best results, choose a timestamp where there is clear, representative content</li>
                        <li>Higher resolution videos will produce higher quality thumbnails</li>
                        <li>Fractional seconds (e.g., 10.5) are supported for precise timing</li>
                    </ul>
                </div>
            </div>
        `,
        
        'video_cut': `
            <h2>Cut Video</h2>
            <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="video_url">Video URL:</label>
                <input type="url" id="video_url" name="video_url" placeholder="https://example.com/video.mp4">
            </div>
            <div class="form-group">
                <label>Or upload a video file:</label>
                <div class="drop-area" id="cut-drop-area">
                    <p>Drag & drop a video file here or click to select file</p>
                    <input type="file" id="cut_file" name="cut_file" accept="video/*" style="display: none;">
                </div>
                <div class="file-list" id="cut-file-list"></div>
            </div>
            <div class="form-group">
                <label for="segments">Segments (JSON format):</label>
                <textarea id="segments" name="segments" placeholder='[{"start": 0, "end": 10}, {"start": 20, "end": 30}]'></textarea>
            </div>
            <input type="hidden" name="endpoint" value="video_cut">
            <button type="submit" class="btn">Cut Video</button>
            </form>
            
            <div class="doc-section">
                <button type="button" class="doc-toggle">Show Documentation</button>
                <div class="doc-content hidden">
                    <h4>About Cut Video</h4>
                    <p>This endpoint removes specified segments from a video file, keeping only the parts you want. It's useful for editing out unwanted sections.</p>
                    
                    <h4>Parameters</h4>
                    <ul>
                        <li><strong>Video URL</strong>: URL to the video file you want to cut</li>
                        <li><strong>Upload Video File</strong>: Alternatively, you can upload a video file directly from your computer</li>
                        <li><strong>Segments</strong>: JSON array specifying which parts to keep. Each segment has a start and end time in seconds.</li>
                    </ul>
                    
                    <h4>Supported Formats</h4>
                    <p>Supports common video formats including MP4, MOV, AVI, and more.</p>
                    
                    <h4>Usage Tips</h4>
                    <ul>
                        <li>Specify segments to keep, not segments to remove</li>
                        <li>Segments must be in JSON format with "start" and "end" properties</li>
                        <li>Time values are in seconds and can include decimals (e.g., 10.5)</li>
                        <li>Multiple segments can be specified to keep non-consecutive parts</li>
                        <li>The output will be multiple video files, one for each segment</li>
                    </ul>
                </div>
            </div>
        `,
        
        'video_split': `
            <h2>Split Video</h2>
            <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="video_url">Video URL:</label>
                <input type="url" id="video_url" name="video_url" placeholder="https://example.com/video.mp4">
            </div>
            <div class="form-group">
                <label>Or upload a video file:</label>
                <div class="drop-area" id="split-drop-area">
                    <p>Drag & drop a video file here or click to select file</p>
                    <input type="file" id="split_file" name="split_file" accept="video/*" style="display: none;">
                </div>
                <div class="file-list" id="split-file-list"></div>
            </div>
            <div class="form-group">
                <label for="segments">Segments (JSON format):</label>
                <textarea id="segments" name="segments" placeholder='[{"start": 0, "end": 10}, {"start": 10, "end": 20}, {"start": 20, "end": 30}]'></textarea>
            </div>
            <input type="hidden" name="endpoint" value="video_split">
            <button type="submit" class="btn">Split Video</button>
            </form>
            
            <div class="doc-section">
                <button type="button" class="doc-toggle">Show Documentation</button>
                <div class="doc-content hidden">
                    <h4>About Split Video</h4>
                    <p>This endpoint splits a video file into multiple separate video files based on specified time segments.</p>
                    
                    <h4>Parameters</h4>
                    <ul>
                        <li><strong>Video URL</strong>: URL to the video file you want to split</li>
                        <li><strong>Upload Video File</strong>: Alternatively, you can upload a video file directly from your computer</li>
                        <li><strong>Segments</strong>: JSON array specifying where to split the video. Each segment has a start and end time in seconds.</li>
                    </ul>
                    
                    <h4>Supported Formats</h4>
                    <p>Supports common video formats including MP4, MOV, AVI, and more.</p>
                    
                    <h4>Usage Tips</h4>
                    <ul>
                        <li>Each segment will become a separate output video file</li>
                        <li>Segments must be in JSON format with "start" and "end" properties</li>
                        <li>Time values are in seconds and can include decimals (e.g., 10.5)</li>
                        <li>Segments should not overlap and should cover the desired portions of the video</li>
                        <li>The output will be multiple video files, one for each segment</li>
                    </ul>
                </div>
            </div>
        `,
        
        'video_trim': `
            <h2>Trim Video</h2>
            <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="video_url">Video URL:</label>
                <input type="url" id="video_url" name="video_url" placeholder="https://example.com/video.mp4">
            </div>
            <div class="form-group">
                <label>Or upload a video file:</label>
                <div class="drop-area" id="trim-drop-area">
                    <p>Drag & drop a video file here or click to select file</p>
                    <input type="file" id="trim_file" name="trim_file" accept="video/*" style="display: none;">
                </div>
                <div class="file-list" id="trim-file-list"></div>
            </div>
            <div class="form-group">
                <label for="start">Start Time (seconds):</label>
                <input type="number" id="start" name="start" placeholder="0" step="0.1">
            </div>
            <div class="form-group">
                <label for="end">End Time (seconds):</label>
                <input type="number" id="end" name="end" placeholder="10" step="0.1">
            </div>
            <input type="hidden" name="endpoint" value="video_trim">
            <button type="submit" class="btn">Trim Video</button>
            </form>
            
            <div class="doc-section">
                <button type="button" class="doc-toggle">Show Documentation</button>
                <div class="doc-content hidden">
                    <h4>About Trim Video</h4>
                    <p>This endpoint trims a video file to a specified start and end time, creating a shorter video clip.</p>
                    
                    <h4>Parameters</h4>
                    <ul>
                        <li><strong>Video URL</strong>: URL to the video file you want to trim</li>
                        <li><strong>Upload Video File</strong>: Alternatively, you can upload a video file directly from your computer</li>
                        <li><strong>Start Time</strong>: The time in seconds where the trimmed video should begin</li>
                        <li><strong>End Time</strong>: The time in seconds where the trimmed video should end</li>
                    </ul>
                    
                    <h4>Supported Formats</h4>
                    <p>Supports common video formats including MP4, MOV, AVI, and more.</p>
                    
                    <h4>Usage Tips</h4>
                    <ul>
                        <li>Start time must be less than end time</li>
                        <li>Time values are in seconds and can include decimals (e.g., 10.5)</li>
                        <li>The output video will contain only the portion between start and end times</li>
                        <li>For best results, choose times that align with scene changes when possible</li>
                    </ul>
                </div>
            </div>
        `,
        
        'file_upload': `
            <h2>Upload to GCP Storage</h2>
            <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>Upload files to maksimum-data bucket:</label>
                <div class="drop-area" id="upload-drop-area">
                    <p>Drag & drop files here or click to select files</p>
                    <input type="file" id="upload_files" name="upload_files[]" multiple style="display: none;">
                </div>
                <div class="file-list" id="upload-file-list"></div>
            </div>
            <input type="hidden" name="endpoint" value="file_upload">
            <button type="submit" class="btn">Upload Files</button>
            </form>
            
            <div class="doc-section">
                <button type="button" class="doc-toggle">Show Documentation</button>
                <div class="doc-content hidden">
                    <h4>About Upload to GCP Storage</h4>
                    <p>This endpoint uploads files directly to Google Cloud Storage. Files are stored in the configured bucket and made accessible via direct URLs.</p>
                    
                    <h4>Parameters</h4>
                    <ul>
                        <li><strong>Upload Files</strong>: Select one or more files from your computer to upload to Google Cloud Storage</li>
                    </ul>
                    
                    <h4>Supported Formats</h4>
                    <p>Supports uploading of any file type including images, videos, audio files, documents, and more.</p>
                    
                    <h4>Usage Tips</h4>
                    <ul>
                        <li>Uploaded files receive unique names to prevent conflicts</li>
                        <li>Each upload provides a direct URL for accessing the file</li>
                        <li>Files are stored in the configured Google Cloud Storage bucket</li>
                        <li>Multiple files can be uploaded simultaneously</li>
                        <li>Uploaded files are publicly accessible via their URLs</li>
                    </ul>
                </div>
            </div>
        `
    };
    
    // Show form when endpoint is selected
        endpointSelect.addEventListener('change', function() {
            const selectedEndpoint = this.value;
            
            // Clear the result section when changing endpoints
            if (resultDiv) {
                resultDiv.style.display = 'none';
            }
            
            if (selectedEndpoint && formTemplates[selectedEndpoint]) {
                endpointForm.innerHTML = formTemplates[selectedEndpoint];
                endpointForm.style.display = 'block';
                
                // Use setTimeout to ensure the DOM is updated before initializing drag and drop
                setTimeout(function() {
                    initDragAndDrop(selectedEndpoint);
                    // Initialize documentation toggle after the form is loaded
                    initDocumentationToggle();
                }, 0);
                
                // Add event listener to the form for loading animation
                const form = endpointForm.querySelector('form');
                if (form) {
                    form.addEventListener('submit', function(e) {
                        // Show loading animation
                        loadingDiv.style.display = 'block';
                        
                        // Hide form while loading
                        endpointForm.style.display = 'none';
                        
                        // Disable endpoint selector while loading
                        endpointSelect.disabled = true;
                    });
                }
            } else {
                endpointForm.style.display = 'none';
            }
        });
    
    // Hide loading animation when page is loaded (in case of form submission)
    if (resultDiv && resultDiv.innerHTML.trim() !== '') {
        loadingDiv.style.display = 'none';
        endpointSelect.disabled = false;
    }
    
    // Initialize documentation toggle functionality
    function initDocumentationToggle() {
        const form = document.querySelector('.endpoint-form form');
        if (form) {
            const docToggle = form.querySelector('.doc-toggle');
            const docContent = form.querySelector('.doc-content');
            
            if (docToggle && docContent) {
                // Remove any existing event listeners to prevent duplicates
                const newDocToggle = docToggle.cloneNode(true);
                docToggle.parentNode.replaceChild(newDocToggle, docToggle);
                
                newDocToggle.addEventListener('click', function() {
                    docContent.classList.toggle('hidden');
                    newDocToggle.textContent = docContent.classList.contains('hidden') ? 'Show Documentation' : 'Hide Documentation';
                });
            }
        }
    }
    
    // Global function to toggle documentation
    function toggleDocumentation(button) {
        const docContent = button.nextElementSibling;
        if (docContent) {
            docContent.classList.toggle('hidden');
            button.textContent = docContent.classList.contains('hidden') ? 'Show Documentation' : 'Hide Documentation';
        }
    }
    
    // Initialize drag and drop functionality
    function initDragAndDrop(endpoint) {
        // Map endpoint names to their correct drop area IDs
        const endpointIdMap = {
            'audio_concatenate': 'audio',
            'image_convert_video': 'image',
            'media_convert_mp3': 'media',
            'media_download': 'download',
            'media_transcribe': 'transcribe',
            'video_caption': 'caption',
            'video_concatenate': 'video',
            'video_thumbnail': 'thumbnail',
            'video_cut': 'cut',
            'video_split': 'split',
            'video_trim': 'trim',
            'file_upload': 'upload'
        };
        
        const prefix = endpointIdMap[endpoint] || endpoint.split('_')[0];
        const dropAreaId = `${prefix}-drop-area`;
        const dropArea = document.getElementById(dropAreaId);
        
        if (!dropArea) {
            console.error(`Drop area with ID ${dropAreaId} not found`);
            return;
        }
        
        const fileInput = dropArea.querySelector('input[type="file"]');
        const fileListId = `${prefix}-file-list`;
        const fileList = document.getElementById(fileListId);
        
        if (!fileInput) {
            console.error(`File input not found in drop area ${dropAreaId}`);
            return;
        }
        
        // Prevent default drag behaviors
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, preventDefaults, false);
        });
        
        // Highlight drop area when item is dragged over it
        ['dragenter', 'dragover'].forEach(eventName => {
            dropArea.addEventListener(eventName, highlight, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, unhighlight, false);
        });
        
        // Handle dropped files
        dropArea.addEventListener('drop', handleDrop, false);
        
        // Handle click on drop area
        dropArea.addEventListener('click', function() {
            fileInput.click();
        });
        
        // Handle file selection via input
        fileInput.addEventListener('change', handleFiles);
        
        // Add documentation toggle functionality
        const form = document.querySelector('.endpoint-form form');
        if (form) {
            const docToggle = form.querySelector('.doc-toggle');
            const docContent = form.querySelector('.doc-content');
            
            if (docToggle && docContent) {
                docToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    docContent.classList.toggle('hidden');
                    this.textContent = docContent.classList.contains('hidden') ? 'Show Documentation' : 'Hide Documentation';
                });
            }
        }
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        function highlight(e) {
            dropArea.classList.add('highlight');
        }
        
        function unhighlight(e) {
            dropArea.classList.remove('highlight');
        }
        
        function handleDrop(e) {
            preventDefaults(e);
            const dt = e.dataTransfer;
            const files = dt.files;
            
            console.log('Dropped files:', files);
            console.log('Number of files dropped:', files.length);
            
            if (files.length > 0) {
                // Create a new DataTransfer object to properly handle the files
                const dataTransfer = new DataTransfer();
                for (let i = 0; i < files.length; i++) {
                    dataTransfer.items.add(files[i]);
                    console.log('Added file to DataTransfer:', files[i].name);
                }
                
                // Update the file input with the dropped files
                fileInput.files = dataTransfer.files;
                
                console.log('File input files after assignment:', fileInput.files);
                console.log('File input files length:', fileInput.files.length);
                
                // Create and dispatch a change event
                const event = new Event('change', { bubbles: true });
                fileInput.dispatchEvent(event);
            }
        }
        
        function handleFiles(e) {
            const files = e.target.files;
            
            console.log('Handle files called with:', files);
            console.log('Number of files in event:', files.length);
            console.log('Event target:', e.target);
            console.log('Event target name:', e.target.name);
            
            if (files.length > 0 && fileList) {
                // Clear previous file list
                fileList.innerHTML = '';
                
                // Display selected files
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    console.log('Processing file:', file.name);
                    const fileItem = document.createElement('div');
                    fileItem.className = 'file-item';
                    fileItem.innerHTML = `
                        <span class="file-name">${escapeHtml(file.name)}</span>
                        <span class="file-size">(${formatFileSize(file.size)})</span>
                    `;
                    fileList.appendChild(fileItem);
                }
            }
        }
        
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
        
        function escapeHtml(text) {
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.replace(/[&<>"']/g, function(m) { return map[m]; });
        }
    }
});

// Global click handler for documentation toggles
document.addEventListener('click', function(e) {
    if (e.target && e.target.classList.contains('doc-toggle')) {
        e.preventDefault();
        const docContent = e.target.nextElementSibling;
        if (docContent && docContent.classList.contains('doc-content')) {
            docContent.classList.toggle('hidden');
            e.target.textContent = docContent.classList.contains('hidden') ? 'Show Documentation' : 'Hide Documentation';
        }
    }
});