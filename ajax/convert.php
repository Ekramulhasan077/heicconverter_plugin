<?php
$response = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (!extension_loaded('imagick')) {
        // $values['status'] = 0;
        // $values['message'] = 'Imagick extension is not loaded.';
        // print json_encode($values);
        echo "Imagick extension is not loaded.";
    }

    function convertHeicToJpg($source, $format)
    {
        $path = "../../../uploads/heicconverter/";
        $milliseconds = round(microtime(true) * 1000);
        $random_text = rand(1000, 1000000) . "_" . $milliseconds;
        $final_image = "heicconverter_" . $random_text . "." . $format;
        $cropFile = "cropped_" . $random_text . ".jpg";
        $croppedOutputPath = $path . $cropFile;

        // // Create an Imagick object
        // $imagick = new Imagick();

        // // Read the HEIC image
        // $imagick->readImage($source);

        // // Set the output format to JPG
        // $imagick->setImageFormat($format);
        // $imagick->setImageCompressionQuality(30);

        // // Write the image to the destination path
        // $imagick->writeImage($path . strtolower($final_image));




        // $croppedImage = clone $imagick;

        // $croppedImage->resizeImage(200, 150, Imagick::FILTER_LANCZOS, 1); // Example resizing

        // $croppedImage->setImageCompressionQuality(30); // Set the quality (0-100)

        // $croppedImage->writeImage($croppedOutputPath);

        // // Clear resources
        // $imagick->clear();
        // $croppedImage->clear();

        $command = escapeshellcmd("python3 ./../main.py");
        // Execute the command
        $output = shell_exec($command);

        // unlink($source);

        $response["message"] = 'Completed.';
        $response['convert_type'] = strtoupper($format);
        $response["download_file"] = $final_image;
        $response["preview_image"] = $cropFile;
        $response["preview_path"] = "../../wp-content/uploads/heicconverter/" . strtolower($final_image);
        $response["download_path"] = $path . strtolower($final_image);
        $response["download_link"] = "https://heicjpgconverter.com/wp-content/uploads/heicconverter/" . strtolower($output);
        echo json_encode($response);
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