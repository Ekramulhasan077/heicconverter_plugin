<?php
/*
Plugin Name: Custom Page and Post Plugin
Description: A plugin to add custom HTML, CSS, and JS to all pages and posts.
Version: 1.0
Author: Turjo
*/

// Enqueue the CSS and JavaScript files
function custom_plugin_enqueue_files() {
    wp_enqueue_style('custom-plugin-css', plugin_dir_url(__FILE__) . 'css/style.css');
    wp_enqueue_script('custom-plugin-js', plugin_dir_url(__FILE__) . 'js/script.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'custom_plugin_enqueue_files');

// Add custom HTML to pages and posts
function custom_plugin_add_html($content) {
    if (is_singular('post') || is_page()) {
        $custom_html = '
        <div class="light">
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
add_filter('the_content', 'custom_plugin_add_html');
