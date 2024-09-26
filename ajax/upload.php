<?php
require ('fpdf.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_COOKIE["dir_name"])) {
        $dirName = $_COOKIE["dir_name"];
    }else{
        $milliseconds = round(microtime(true) * 1000);
        $random_text = rand(1000, 1000000) . "_" . $milliseconds;

        $command = escapeshellcmd("sh ../script.sh '$random_text'");
        shell_exec($command);

        setcookie("dir_name", $random_text, time() + 31104000, "/", ".heicjpgconverter.com");
        $dirName = $random_text;
    }
    function convertHeicToJpg($source, $format, $fileName)
    {
        $dirName = $_COOKIE["dir_name"];
        $tempImageName = 'request_for_pdf_' . rand(1000, 1000000) . '.jpg';
        // Temporary path for the intermediate JPEG/PNG file
        $tempImagePath = '../temp/' . $dirName . "/" . $tempImageName;


        $path = "../../../uploads/heicconverter/".$dirName."/";
        $milliseconds = round(microtime(true) * 1000);
        $random_text = rand(1000, 1000000) . "_" . $milliseconds;
        $final_image = $fileName . $format;
        $cropFile = "cropped_" .$random_text . ".jpg";
        $croppedOutputPath = $path . $cropFile;


       
            // // Create a new Imagick object
            // $imagick = new Imagick();

            // // Read the HEIC image
            // $imagick->readImage($source);

            // // Convert to JPEG (or PNG)
            // $imagick->setImageFormat('jpeg');
            // $imagick->writeImage($tempImagePath);


        $source = escapeshellarg($source);
        $croppedOutputPath = escapeshellarg($croppedOutputPath);
        $outputImage = escapeshellarg("$tempImagePath");
        $command = escapeshellcmd("python3 ../main.py $source $croppedOutputPath $outputImage");
        // Execute the command
        shell_exec($command);

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
            $pdf->Output('F', $path . $final_image);


            // $croppedImage = clone $imagick;

            // $croppedImage->resizeImage(200, 150, Imagick::FILTER_LANCZOS, 1); // Example resizing

            // $croppedImage->setImageCompressionQuality(30); // Set the quality (0-100)

            // $croppedImage->writeImage($croppedOutputPath);

            // // Clear resources
            // $imagick->clear();
            // $croppedImage->clear();


            // unlink($source);

            $response["message"] = 'Completed.';
            $response['convert_type'] = strtoupper($format);
            $response["download_file"] = $final_image;
            $response["preview_image"] = "https://heicjpgconverter.com/wp-content/uploads/heicconverter/".$dirName."/" . $cropFile;
            $response["download_path"] = "../temp/" . $dirName . "/" . strtolower($tempImageName);
            $response["download_link"] = "https://heicjpgconverter.com/wp-content/uploads/heicconverter/". $dirName . "/" . $final_image;
            echo json_encode($response);

      

    }

    $valid_extensions = array('jpeg', 'jpg', 'png', 'gif', 'bmp', 'pdf', 'doc', 'ppt', 'heic', 'HEIC'); // valid extensions
    $path = '../temp/'.$dirName."/";

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
            convertHeicToJpg($path, $_POST["format"], pathinfo($img."")['filename']);
        }
    } else {
        // $values['status'] = 0;
        // $values['message'] = 'Invalid file.';
        // print json_encode($values);
        echo "invalid";
    }

}

?>