<?php
// require_once('../../../../wp-load.php');
// /*
// Template Name: Insert Data Page
// */
// get_header(); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // global $wpdb;

    // $table_name = $wpdb->prefix . 'heicfilter_media';
    // $data = array(
    //     'file_name' => sanitize_text_field(strtolower(($_POST["file_name"]))),
    //     'file_type' => sanitize_text_field($_POST["file_type"]),
    //     'user_id' => sanitize_text_field($_COOKIE["user_id"]),
    // );

    // $format = array('%s', '%s', '%s');

    // $wpdb->insert($table_name, $data, $format);

   

    if (isset($_COOKIE["convert_image"])) {

        $countImg = $_COOKIE["convert_image"];
        setcookie("dir_name", intval($countImg) + 1, time() + 31104000, "/", ".heicjpgconverter.com");
        
    }else{
        $countImg = '0';
        setcookie("dir_name", intval($countImg) + 1, time() + 31104000, "/", ".heicjpgconverter.com");
    }
}
?>
