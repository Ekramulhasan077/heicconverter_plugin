<?php
$response = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = file_get_contents("php://input");

    // Decode the JSON data
    $decoded_data = json_decode($data, true);

    // List of files to be included in the ZIP
    $files = $decoded_data['files'];

    // Create a new ZIP archive
    $zip = new ZipArchive();
    $milliseconds = round(microtime(true) * 1000);
    $file_zip = 'Heicfilter_' . rand(1000, 1000000) . "_" . $milliseconds . ".zip";
    $zipFileName = '../tempzip/' . $file_zip;

    if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
        exit("Cannot open <$zipFileName>\n");
    }

    // Add files to the ZIP archive
    foreach ($files as $file) {
        if (file_exists($file)) {
            $zip->addFile($file, basename($file));
        }
    }

    // Close the ZIP archive
    $zip->close();

    // Serve the ZIP file for download
    if (file_exists($zipFileName)) {
        // Set headers to initiate a file download
        // header('Content-Type: application/zip');
        // header('Content-Disposition: attachment; filename="' . basename($zipFileName) . '"');
        // header('Content-Length: ' . filesize($zipFileName));
        $response["file_name"] = $file_zip;
        $response["download_zip"] = "https://heicfilter.com/wp-content/themes/heicfilter/tempzip/" . $file_zip;
        echo json_encode($response);

        // Delete the ZIP file after download
        // unlink($zipFileName);
    } else {
        $response["download_zip"] = "Dos not exit";
        echo json_encode($response);
    }
}
?>