// JavaScript functionality for NCA Toolkit PHP Client

// Translation function
function translate(key) {
    // ... translation code ...
    return translations[key] || key;
}

// Function to convert HH:MM:SS format to seconds
function timeToSeconds(timeStr) {
    if (!timeStr) return 0;
    
    const parts = timeStr.split(':');
    if (parts.length !== 3) {
        // If not in HH:MM:SS format, try MM:SS
        const shortParts = timeStr.split(':');
        if (shortParts.length === 2) {
            return parseInt(shortParts[0]) * 60 + parseInt(shortParts[1]);
        }
        return 0;
    }
    
    const hours = parseInt(parts[0]) || 0;
    const minutes = parseInt(parts[1]) || 0;
    const seconds = parseInt(parts[2]) || 0;
    
    return hours * 3600 + minutes * 60 + seconds;
}

// Function to add a new segment row
function addSegmentRow(containerId) {
    const container = document.getElementById(containerId);
    const newRow = document.createElement('div');
    newRow.className = 'segment-row';
    newRow.innerHTML = `
        <input type="text" class="time-input" name="start_times[]" placeholder="${translate('start_time')} (HH:MM:SS)" required>
        <span class="time-separator">-</span>
        <input type="text" class="time-input" name="end_times[]" placeholder="${translate('end_time')} (HH:MM:SS)" required>
        <button type="button" class="btn remove-segment" onclick="removeSegmentRow(this)">${translate('remove_segment')}</button>
    `;
    container.appendChild(newRow);
}

