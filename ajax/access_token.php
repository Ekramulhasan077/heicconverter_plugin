<?php
require_once('../../../../wp-load.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    global $wpdb;

    $seconds = $_POST["expire_date"];
    $milliseconds = $seconds * 1000;

    $current_milliseconds = round(microtime(true) * 1000) + $milliseconds;
    $table_name = $wpdb->prefix . 'access_token';
    $permitted_token = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $random_token = substr(str_shuffle($permitted_token), 0, 50);
    $data = array(
        'file_id' => sanitize_text_field(strtolower(($_POST["id"]))),
        'token' => sanitize_text_field($random_token),
        'expire' => sanitize_text_field($current_milliseconds)
    );

    $format = array('%d', '%s');

    $wpdb->insert($table_name, $data, $format);

   echo $random_token;
}

?>