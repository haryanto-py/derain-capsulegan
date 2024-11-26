<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Single Image Deraining - Real World</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>

<body style="background-image: url('img/bg.jpg');">
    <div class="top-right-button">
        <a href="synthetic.php" class="btn btn-success">Synthetic Rain Image Deraining</a>
    </div>
    <div class="title">
        <h1>Single Image Deraining</h1>
        <h3>Please upload real-rain world image</h3>
    </div>
    <div class="hero">
        <form id="upload-form" action="" method="post" enctype="multipart/form-data">
            <label for="input-file" id="drop-area">
                <input type="file" accept="image/*" id="input-file" name="image" hidden>
                <div id="img-view">
                    <img src="img/file-format.png" style="width: 100px; height: 100px; margin-bottom: 5px">
                    <p>Select or drag your image here</p>
                    <span>Upload the input rainy image</span>
                </div>
            </label>
            <div class="d-grid gap-2 col-12 mx-auto mt-3">
                <button type="submit" class="btn btn-primary">Upload</button>
            </div>
        </form>
    </div>

    <?php
    require_once 'RealModel.php'; // Include the class file

    // Check if image file is uploaded
    if (isset($_FILES['image'])) {
        $errors = array();
        $file_name = $_FILES['image']['name'];
        $file_size = $_FILES['image']['size'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_type = $_FILES['image']['type'];
        $file_ext = explode('.', $_FILES['image']['name']);
        $file_ext = strtolower(end($file_ext));

        $extensions = array("jpeg", "jpg", "png");

        if (in_array($file_ext, $extensions) === false) {
            $errors[] = "Extension not allowed, please choose a JPEG or PNG file.";
        }

        if ($file_size > 2097152) {
            $errors[] = 'File size must be less than 2 MB';
        }

        if (empty($errors) == true) {
            move_uploaded_file($file_tmp, "uploads/rain/" . $file_name);
        } else {
            print_r($errors);
        }
    }

    // Load the deep learning model
    $model = new DeepLearningModel();

    // Process the uploaded image with the deep learning model
    if (isset($file_name)) {
        $input_image = "C:/xampp3/htdocs/derain-capsulegan/uploads/rain/" . $file_name;
        $output_image = $model->processImage("C:/xampp3/htdocs/derain-capsulegan/uploads/rain/" . $file_name);

        // Check if the outputs folder exists and is writable
        $outputs_folder = "C:/xampp3/htdocs/derain-capsulegan/outputs/";
        if (!file_exists($outputs_folder) || !is_writable($outputs_folder)) {
            die("Error: Outputs folder does not exist or is not writable.");
        }

        // Display the input and output images side-by-side'
        echo '<div class="hero">';
        echo '<div class="image-container">';
        echo '<div class="image-item"><b>Input image</b><br><img src="data:image/jpeg;base64,' . base64_encode(file_get_contents($input_image)) . '" class="image" /></div>';
        echo '<div class="image-item"><b>Processed image</b><br><img src="data:image/jpeg;base64,' . base64_encode($output_image) . '" class="image" /></div>';
        echo '</div>';
        echo '</div>';
    }
    ?>

    <div class="footer" style="text-align:center; margin-top: 30px">
        <p><b>&copy; 2024 Haryanto Hidayat</b></p>

        <script src="js/script.js"></script>
</body>

</html>