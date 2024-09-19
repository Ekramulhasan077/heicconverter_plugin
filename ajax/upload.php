<?php
require ('fpdf.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if Imagick extension is loaded
    if (!extension_loaded('imagick')) {
        die('Imagick extension is not installed or loaded.');
    }

    function convertHeicToJpg($source, $format)
    {

        $tempImageName = 'request_for_pdf_' . rand(1000, 1000000) . '.jpg';
        // Temporary path for the intermediate JPEG/PNG file
        $tempImagePath = '../temp/' . $tempImageName;


        $path = "../../../uploads/heicconverter/";
        $milliseconds = round(microtime(true) * 1000);
        $random_text = rand(1000, 1000000) . "_" . $milliseconds;
        $final_image = "heicconverter_" . $random_text . "." . $format;
        $cropFile = "cropped_" .$random_text . ".jpg";
        $croppedOutputPath = $path . $cropFile;


        try {
            // Create a new Imagick object
            $imagick = new Imagick();

            // Read the HEIC image
            $imagick->readImage($source);

            // Convert to JPEG (or PNG)
            $imagick->setImageFormat('jpeg');
            $imagick->writeImage($tempImagePath);

            // Create a new FPDF object
            $pdf = new FPDF();
            $pdf->AddPage();

            // Get image dimensions
            list($width, $height) = getimagesize($tempImagePath);

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

            // Add image to PDF
            $pdf->Image($tempImagePath, $x, $y, $newWidth, $newHeight);

            // Output the PDF
            $pdf->Output('F', $path . strtolower($final_image));


            $croppedImage = clone $imagick;

            $croppedImage->resizeImage(200, 150, Imagick::FILTER_LANCZOS, 1); // Example resizing

            $croppedImage->setImageCompressionQuality(30); // Set the quality (0-100)

            $croppedImage->writeImage($croppedOutputPath);

            // Clear resources
            $imagick->clear();
            $croppedImage->clear();


            unlink($source);

            $response["message"] = 'Completed.';
            $response['convert_type'] = strtoupper($format);
            $response["download_file"] = $final_image;
            $response["preview_image"] = $cropFile;
            $response["download_path"] = "../temp/" . strtolower($tempImageName);
            $response["download_link"] = "https://heicjpgconverter.com/wp-content/uploads/heicconverter/" . strtolower($final_image);
            echo json_encode($response);

        } catch (ImagickException $e) {
            echo 'Imagick Error: ' . $e->getMessage();
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }

    }

    $valid_extensions = array('jpeg', 'jpg', 'png', 'gif', 'bmp', 'pdf', 'doc', 'ppt', 'heic', 'HEIC'); // valid extensions
    $path = '../temp/';

    $img = $_FILES['image']['name'];
    $tmp = $_FILES['image']['tmp_name'];
    // get uploaded file's extension
    $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
    // can upload same image using rand function
    $final_image = rand(1000, 1000000) . $img;
    // check's valid format
    if (in_array($ext, $valid_extensions)) {
        $path = $path . strtolower($final_image);
        if (move_uploaded_file($tmp, $path)) {
            convertHeicToJpg($path, $_POST["format"]);
        }
    } else {
        // $values['status'] = 0;
        // $values['message'] = 'Invalid file.';
        // print json_encode($values);
        echo "invalid";
    }

}

?>