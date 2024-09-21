<?php
if (isset($_POST["download"])) {
    $imageUrl = $_POST["download"];

    // Get the image content
    $imageContent = file_get_contents($imageUrl);
    if ($imageContent === FALSE) {
        http_response_code(404);
        echo 'Image not found.';
        exit;
    }

    // Set headers to force download
    header('Content-Description: File Transfer');
    header('Content-Type: image/jpeg'); // Change content type based on the image format
    header('Content-Disposition: attachment; filename="' . basename($imageUrl) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . strlen($imageContent));

    // Output the image content
    echo $imageContent;
    exit;
}
?>