// Function to remove a segment row
function removeSegmentRow(button) {
    const container = button.parentElement;
    if (container.parentElement.children.length > 1) {
        container.remove();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const endpointSelect = document.getElementById('endpoint');
    const endpointForm = document.getElementById('endpoint-form');
    const loadingDiv = document.getElementById('loading');
    const resultDiv = document.querySelector('.result');
    
    // Update the endpoint selector options with translations
    if (endpointSelect) {
        // Clear existing options
        endpointSelect.innerHTML = '';
        
        // Add default option
        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = translate('choose_endpoint');
        endpointSelect.appendChild(defaultOption);
        
        // Add optgroups with translated labels
        const audioGroup = document.createElement('optgroup');
        audioGroup.label = 'Audio';
        const audioOption = document.createElement('option');
        audioOption.value = 'audio_concatenate';
        audioOption.textContent = translate('audio_concatenate');
        audioGroup.appendChild(audioOption);
        endpointSelect.appendChild(audioGroup);
        
        const imageGroup = document.createElement('optgroup');
        imageGroup.label = 'Image';
        const imageConvertOption = document.createElement('option');
        imageConvertOption.value = 'image_convert_video';
        imageConvertOption.textContent = translate('image_convert_video');
        imageGroup.appendChild(imageConvertOption);
        const imageScreenshotOption = document.createElement('option');
        imageScreenshotOption.value = 'image_screenshot_webpage';
        imageScreenshotOption.textContent = translate('image_screenshot_webpage');
        imageGroup.appendChild(imageScreenshotOption);
        endpointSelect.appendChild(imageGroup);
        
        const mediaGroup = document.createElement('optgroup');
        mediaGroup.label = 'Media';
        const mediaConvertOption = document.createElement('option');
        mediaConvertOption.value = 'media_convert_mp3';
        mediaConvertOption.textContent = translate('media_convert_mp3');
        mediaGroup.appendChild(mediaConvertOption);
        const mediaTranscribeOption = document.createElement('option');
        mediaTranscribeOption.value = 'media_transcribe';
        mediaTranscribeOption.textContent = translate('media_transcribe');
        mediaGroup.appendChild(mediaTranscribeOption);
        endpointSelect.appendChild(mediaGroup);
        
        const youtubeGroup = document.createElement('optgroup');
        youtubeGroup.label = 'YouTube';
        const youtubeDownloadOption = document.createElement('option');
        youtubeDownloadOption.value = 'youtube_download';
        youtubeDownloadOption.textContent = translate('youtube_download');
        youtubeGroup.appendChild(youtubeDownloadOption);
        const youtubeInfoOption = document.createElement('option');
        youtubeInfoOption.value = 'youtube_info';
        youtubeInfoOption.textContent = translate('youtube_info');
        youtubeGroup.appendChild(youtubeInfoOption);
        endpointSelect.appendChild(youtubeGroup);
        
        const videoGroup = document.createElement('optgroup');
        videoGroup.label = 'Video';
        const videoCaptionOption = document.createElement('option');
        videoCaptionOption.value = 'video_caption';
        videoCaptionOption.textContent = translate('video_caption');
        videoGroup.appendChild(videoCaptionOption);
        const videoConcatenateOption = document.createElement('option');
        videoConcatenateOption.value = 'video_concatenate';
        videoConcatenateOption.textContent = translate('video_concatenate');
        videoGroup.appendChild(videoConcatenateOption);
        const videoThumbnailOption = document.createElement('option');
        videoThumbnailOption.value = 'video_thumbnail';
        videoThumbnailOption.textContent = translate('video_thumbnail');
        videoGroup.appendChild(videoThumbnailOption);
        const videoCutOption = document.createElement('option');
        videoCutOption.value = 'video_cut';
        videoCutOption.textContent = translate('video_cut');
        videoGroup.appendChild(videoCutOption);
        const videoSplitOption = document.createElement('option');
        videoSplitOption.value = 'video_split';
        videoSplitOption.textContent = translate('video_split');
        videoGroup.appendChild(videoSplitOption);
        const videoTrimOption = document.createElement('option');
        videoTrimOption.value = 'video_trim';
        videoTrimOption.textContent = translate('video_trim');
        videoGroup.appendChild(videoTrimOption);
        endpointSelect.appendChild(videoGroup);
        
        const fileUploadGroup = document.createElement('optgroup');
        fileUploadGroup.label = 'File Upload';
        const fileUploadOption = document.createElement('option');
        fileUploadOption.value = 'file_upload';
        fileUploadOption.textContent = translate('file_upload');
        fileUploadGroup.appendChild(fileUploadOption);
        endpointSelect.appendChild(fileUploadGroup);
    }
    
    // Form templates for each endpoint with drag-and-drop support
    const formTemplates = {
        'audio_concatenate': `
            <h2>${translate('audio_concatenate')}</h2>
            <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="audio_urls">${translate('audio_urls')}</label>
                <textarea id="audio_urls" name="audio_urls" placeholder="${translate('audio_urls_placeholder')}"></textarea>
            </div>
            <div class="form-group">
                <label>${translate('or_upload_audio_files')}</label>
                <div class="drop-area" id="audio-drop-area">
                    <p>${translate('drag_drop_audio')}</p>
                    <input type="file" id="audio_files" name="audio_files[]" multiple accept="audio/*" style="display: none;">
                </div>
                <div class="file-list" id="audio-file-list"></div>
            </div>
            <input type="hidden" name="endpoint" value="audio_concatenate">
            <button type="submit" class="btn">${translate('concatenate_audio')}</button>
            </form>
            
            <div class="doc-section">
                <button type="button" class="doc-toggle">${translate('show_documentation')}</button>
                <div class="doc-content hidden">
                    <h4>${translate('about_audio_concatenate')}</h4>
                    <p>${translate('audio_concatenate_desc')}</p>
                    
                    <h4>${translate('audio_parameters')}</h4>
                    <ul>
                        <li><strong>${translate('audio_urls')}</strong>: ${translate('audio_urls_param')}</li>
                        <li><strong>${translate('audio_upload_param')}</strong>: ${translate('audio_upload_param')}</li>
                    </ul>
                    
                    <h4>${translate('audio_formats')}</h4>
                    <p>${translate('audio_formats_desc')}</p>
                    
                    <h4>${translate('audio_tips')}</h4>
                    <ul>
                        <li>${translate('audio_tips_1')}</li>
                        <li>${translate('audio_tips_2')}</li>
                        <li>${translate('audio_tips_3')}</li>
                    </ul>
                </div>
            </div>
        `,
        
        'image_convert_video': `
            <h2>${translate('image_convert_video')}</h2>
            <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="image_url">${translate('image_url')}</label>
                <input type="url" id="image_url" name="image_url" placeholder="${translate('image_url_placeholder')}">
            </div>
            <div class="form-group">
                <label>${translate('or_upload_image_file')}</label>
                <div class="drop-area" id="image-drop-area">
                    <p>${translate('drag_drop_image')}</p>
                    <input type="file" id="image_file" name="image_file" accept="image/*" style="display: none;">
                </div>
                <div class="file-list" id="image-file-list"></div>
            </div>
            <input type="hidden" name="endpoint" value="image_convert_video">
            <button type="submit" class="btn">${translate('convert_to_video')}</button>
            </form>
            
            <div class="doc-section">
                <button type="button" class="doc-toggle">${translate('show_documentation')}</button>
                <div class="doc-content hidden">
                    <h4>${translate('about_image_convert_video')}</h4>
                    <p>${translate('image_convert_video_desc')}</p>
                    
                    <h4>${translate('image_parameters')}</h4>
                    <ul>
                        <li><strong>${translate('image_url')}</strong>: ${translate('image_url_param')}</li>
                        <li><strong>${translate('image_upload_param')}</strong>: ${translate('image_upload_param')}</li>
                    </ul>
                    
                    <h4>${translate('image_formats')}</h4>
                    <p>${translate('image_formats_desc')}</p>
                    
                    <h4>${translate('image_tips')}</h4>
                    <ul>
                        <li>${translate('image_tips_1')}</li>
                        <li>${translate('image_tips_2')}</li>
                        <li>${translate('image_tips_3')}</li>
                    </ul>
                </div>
            </div>
        `,
        
        'image_screenshot_webpage': `
            <h2>${translate('image_screenshot_webpage')}</h2>
            <form method="post">
            <div class="form-group">
                <label for="url">${translate('webpage_url')}</label>
                <input type="url" id="url" name="url" placeholder="${translate('webpage_url_placeholder')}">
            </div>
            <div class="form-group">
                <label for="viewport_width">${translate('viewport_width')}</label>
                <input type="number" id="viewport_width" name="viewport_width" placeholder="1920">
            </div>
            <div class="form-group">
                <label for="viewport_height">${translate('viewport_height')}</label>
                <input type="number" id="viewport_height" name="viewport_height" placeholder="1080">
            </div>
            <input type="hidden" name="endpoint" value="image_screenshot_webpage">
            <button type="submit" class="btn">${translate('take_screenshot')}</button>
            </form>
            
            <div class="doc-section">
                <button type="button" class="doc-toggle">${translate('show_documentation')}</button>
                <div class="doc-content hidden">
                    <h4>${translate('about_webpage_screenshot')}</h4>
                    <p>${translate('webpage_screenshot_desc')}</p>
                    
                    <h4>${translate('webpage_parameters')}</h4>
                    <ul>
                        <li><strong>${translate('webpage_url')}</strong>: ${translate('webpage_url_param')}</li>
                        <li><strong>${translate('viewport_width')}</strong>: ${translate('viewport_width_param')}</li>
                        <li><strong>${translate('viewport_height')}</strong>: ${translate('viewport_height_param')}</li>
                    </ul>
                    
                    <h4>${translate('webpage_formats')}</h4>
                    <p>${translate('webpage_formats_desc')}</p>
                    
                    <h4>${translate('webpage_tips')}</h4>
                    <ul>
                        <li>${translate('webpage_tips_1')}</li>
                        <li>${translate('webpage_tips_2')}</li>
                        <li>${translate('webpage_tips_3')}</li>
                        <li>${translate('webpage_tips_4')}</li>
                    </ul>
                </div>
            </div>
        `,
        
        'media_convert_mp3': `
            <h2>${translate('media_convert_mp3')}</h2>
            <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="media_url">${translate('media_url')}</label>
                <input type="url" id="media_url" name="media_url" placeholder="${translate('media_url_placeholder')}">
            </div>
            <div class="form-group">
                <label>${translate('or_upload_media_file')}</label>
                <div class="drop-area" id="media-drop-area">
                    <p>${translate('drag_drop_media')}</p>
                    <input type="file" id="media_file" name="media_file" accept="audio/*,video/*" style="display: none;">
                </div>
                <div class="file-list" id="media-file-list"></div>
            </div>
            <input type="hidden" name="endpoint" value="media_convert_mp3">
            <button type="submit" class="btn">${translate('convert_to_mp3')}</button>
            </form>
            
            <div class="doc-section">
                <button type="button" class="doc-toggle">${translate('show_documentation')}</button>
                <div class="doc-content hidden">
                    <h4>${translate('about_media_convert_mp3')}</h4>
                    <p>${translate('media_convert_mp3_desc')}</p>
                    
                    <h4>${translate('media_convert_mp3_parameters')}</h4>
                    <ul>
                        <li><strong>${translate('media_url')}</strong>: ${translate('media_url_param')}</li>
                        <li><strong>${translate('media_upload_param')}</strong>: ${translate('media_upload_param')}</li>
                    </ul>
                    
                    <h4>${translate('media_convert_mp3_formats')}</h4>
                    <p>${translate('media_convert_mp3_formats_desc')}</p>
                    
                    <h4>${translate('media_convert_mp3_tips')}</h4>
                    <ul>
                        <li>${translate('media_convert_mp3_tips_1')}</li>
                        <li>${translate('media_convert_mp3_tips_2')}</li>
                        <li>${translate('media_convert_mp3_tips_3')}</li>
                        <li>${translate('media_convert_mp3_tips_4')}</li>
                    </ul>
                </div>
            </div>
        `,
        
        
        
        'media_transcribe': `
            <h2>${translate('media_transcribe')}</h2>
            <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="media_url">${translate('media_url')}</label>
                <input type="url" id="media_url" name="media_url" placeholder="${translate('media_url_placeholder')}">
            </div>
            <div class="form-group">
                <label>${translate('or_upload_media_file')}</label>
                <div class="drop-area" id="transcribe-drop-area">
                    <p>${translate('drag_drop_transcribe')}</p>
                    <input type="file" id="transcribe_file" name="transcribe_file" accept="audio/*,video/*" style="display: none;">
                </div>
                <div class="file-list" id="transcribe-file-list"></div>
            </div>
            <div class="form-group">
                <label for="srt_format">
                    <input type="checkbox" id="srt_format" name="srt_format" value="1" checked>
                    ${translate('get_segmented_srt')}
                </label>
            </div>
            <div class="form-group">
                <label for="direct_output">
                    <input type="checkbox" id="direct_output" name="direct_output" value="1">
                    ${translate('show_direct_output')}
                </label>
            </div>
            <div class="form-group">
                <label for="words_per_line">${translate('words_per_line')}</label>
                <input type="number" id="words_per_line" name="words_per_line" min="1" placeholder="${translate('words_per_line_placeholder')}">
                <small>${translate('words_per_line_note')}</small>
            </div>
            <input type="hidden" name="endpoint" value="media_transcribe">
            <button type="submit" class="btn">${translate('transcribe_media')}</button>
            </form>
            
            <div class="doc-section">
                <button type="button" class="doc-toggle">${translate('show_documentation')}</button>
                <div class="doc-content hidden">
                    <h4>${translate('about_media_transcribe')}</h4>
                    <p>${translate('media_transcribe_desc')}</p>
                    
                    <h4>${translate('media_transcribe_parameters')}</h4>
                    <ul>
                        <li><strong>${translate('media_transcribe_url_param')}</strong>: ${translate('media_transcribe_url_param')}</li>
                        <li><strong>${translate('media_transcribe_upload_param')}</strong>: ${translate('media_transcribe_upload_param')}</li>
                        <li><strong>${translate('media_transcribe_srt_param')}</strong>: ${translate('media_transcribe_srt_param')}</li>
                        <li><strong>${translate('media_transcribe_output_param')}</strong>: ${translate('media_transcribe_output_param')}</li>
                        <li><strong>${translate('words_per_line')}</strong>: ${translate('words_per_line_param')}</li>
                    </ul>
                    
                    <h4>${translate('media_transcribe_formats')}</h4>
                    <p>${translate('media_transcribe_formats_desc')}</p>
                    
                    <h4>${translate('media_transcribe_tips')}</h4>
                    <ul>
                        <li>${translate('media_transcribe_tips_1')}</li>
                        <li>${translate('media_transcribe_tips_2')}</li>
                        <li>${translate('media_transcribe_tips_3')}</li>
                        <li>${translate('media_transcribe_tips_4')}</li>
                    </ul>
                </div>
            </div>
        `,
        
        'youtube_download': `
            <h2>${translate('youtube_download')}</h2>
            <form method="post">
            <div class="form-group">
                <label for="url">${translate('youtube_url')}</label>
                <input type="url" id="url" name="url" placeholder="${translate('youtube_url_placeholder')}" required>
            </div>
            <div class="form-group">
                <label for="cookies">${translate('cookies_optional')}</label>
                <textarea id="cookies" name="cookies" placeholder="${translate('youtube_cookies_placeholder')}"></textarea>
                <small>${translate('youtube_cookies_note')}</small>
            </div>
            <input type="hidden" name="endpoint" value="youtube_download">
            <button type="submit" class="btn">${translate('download_youtube')}</button>
            </form>
            
            <div class="doc-section">
                <button type="button" class="doc-toggle">${translate('show_documentation')}</button>
                <div class="doc-content hidden">
                    <h4>${translate('about_youtube_download')}</h4>
                    <p>${translate('youtube_download_desc')}</p>
                    
                    <h4>${translate('youtube_download_parameters')}</h4>
                    <ul>
                        <li><strong>${translate('youtube_download_url_param')}</strong>: ${translate('youtube_download_url_param')} (${translate('required')})</li>
                        <li><strong>${translate('youtube_download_cookies_param')}</strong>: ${translate('youtube_download_cookies_param')}</li>
                    </ul>
                    
                    <h4>${translate('youtube_download_formats')}</h4>
                    <p>${translate('youtube_download_formats_desc')}</p>
                    
                    <h4>${translate('youtube_download_tips')}</h4>
                    <ul>
                        <li>${translate('youtube_download_tips_1')}</li>
                        <li>${translate('youtube_download_tips_2')}</li>
                        <li>${translate('youtube_download_tips_3')}</li>
                        <li>${translate('youtube_download_tips_4')}</li>
                        <li>${translate('youtube_bot_detection')}</li>
                        <li>${translate('youtube_cookies_howto')}</li>
                    </ul>
                </div>
            </div>
        `,
        
        'youtube_info': `
            <h2>${translate('youtube_info')}</h2>
            <form method="post">
            <div class="form-group">
                <label for="url">${translate('youtube_url')}</label>
                <input type="url" id="url" name="url" placeholder="${translate('youtube_info_url_placeholder')}" required>
            </div>
            <div class="form-group">
                <label for="cookies">${translate('cookies_optional')}</label>
                <textarea id="cookies" name="cookies" placeholder="${translate('youtube_info_cookies_placeholder')}"></textarea>
            </div>
            <input type="hidden" name="endpoint" value="youtube_info">
            <button type="submit" class="btn">${translate('get_youtube_info')}</button>
            </form>
            
            <div class="doc-section">
                <button type="button" class="doc-toggle">${translate('show_documentation')}</button>
                <div class="doc-content hidden">
                    <h4>${translate('about_youtube_info')}</h4>
                    <p>${translate('youtube_info_desc')}</p>
                    
                    <h4>${translate('youtube_info_parameters')}</h4>
                    <ul>
                        <li><strong>${translate('youtube_info_url_param')}</strong>: ${translate('youtube_info_url_param')} (${translate('required')})</li>
                        <li><strong>${translate('youtube_info_cookies_param')}</strong>: ${translate('youtube_info_cookies_param')}</li>
                    </ul>
                    
                    <h4>${translate('youtube_info_provided')}</h4>
                    <p>${translate('youtube_info_provided_desc')}</p>
                    <ul>
                        <li>${translate('youtube_info_provided_1')}</li>
                        <li>${translate('youtube_info_provided_2')}</li>
                        <li>${translate('youtube_info_provided_3')}</li>
                        <li>${translate('youtube_info_provided_4')}</li>
                        <li>${translate('youtube_info_provided_5')}</li>
                    </ul>
                    
                    <h4>${translate('youtube_info_tips')}</h4>
                    <ul>
                        <li>${translate('youtube_info_tips_1')}</li>
                        <li>${translate('youtube_info_tips_2')}</li>
                        <li>${translate('youtube_info_tips_3')}</li>
                        <li>${translate('youtube_info_tips_4')}</li>
                        <li>${translate('youtube_bot_detection')}</li>
                        <li>${translate('youtube_cookies_howto')}</li>
                    </ul>
                </div>
            </div>
        `,
        
        'video_caption': `
            <h2>${translate('video_caption')}</h2>
            <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="video_url">${translate('video_url')}</label>
                <input type="url" id="video_url" name="video_url" placeholder="${translate('video_url_placeholder')}">
            </div>
            <div class="form-group">
                <label>${translate('or_upload_video_file')}</label>
                <div class="drop-area" id="caption-drop-area">
                    <p>${translate('drag_drop_caption')}</p>
                    <input type="file" id="caption_file" name="caption_file" accept="video/*" style="display: none;">
                </div>
                <div class="file-list" id="caption-file-list"></div>
            </div>
            <div class="form-group">
                <label for="srt_url">${translate('external_srt_url')}</label>
                <input type="url" id="srt_url" name="srt_url" placeholder="${translate('srt_url_placeholder')}">
                <small>${translate('external_srt_note')}</small>
            </div>
            <div class="form-group">
                <label for="caption_text">${translate('caption_text')}</label>
                <textarea id="caption_text" name="caption_text" placeholder="${translate('caption_text')}"></textarea>
            </div>
            <div class="form-group">
                <label for="position">${translate('caption_position')}</label>
                <select id="position" name="position">
                    <option value="bottom_center">${translate('position_bottom_center')}</option>
                    <option value="bottom_left">${translate('position_bottom_left')}</option>
                    <option value="bottom_right">${translate('position_bottom_right')}</option>
                    <option value="middle_center">${translate('position_middle_center')}</option>
                    <option value="top_center">${translate('position_top_center')}</option>
                </select>
            </div>
            <div class="form-group">
                <label for="font_size">${translate('font_size')}</label>
                <input type="number" id="font_size" name="font_size" placeholder="16" value="16">
            </div>
            <div class="form-group">
                <label for="font_family">${translate('font_family')}</label>
                <input type="text" id="font_family" name="font_family" placeholder="Arial" value="Arial">
            </div>
            <div class="form-group">
                <label for="style">${translate('caption_style')}</label>
                <select id="style" name="style">
                    <option value="">${translate('style_default')}</option>
                    <option value="karaoke">${translate('style_karaoke')}</option>
                    <option value="highlight">${translate('style_highlight')}</option>
                    <option value="underline">${translate('style_underline')}</option>
                    <option value="classic">${translate('style_classic')}</option>
                    <option value="word_by_word">${translate('style_word_by_word')}</option>
                </select>
            </div>
            <div class="form-group">
                <label for="line_color">${translate('line_color')}</label>
                <input type="text" id="line_color" name="line_color" placeholder="#FFFFFF" value="#FFFFFF">
            </div>
            <div class="form-group">
                <label for="outline_color">${translate('outline_color')}</label>
                <input type="text" id="outline_color" name="outline_color" placeholder="#000000" value="#000000">
            </div>
            <div class="form-group">
                <label for="word_color">${translate('word_color')}</label>
                <input type="text" id="word_color" name="word_color" placeholder="#FFFF00">
            </div>
            <input type="hidden" name="endpoint" value="video_caption">
            <button type="submit" class="btn">${translate('add_caption')}</button>
            </form>
            
            <div class="doc-section">
                <button type="button" class="doc-toggle">${translate('show_documentation')}</button>
                <div class="doc-content hidden">
                    <h4>${translate('about_video_caption')}</h4>
                    <p>${translate('video_caption_desc')}</p>
                    
                    <h4>${translate('video_caption_parameters')}</h4>
                    <ul>
                        <li><strong>${translate('video_caption_url_param')}</strong>: ${translate('video_caption_url_param')}</li>
                        <li><strong>${translate('video_caption_upload_param')}</strong>: ${translate('video_caption_upload_param')}</li>
                        <li><strong>${translate('video_caption_srt_url_param')}</strong>: ${translate('video_caption_srt_url_param')}</li>
                        <li><strong>${translate('video_caption_text_param')}</strong>: ${translate('video_caption_text_param')}</li>
                        <li><strong>${translate('video_caption_position_param')}</strong>: ${translate('video_caption_position_param')}</li>
                        <li><strong>${translate('video_caption_font_size_param')}</strong>: ${translate('video_caption_font_size_param')}</li>
                        <li><strong>${translate('video_caption_font_family_param')}</strong>: ${translate('video_caption_font_family_param')}</li>
                        <li><strong>${translate('video_caption_style_param')}</strong>: ${translate('video_caption_style_param')}</li>
                        <li><strong>${translate('video_caption_line_color_param')}</strong>: ${translate('video_caption_line_color_param')}</li>
                        <li><strong>${translate('video_caption_outline_color_param')}</strong>: ${translate('video_caption_outline_color_param')}</li>
                        <li><strong>${translate('video_caption_word_color_param')}</strong>: ${translate('video_caption_word_color_param')}</li>
                    </ul>
                    
                    <h4>${translate('video_caption_formats')}</h4>
                    <p>${translate('video_caption_formats_desc')}</p>
                    
                    <h4>${translate('video_caption_tips')}</h4>
                    <ul>
                        <li>${translate('video_caption_tips_1')}</li>
                        <li>${translate('video_caption_tips_2')}</li>
                        <li>${translate('video_caption_tips_3')}</li>
                        <li>${translate('video_caption_tips_4')}</li>
                    </ul>
                </div>
            </div>
        `,
        
        'video_concatenate': `
            <h2>${translate('video_concatenate')}</h2>
            <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="video_urls">${translate('video_urls')}</label>
                <textarea id="video_urls" name="video_urls" placeholder="${translate('video_urls_placeholder')}"></textarea>
            </div>
            <div class="form-group">
                <label>${translate('or_upload_video_files')}</label>
                <div class="drop-area" id="video-drop-area">
                    <p>${translate('drag_drop_video')}</p>
                    <input type="file" id="video_files" name="video_files[]" multiple accept="video/*" style="display: none;">
                </div>
                <div class="file-list" id="video-file-list"></div>
            </div>
            <input type="hidden" name="endpoint" value="video_concatenate">
            <button type="submit" class="btn">${translate('concatenate_videos')}</button>
            </form>
            
            <div class="doc-section">
                <button type="button" class="doc-toggle">${translate('show_documentation')}</button>
                <div class="doc-content hidden">
                    <h4>${translate('about_video_concatenate')}</h4>
                    <p>${translate('video_concatenate_desc')}</p>
                    
                    <h4>${translate('video_concatenate_parameters')}</h4>
                    <ul>
                        <li><strong>${translate('video_concatenate_urls_param')}</strong>: ${translate('video_concatenate_urls_param')}</li>
                        <li><strong>${translate('video_concatenate_upload_param')}</strong>: ${translate('video_concatenate_upload_param')}</li>
                    </ul>
                    
                    <h4>${translate('video_concatenate_formats')}</h4>
                    <p>${translate('video_concatenate_formats_desc')}</p>
                    
                    <h4>${translate('video_concatenate_tips')}</h4>
                    <ul>
                        <li>${translate('video_concatenate_tips_1')}</li>
                        <li>${translate('video_concatenate_tips_2')}</li>
                        <li>${translate('video_concatenate_tips_3')}</li>
                        <li>${translate('video_concatenate_tips_4')}</li>
                    </ul>
                </div>
            </div>
        `,
        
        'video_thumbnail': `
            <h2>${translate('video_thumbnail')}</h2>
            <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="video_url">${translate('video_url')}</label>
                <input type="url" id="video_url" name="video_url" placeholder="${translate('video_url_placeholder')}">
            </div>
            <div class="form-group">
                <label>${translate('or_upload_video_file')}</label>
                <div class="drop-area" id="thumbnail-drop-area">
                    <p>${translate('drag_drop_thumbnail')}</p>
                    <input type="file" id="thumbnail_file" name="thumbnail_file" accept="video/*" style="display: none;">
                </div>
                <div class="file-list" id="thumbnail-file-list"></div>
            </div>
            <div class="form-group">
                <label for="timestamp">${translate('timestamp_seconds')}</label>
                <input type="number" id="timestamp" name="timestamp" placeholder="10.5" step="0.1">
            </div>
            <input type="hidden" name="endpoint" value="video_thumbnail">
            <button type="submit" class="btn">${translate('extract_thumbnail')}</button>
            </form>
            
            <div class="doc-section">
                <button type="button" class="doc-toggle">${translate('show_documentation')}</button>
                <div class="doc-content hidden">
                    <h4>${translate('about_video_thumbnail')}</h4>
                    <p>${translate('video_thumbnail_desc')}</p>
                    
                    <h4>${translate('video_thumbnail_parameters')}</h4>
                    <ul>
                        <li><strong>${translate('video_thumbnail_url_param')}</strong>: ${translate('video_thumbnail_url_param')}</li>
                        <li><strong>${translate('video_thumbnail_upload_param')}</strong>: ${translate('video_thumbnail_upload_param')}</li>
                        <li><strong>${translate('video_thumbnail_timestamp_param')}</strong>: ${translate('video_thumbnail_timestamp_param')}</li>
                    </ul>
                    
                    <h4>${translate('video_thumbnail_formats')}</h4>
                    <p>${translate('video_thumbnail_formats_desc')}</p>
                    
                    <h4>${translate('video_thumbnail_tips')}</h4>
                    <ul>
                        <li>${translate('video_thumbnail_tips_1')}</li>
                        <li>${translate('video_thumbnail_tips_2')}</li>
                        <li>${translate('video_thumbnail_tips_3')}</li>
                        <li>${translate('video_thumbnail_tips_4')}</li>
                    </ul>
                </div>
            </div>
        `,
        
        'video_cut': `
            <h2>${translate('video_cut')}</h2>
            <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="video_url">${translate('video_url')}</label>
                <input type="url" id="video_url" name="video_url" placeholder="${translate('video_url_placeholder')}">
            </div>
            <div class="form-group">
                <label>${translate('or_upload_video_file')}</label>
                <div class="drop-area" id="cut-drop-area">
                    <p>${translate('drag_drop_cut')}</p>
                    <input type="file" id="cut_file" name="cut_file" accept="video/*" style="display: none;">
                </div>
                <div class="file-list" id="cut-file-list"></div>
            </div>
            <div class="form-group">
                <label>${translate('segments')}</label>
                <div id="segments-container">
                    <div class="segment-row">
                        <input type="text" class="time-input" name="start_times[]" placeholder="${translate('start_time')} (HH:MM:SS)" required>
                        <span class="time-separator">-</span>
                        <input type="text" class="time-input" name="end_times[]" placeholder="${translate('end_time')} (HH:MM:SS)" required>
                    </div>
                </div>
                <button type="button" class="btn btn-secondary" onclick="addSegmentRow('segments-container')">${translate('add_segment')}</button>
            </div>
            <input type="hidden" name="endpoint" value="video_cut">
            <button type="submit" class="btn">${translate('cut_video')}</button>
            </form>
            
            <div class="doc-section">
                <button type="button" class="doc-toggle">${translate('show_documentation')}</button>
                <div class="doc-content hidden">
                    <h4>${translate('about_video_cut')}</h4>
                    <p>${translate('video_cut_desc')}</p>
                    
                    <h4>${translate('video_cut_parameters')}</h4>
                    <ul>
                        <li><strong>${translate('video_cut_url_param')}</strong>: ${translate('video_cut_url_param')}</li>
                        <li><strong>${translate('video_cut_upload_param')}</strong>: ${translate('video_cut_upload_param')}</li>
                        <li><strong>${translate('video_cut_segments_param')}</strong>: ${translate('video_cut_segments_param')}</li>
                    </ul>
                    
                    <h4>${translate('video_cut_formats')}</h4>
                    <p>${translate('video_cut_formats_desc')}</p>
                    
                    <h4>${translate('video_cut_tips')}</h4>
                    <ul>
                        <li>${translate('video_cut_tips_1')}</li>
                        <li>${translate('video_cut_tips_2')}</li>
                        <li>${translate('video_cut_tips_3')}</li>
                        <li>${translate('video_cut_tips_4')}</li>
                        <li>${translate('video_cut_tips_5')}</li>
                    </ul>
                </div>
            </div>
        `,
        
        'video_split': `
            <h2>${translate('video_split')}</h2>
            <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="video_url">${translate('video_url')}</label>
                <input type="url" id="video_url" name="video_url" placeholder="${translate('video_url_placeholder')}">
            </div>
            <div class="form-group">
                <label>${translate('or_upload_video_file')}</label>
                <div class="drop-area" id="split-drop-area">
                    <p>${translate('drag_drop_split')}</p>
                    <input type="file" id="split_file" name="split_file" accept="video/*" style="display: none;">
                </div>
                <div class="file-list" id="split-file-list"></div>
            </div>
            <div class="form-group">
                <label>${translate('segments')}</label>
                <div id="segments-container">
                    <div class="segment-row">
                        <input type="text" class="time-input" name="start_times[]" placeholder="${translate('start_time')} (HH:MM:SS)" required>
                        <span class="time-separator">-</span>
                        <input type="text" class="time-input" name="end_times[]" placeholder="${translate('end_time')} (HH:MM:SS)" required>
                    </div>
                </div>
                <button type="button" class="btn btn-secondary" onclick="addSegmentRow('segments-container')">${translate('add_segment')}</button>
            </div>
            <input type="hidden" name="endpoint" value="video_split">
            <button type="submit" class="btn">${translate('split_video')}</button>
            </form>
            
            <div class="doc-section">
                <button type="button" class="doc-toggle">${translate('show_documentation')}</button>
                <div class="doc-content hidden">
                    <h4>${translate('about_video_split')}</h4>
                    <p>${translate('video_split_desc')}</p>
                    
                    <h4>${translate('video_split_parameters')}</h4>
                    <ul>
                        <li><strong>${translate('video_split_url_param')}</strong>: ${translate('video_split_url_param')}</li>
                        <li><strong>${translate('video_split_upload_param')}</strong>: ${translate('video_split_upload_param')}</li>
                        <li><strong>${translate('video_split_segments_param')}</strong>: ${translate('video_split_segments_param')}</li>
                    </ul>
                    
                    <h4>${translate('video_split_formats')}</h4>
                    <p>${translate('video_split_formats_desc')}</p>
                    
                    <h4>${translate('video_split_tips')}</h4>
                    <ul>
                        <li>${translate('video_split_tips_1')}</li>
                        <li>${translate('video_split_tips_2')}</li>
                        <li>${translate('video_split_tips_3')}</li>
                        <li>${translate('video_split_tips_4')}</li>
                        <li>${translate('video_split_tips_5')}</li>
                    </ul>
                </div>
            </div>
        `,
        
        'video_trim': `
            <h2>${translate('video_trim')}</h2>
            <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="video_url">${translate('video_url')}</label>
                <input type="url" id="video_url" name="video_url" placeholder="${translate('video_url_placeholder')}">
            </div>
            <div class="form-group">
                <label>${translate('or_upload_video_file')}</label>
                <div class="drop-area" id="trim-drop-area">
                    <p>${translate('drag_drop_trim')}</p>
                    <input type="file" id="trim_file" name="trim_file" accept="video/*" style="display: none;">
                </div>
                <div class="file-list" id="trim-file-list"></div>
            </div>
            <div class="form-group">
                <label for="start">${translate('start_time')}</label>
                <input type="number" id="start" name="start" placeholder="0" step="0.1">
            </div>
            <div class="form-group">
                <label for="end">${translate('end_time')}</label>
                <input type="number" id="end" name="end" placeholder="10" step="0.1">
            </div>
            <input type="hidden" name="endpoint" value="video_trim">
            <button type="submit" class="btn">${translate('trim_video')}</button>
            </form>
            
            <div class="doc-section">
                <button type="button" class="doc-toggle">${translate('show_documentation')}</button>
                <div class="doc-content hidden">
                    <h4>${translate('about_video_trim')}</h4>
                    <p>${translate('video_trim_desc')}</p>
                    
                    <h4>${translate('video_trim_parameters')}</h4>
                    <ul>
                        <li><strong>${translate('video_trim_url_param')}</strong>: ${translate('video_trim_url_param')}</li>
                        <li><strong>${translate('video_trim_upload_param')}</strong>: ${translate('video_trim_upload_param')}</li>
                        <li><strong>${translate('video_trim_start_param')}</strong>: ${translate('video_trim_start_param')}</li>
                        <li><strong>${translate('video_trim_end_param')}</strong>: ${translate('video_trim_end_param')}</li>
                    </ul>
                    
                    <h4>${translate('video_trim_formats')}</h4>
                    <p>${translate('video_trim_formats_desc')}</p>
                    
                    <h4>${translate('video_trim_tips')}</h4>
                    <ul>
                        <li>${translate('video_trim_tips_1')}</li>
                        <li>${translate('video_trim_tips_2')}</li>
                        <li>${translate('video_trim_tips_3')}</li>
                        <li>${translate('video_trim_tips_4')}</li>
                    </ul>
                </div>
            </div>
        `,
        
        'file_upload': `
            <h2>${translate('file_upload')}</h2>
            <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>${translate('upload_files_to_bucket')}</label>
                <div class="drop-area" id="upload-drop-area">
                    <p>${translate('drag_drop_upload')}</p>
                    <input type="file" id="upload_files" name="upload_files[]" multiple style="display: none;">
                </div>
                <div class="file-list" id="upload-file-list"></div>
            </div>
            <input type="hidden" name="endpoint" value="file_upload">
            <button type="submit" class="btn">${translate('upload_files')}</button>
            </form>
            
            <div class="doc-section">
                <button type="button" class="doc-toggle">${translate('show_documentation')}</button>
                <div class="doc-content hidden">
                    <h4>${translate('about_file_upload')}</h4>
                    <p>${translate('file_upload_desc')}</p>
                    
                    <h4>${translate('file_upload_parameters')}</h4>
                    <ul>
                        <li><strong>${translate('file_upload_files_param')}</strong>: ${translate('file_upload_files_param')}</li>
                    </ul>
                    
                    <h4>${translate('file_upload_formats')}</h4>
                    <p>${translate('file_upload_formats_desc')}</p>
                    
                    <h4>${translate('file_upload_tips')}</h4>
                    <ul>
                        <li>${translate('file_upload_tips_1')}</li>
                        <li>${translate('file_upload_tips_2')}</li>
                        <li>${translate('file_upload_tips_3')}</li>
                        <li>${translate('file_upload_tips_4')}</li>
                        <li>${translate('file_upload_tips_5')}</li>
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