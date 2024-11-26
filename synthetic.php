<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Single Image Deraining - Synthetic</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style-synthetic.css">
</head>

<body style="background-image: url('img/bg.jpg');">
    <div class="top-right-button">
        <a href="index.php" class="btn btn-success">Real Rain Image Deraining</a>
    </div>
    <div class="title">
        <h1>Synthetic Image Deraining</h1>
        <h3>Please upload synthetic rain image with its target image</h3>
    </div>
    <div class="hero">
        <form id="upload-form" action="" method="post" enctype="multipart/form-data">
            <div class="drop-area-container">
                <div class="drop-area-wrapper">
                    <p class="drop-text">Rain Image</p>
                    <label class="drop-area" for="input-file-rain" id="drop-area-rain">
                        <input type="file" accept="image/*" id="input-file-rain" name="image_rain" hidden>
                        <div id="img-view-rain">
                            <img src="img/file-format.png" style="width: 100px; height: 100px; margin-bottom: 5px">
                            <p>Select or drag your image here</p>
                            <span>Upload the input rainy image</span>
                        </div>
                    </label>
                </div>
                <div class="drop-area-wrapper">
                    <p class="drop-text">Target Image</p>
                    <label class="drop-area" for="input-file-target" id="drop-area-target">
                        <input type="file" accept="image/*" id="input-file-target" name="image_target" hidden>
                        <div id="img-view-target">
                            <img src="img/file-format.png" style="width: 100px; height: 100px; margin-bottom: 5px">
                            <p>Select or drag your image here</p>
                            <span>Upload the input target image</span>
                        </div>
                    </label>
                </div>
            </div>
            <div class="d-grid gap-2 col-6 mx-auto mt-3">
                <button type="submit" class="btn btn-primary">Upload</button>
            </div>
        </form>
    </div>

    <?php
    require_once 'SyntheticModel.php'; // Include the class file

    // Check if image file is uploaded
    if (isset($_FILES['image_rain']) && isset($_FILES['image_target'])) {
        $errors = array();
        $file_name_rain = $_FILES['image_rain']['name'];
        $file_size_rain = $_FILES['image_rain']['size'];
        $file_tmp_rain = $_FILES['image_rain']['tmp_name'];
        $file_type_rain = $_FILES['image_rain']['type'];
        $file_ext_rain = explode('.', $_FILES['image_rain']['name']);
        $file_ext_rain = strtolower(end($file_ext_rain));

        $file_name_target = $_FILES['image_target']['name'];
        $file_size_target = $_FILES['image_target']['size'];
        $file_tmp_target = $_FILES['image_target']['tmp_name'];
        $file_type_target = $_FILES['image_target']['type'];
        $file_ext_target = explode('.', $_FILES['image_target']['name']);
        $file_ext_target = strtolower(end($file_ext_target));

        $extensions = array("jpeg", "jpg", "png");

        if (in_array($file_ext_rain, $extensions) === false) {
            $errors[] = "Extension not allowed, please choose a JPEG or PNG file for the rain image.";
        }

        if ($file_size_rain > 2097152) {
            $errors[] = 'File size must be less than 2 MB for the rain image';
        }

        if (in_array($file_ext_target, $extensions) === false) {
            $errors[] = "Extension not allowed, please choose a JPEG or PNG file for the target image.";
        }

        if ($file_size_target > 2097152) {
            $errors[] = 'File size must be less than 2 MB for the target image';
        }

        if (empty($errors) == true) {
            move_uploaded_file($file_tmp_rain, "uploads/rain/" . $file_name_rain);
            move_uploaded_file($file_tmp_target, "uploads/target/" . $file_name_target);
        } else {
            print_r($errors);
        }
    }

    // Load the deep learning model
    $model = new DeepLearningModel();

    // Process the uploaded image with the deep learning model
    if (isset($file_name_rain) && isset($file_name_target)) {
        $input_image_rain = "C:/xampp3/htdocs/derain-capsulegan/uploads/rain/" . $file_name_rain;
        $input_image_target = "C:/xampp3/htdocs/derain-capsulegan/uploads/target/" . $file_name_target;
        $result = $model->processImage($input_image_rain, $input_image_target);

        // Check for errors in the result
        if ($result === null) {
            die("Error: Unable to process the images. Please check the command and output.");
        }

        if (isset($result['error'])) {
            die("Error: " . $result['error']);
        }

        // Check if the outputs folder exists and is writable
        $outputs_folder = "C:/xampp3/htdocs/derain-capsulegan/outputs/";
        if (!file_exists($outputs_folder) || !is_writable($outputs_folder)) {
            die("Error: Outputs folder does not exist or is not writable.");
        }

        // Display the input and output images side-by-side
        echo '<div class="hero">';
        echo '<div class="image-container">';
        echo '<div class="image-item"><b>Rain Image</b><br><img src="data:image/jpeg;base64,' . base64_encode(file_get_contents($input_image_rain)) . '" class="image" /></div>';

        if (isset($result['output_path'])) {
            echo '<div class="image-item"><b>Processed Image</b><br><img src="data:image/jpeg;base64,' . base64_encode(file_get_contents($result['output_path'])) . '" class="image" /></div>';
        }

        echo '<div class="image-item"><b>Target Image</b><br><img src="data:image/jpeg;base64,' . base64_encode(file_get_contents($input_image_target)) . '" class="image" /></div>';
        echo '</div>';
        echo '</div>';

        if (isset($result['psnr']) && isset($result['ssim'])) {
            echo '<div class="metric"><b>Metrics:</b>';
            echo '<div><b>PSNR</b>: ' . $result['psnr'] . ' | <b>SSIM</b>: ' . $result['ssim'] . '</div>';
            echo '</div>';
        }
    }
    ?>

    <div class="footer" style="text-align:center; margin-top: 30px">
        <p><b>&copy; 2024 Haryanto Hidayat</b></p>

        <script src="js/script2.js"></script>
</body>

</html>