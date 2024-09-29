<?php
$imageUrl = $_GET["download"]; // URL of the image

// Get the image content
$imageContent = file_get_contents($imageUrl);
if ($imageContent === FALSE) {
    http_response_code(404);
    echo 'Image not found.';
    echo '<script type="text/javascript">window.close();</script>';
    // exit;
}

// Set headers to force download
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . basename($imageUrl) . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . strlen($imageContent));


echo '<script type="text/javascript">window.close();</script>';
// exit;




// $file = "../../../" . $_GET["file"]; // Path to the file you want to download
// if (file_exists($file)) {
//     header('Content-Description: File Transfer');
//     header('Content-Type: application/octet-stream');
//     header('Content-Disposition: attachment; filename="' . basename($file) . '"');
//     header('Expires: 0');
//     header('Cache-Control: must-revalidate');
//     header('Pragma: public');
//     header('Content-Length: ' . filesize($file));
//     readfile($file);
//     exit;
// } else {
//     http_response_code(404);
//     echo "File not found.";
// }
?>
