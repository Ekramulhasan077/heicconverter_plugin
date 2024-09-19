<?php
require ('fpdf.php');

$response = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = file_get_contents("php://input");

    // Decode the JSON data
    $decoded_data = json_decode($data, true);

    // List of files to be included in the ZIP
    $images = $decoded_data['files'];

    $pdf = new FPDF();

    // Add multiple images
    foreach ($images as $image) {
        $pdf->AddPage();
        list($width, $height) = getimagesize($image);

        // Calculate the dimensions to fit the PDF page
        $pdfWidth = $pdf->GetPageWidth() - 20; // 10mm margin on each side
        $pdfHeight = $pdf->GetPageHeight() - 20; // 10mm margin on top and bottom

        // Maintain aspect ratio
        if ($width > $height) {
            $newWidth = $pdfWidth;
            $newHeight = ($height / $width) * $pdfWidth;
        } else {
            $newHeight = $pdfHeight;
            $newWidth = ($width / $height) * $pdfHeight;
        }

        // Center the image on the page
        $x = ($pdfWidth - $newWidth) / 2 + 10; // 10mm left margin
        $y = ($pdfHeight - $newHeight) / 2 + 10; // 10mm top margin

        $pdf->Image($image, $x, $y, $newWidth, $newHeight); // Adjust the coordinates and size as needed
    }

    $milliseconds = round(microtime(true) * 1000);
    $random_text = rand(1000, 1000000) . "_" . $milliseconds;
    $final_image = "heicfilter_" . $random_text . ".pdf";

    $pdfFilePath = '../../../uploads/heicfilter/' . $final_image;
    $pdf->Output('F', $pdfFilePath);

    $response["file_name"] = $final_image;
    $response["download_zip"] = "https://heicfilter.com/wp-content/uploads/heicfilter/" . $final_image;
    echo json_encode($response);


} else {
    echo 'Invalid request method';
}
?>