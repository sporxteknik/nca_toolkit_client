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
                <label for="translate">
                    <input type="checkbox" id="translate" name="translate" value="1">
                    Translate transcription
                </label>
            </div>
            <div class="form-group">
                <label for="srt_format">
                    <input type="checkbox" id="srt_format" name="srt_format" value="1">
                    Get segmented SRT format
                </label>
            </div>
            <input type="hidden" name="endpoint" value="media_transcribe">
            <button type="submit" class="btn">Transcribe Media</button>
            </form>
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
                <label for="caption_text">Caption Text (Note: This may be ignored by the API):</label>
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
            <input type="hidden" name="endpoint" value="video_caption">
            <button type="submit" class="btn">Add Caption</button>
            </form>
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
            'video_trim': 'trim'
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