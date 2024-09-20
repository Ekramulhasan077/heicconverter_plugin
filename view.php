<?php
require_once ('../../../wp-load.php');
get_header();

global $wpdb;
$table_name = $wpdb->prefix . 'heicfilter_media';
$table_access = $wpdb->prefix . 'access_token';
$imageId = $_GET["image"];
$token = $_GET["token"];
$single_row = $wpdb->get_row("SELECT * FROM $table_name WHERE id = '$imageId' ");

$current_milliseconds = round(microtime(true) * 1000);
$access_check = $wpdb->get_row("SELECT * FROM $table_access WHERE file_id = '$imageId' AND token='$token' AND expire > $current_milliseconds ");

if(isset($access_check)){
?>

<div class="container">
    <div class="view-image">
        <img src="wp-content/uploads/heicfilter/<?php echo "cropped" . substr($single_row->file_name, 10, -4) . ".jpg"; ?>"
            alt="">

        <div class="view-image-action">
            <a href="wp-content/uploads/heicfilter/<?php echo $single_row->file_name; ?>"><i class="zp zp-visibility"></i>View Image</a>
            <a href="download?download=https://heicfilter.com/wp-content/uploads/heicfilter/<?php echo $single_row->file_name; ?>"><i class="zp zp-download"></i>Download</a>
        </div>
    </div>
</div>



<?php

}else{
    echo '<script type="text/javascript">';
    echo 'window.location.href="https://heicfilter.com/";';
    echo 'exit';
    echo '</script>';

}
get_footer();

?>