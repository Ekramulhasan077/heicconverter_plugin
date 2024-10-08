<?php
/*
Plugin Name: HEIC Converter
Description: HEIC Converter is a powerful WordPress plugin designed to effortlessly convert high-quality HEIC images into JPG, PNG, and PDF formats. Perfect for photographers, bloggers, and web admins, this plugin ensures seamless compatibility with all major image formats, improving site performance and user experience.
Version: 1.0.3
Author: Md Turjo
Author URI: https://www.facebook.com/tarikulislambd.me/
*/

// Enqueue the CSS and JavaScript files
function custom_plugin_enqueue_files() {
    wp_enqueue_style('custom-plugin-css', plugin_dir_url(__FILE__) . 'css/style.css');
    if (is_page('heic-to-pdf')) {
        wp_enqueue_script('custom-plugin-js', plugin_dir_url(__FILE__) . 'js/pdf_a_script.js', array('jquery'), null, true);
    }else if(is_page('heic-to-png')){
        wp_enqueue_script('custom-plugin-js', plugin_dir_url(__FILE__) . 'js/png_a_script.js', array('jquery'), null, true);
    }else{
        wp_enqueue_script('custom-plugin-js', plugin_dir_url(__FILE__) . 'js/jpg_h_script.js', array('jquery'), null, true);
    }
    
}
add_action('wp_enqueue_scripts', 'custom_plugin_enqueue_files');

