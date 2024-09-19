<?php
require_once('../../../../wp-load.php');
get_header();

global $wpdb;

$table_name = $wpdb->prefix . 'heicfilter_users';

$data = array(
    'theme' => $_POST["theme"]
);

$where = array(
    'id' => $_COOKIE["user_id"], // Replace with your record ID
);

$wpdb->update($table_name, $data, $where);

?>