// Add custom HTML to pages and posts
function custom_plugin_add_html($content) {
    if (is_page('heic-to-png')) {
        
        $custom_html = '
        <div class="light">
        <div class="d-flex format-nav-link">
        <ul>
            <li><a href="/">HEIC to JPG</a></li>
            <li><a href="../heic-to-png" class="active">HEIC to PNG</a></li>
            <li><a href="../heic-to-pdf">HEIC to PDF</a></li>
        </ul>
    </div>
    <form id="convertForm" method="post" enctype="multipart/form-data">
        <div class="d-block cursor-pointer hf-main-content"
            style="pointer-events: none;">
            <label class="d-block">
                <input id="choose_image" maxuploads="2" accept=".heic" type="file" name="image" hidden
                    onchange="loadFile(event)" multiple />
                <div class="d-flex w-100 mt-2">
                    <span id="convert-button" style="pointer-events: painted;"
                        class="mt-2 d-flex btn bg-gradient text-white text-xl border rounded-md px-5 py-2 ml-auto mr-2">
                        <i class="zp zp-upload mr-2"></i>Upload file</span>
                    <a id="again-button" href="../heic-to-png" style="pointer-events: none; opacity: 0.5;"
                        class="mt-2 d-flex btn bg-gradient text-white text-xl border rounded-md px-5 py-2 mr-auto ml-2">
                        <i class="zp zp-clear mr-2"></i>Clear</a>
                </div>
            </label>
            <div id="drop_down_body"
                style="min-height: 170px; align-items: center; border-style: dashed; border-color: #b0b1b3;"
                class="d-flex m-3 rounded justify-center cursor-pointer">

                <span id="drag-drop-text" class="mx-auto"
                    style="user-select: none;text-align: center;line-height: 2;font-weight: lighter;">Drag & Drop
                    Files Here</span>


                <div class=" w-100 drag-display grid-container" id="selected-image-grid">




                </div>
            </div>

            <div class="d-flex my-4">
                <span id="g_drive_btn" onclick="downloadAll()" class="mx-auto py-2 px-3 d-flex btn"><i
                        class="mr-2 zp zp-download"></i>Download All <span class="download-count">0</span></span>

            </div>
            <span id="selected-file"></span>
        </div>
        <!-- <div class="d-flex mt-4 w-100">
            <div class="w-50 mr-2">
                <label class="mb-1">Quality</label>
                <input id="quality_box" type="number" name="quality" min="0" max="100" value="70"
                    class="w-100 bg-gray-100 rounded-md">
            </div>
            <div class="w-50 ml-2">
                <label class="mb-1">Format</label>
                <select name="format" id="select_format" class="w-100 bg-gray-100 rounded-md text-center">
                    <option value="jpg">JPG</option>
                    <option value="png">PNG</option>
                    <option value="heic">HEIC</option>
                </select>
            </div>

        </div> -->

        <span id="server-message">
            <!-- <div class="mt-4 hf-error">
                    <div class="border px-3 py-5 text-center">
                        <span class="font-medium">{ serverMessage }</span>
                    </div>
                </div> -->
        </span>

        <!-- <div class="d-flex mt-4 action-body">
            <input type="submit" id="convert-button"
                class="bg-primary btn bg-gradient text-white text-xl border rounded-md px-5 py-2 mx-auto"
                value="Convert now" />




        </div> -->
    </form>
        </div>
        ';
        $content = $custom_html;
    }else if (is_page('heic-to-pdf')) {
        $custom_html = '
        <div class="light">
        <div class="d-flex format-nav-link">
        <ul>
            <li><a href="/">HEIC to JPG</a></li>
            <li><a href="../heic-to-png">HEIC to PNG</a></li>
            <li><a href="../heic-to-pdf" class="active">HEIC to PDF</a></li>
        </ul>
    </div>
    <form id="convertForm" method="post" enctype="multipart/form-data">
        <div class="d-block cursor-pointer hf-main-content" style="pointer-events: none;">
            <label class="d-block">
                <input id="choose_image" maxuploads="2" accept=".heic" type="file" name="image" hidden
                    onchange="loadFile(event)" multiple />
                <div class="d-flex w-100 mt-2">
                    <span id="convert-button" style="pointer-events: painted;"
                        class="mt-2 d-flex btn bg-gradient text-white text-xl border rounded-md px-5 py-2 ml-auto mr-2">
                        <i class="zp zp-upload mr-2"></i>Upload file</span>
                    <a id="again-button" href="../heic-to-pdf" style="pointer-events: none; opacity: 0.5;"
                        class="mt-2 d-flex btn bg-gradient text-white text-xl border rounded-md px-5 py-2 mr-auto ml-2">
                        <i class="zp zp-clear mr-2"></i>Clear</a>
                </div>
            </label>
            <div id="drop_down_body"
                style="min-height: 170px; align-items: center; border-style: dashed; border-color: #b0b1b3;"
                class="d-flex m-3 rounded justify-center cursor-pointer">

                <span id="drag-drop-text" class="mx-auto"
                    style="user-select: none;text-align: center;line-height: 2;font-weight: lighter;">Drag & Drop
                    Files Here</span>


                <div class=" w-100 drag-display grid-container" id="selected-image-grid">




                </div>
            </div>

            <div class="d-flex my-4">
                <span id="g_drive_btn" onclick="downloadAll()" class="mx-auto py-2 px-3 d-flex btn"><i
                        class="mr-2 zp zp-download"></i>Download All <span class="download-count">0</span></span>

            </div>
            <span id="selected-file"></span>
        </div>
        <!-- <div class="d-flex mt-4 w-100">
            <div class="w-50 mr-2">
                <label class="mb-1">Quality</label>
                <input id="quality_box" type="number" name="quality" min="0" max="100" value="70"
                    class="w-100 bg-gray-100 rounded-md">
            </div>
            <div class="w-50 ml-2">
                <label class="mb-1">Format</label>
                <select name="format" id="select_format" class="w-100 bg-gray-100 rounded-md text-center">
                    <option value="jpg">JPG</option>
                    <option value="png">PNG</option>
                    <option value="heic">HEIC</option>
                </select>
            </div>

        </div> -->

        <span id="server-message">
            <!-- <div class="mt-4 hf-error">
                    <div class="border px-3 py-5 text-center">
                        <span class="font-medium">{ serverMessage }</span>
                    </div>
                </div> -->
        </span>

        <!-- <div class="d-flex mt-4 action-body">
            <input type="submit" id="convert-button"
                class="bg-primary btn bg-gradient text-white text-xl border rounded-md px-5 py-2 mx-auto"
                value="Convert now" />




        </div> -->
    </form>
        </div>
        ';
        $content = $custom_html;
    }else if (is_page('gallery')) {
        global $wpdb;
$table_session = $wpdb->prefix . 'session_time';
$table_settings = $wpdb->prefix . 'heicfilter_settings';
$get_session_data = $wpdb->get_row("SELECT session.title, session.time FROM `$table_settings` AS setting LEFT JOIN `$table_session` AS session ON setting.session_id=session.id WHERE setting.id = 1");
$table_media = $wpdb->prefix . 'heicfilter_media';


        $userId = $_COOKIE["user_id"];

        $currentTimestamp = time();
        // Add 1 hour (3600 seconds) to the current timestamp
        $timestampPlusOneHour = $currentTimestamp - $get_session_data->time;
        // Convert the timestamp to a human-readable format
        $datePlusOneHour = date('Y-m-d H:i:s', $timestampPlusOneHour);

        $result_media = $wpdb->get_results("SELECT * FROM $table_media ORDER BY id DESC");
        
        
        $custom_html = '<div class="d-block hf-main-content" style="padding: 10px;">';

if (!empty($result_media)) {
    foreach ($result_media as $row) {
        $custom_html .= '<div class="photo-item-model">

            <img src="wp-content/uploads/heicfilter/' . "cropped" . substr($row->file_name, 10, -4) . '.jpg" alt="">
            <div class="photo-model-info">
                <h2>' . $row->file_name . '</h2>
                <h3>Format: <strong>' . $row->file_type . '</strong></h3>
                <h3>Time: <strong>' . "ddd" . '</strong></h3>
            </div>
            <div class="photo-model-action">
                <a class="cursor-pointer" onclick="sharePopUp(\'' . 'cropped' . substr($row->file_name, 10, -4) . '.jpg\', \'' . $row->id . '\', \'' . $row->created_date . '\')"><i class="zp zp-share"></i></a>
                <a target="_blank" href="download?download=https://heicfilter.com/wp-content/uploads/heicfilter/' . $row->file_name . '"><i class="zp zp-download"></i></a>
            </div>
        </div>';
    }
}

$custom_html .= '</div>';
        $content = $custom_html;
    }else{
        $custom_html = '
        <div class="light">
        <div class="d-flex format-nav-link">
        <ul>
            <li><a href="/" class="active">HEIC to JPG</a></li>
            <li><a href="../heic-to-png/">HEIC to PNG</a></li>
            <li><a href="../heic-to-pdf/">HEIC to PDF</a></li>
        </ul>
    </div>
        <form id="convertForm" method="post" enctype="multipart/form-data">
        <div class="d-block cursor-pointer hf-main-content"
            style=" pointer-events: none;">
            <label class="d-block">
                <input id="choose_image" maxuploads="2" accept=".heic" type="file" name="image" hidden
                    onchange="loadFile(event)" multiple />

                <div class="d-flex w-100 mt-2">
                    <span id="convert-button" style="pointer-events: painted;"
                        class="mt-2 d-flex btn bg-gradient text-white text-xl border rounded-md px-5 py-2 ml-auto mr-2">
                        <i class="zp zp-upload mr-2"></i>Upload file</span>
                    <a id="again-button" href="/" style="pointer-events: none; opacity: 0.5;"
                        class="mt-2 d-flex btn bg-gradient text-white text-xl border rounded-md px-5 py-2 mr-auto ml-2">
                        <i class="zp zp-clear mr-2"></i>Clear</a>
                </div>
            </label>
            <div id="drop_down_body" class="d-flex m-3 rounded justify-center cursor-pointer">

                <span id="drag-drop-text" class="mx-auto"
                    style="user-select: none;text-align: center;line-height: 2;font-weight: lighter;">Drag & Drop
                    Files Here</span>


                <div class=" w-100 drag-display grid-container" id="selected-image-grid">




                </div>
            </div>

            <div class="d-flex my-4">
                <span id="g_drive_btn" onclick="downloadAll()" class="mx-auto py-2 px-3 d-flex btn"><i
                        class="mr-2 zp zp-download"></i>Download All <span class="download-count">0</span></span>

            </div>
            <span id="selected-file"></span>
        </div>
        <!-- <div class="d-flex mt-4 w-100">
            <div class="w-50 mr-2">
                <label class="mb-1">Quality</label>
                <input id="quality_box" type="number" name="quality" min="0" max="100" value="70"
                    class="w-100 bg-gray-100 rounded-md">
            </div>
            <div class="w-50 ml-2">
                <label class="mb-1">Format</label>
                <select name="format" id="select_format" class="w-100 bg-gray-100 rounded-md text-center">
                    <option value="jpg">JPG</option>
                    <option value="png">PNG</option>
                    <option value="heic">HEIC</option>
                </select>
            </div>

        </div> -->

        <span id="server-message">
            <!-- <div class="mt-4 hf-error">
                    <div class="border px-3 py-5 text-center">
                        <span class="font-medium">{ serverMessage }</span>
                    </div>
                </div> -->
        </span>

        <!-- <div class="d-flex mt-4 action-body">
            <input type="submit" id="convert-button"
                class="bg-primary btn bg-gradient text-white text-xl border rounded-md px-5 py-2 mx-auto"
                value="Convert now" />




        </div> -->
    </form>
        </div>
        ';
        $content = $custom_html;
    }
    return $content;
}
add_shortcode('the_content', 'custom_plugin_add_html');
