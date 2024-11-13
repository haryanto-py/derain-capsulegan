<?php

class DeepLearningModel {
    public function processImage($image_path, $target_path = null) {
        // Command to execute the Python script, with error redirection
        $command = "D:/Users/harya/anaconda3/envs/capsgan/python.exe process_image.py " . escapeshellarg($image_path);

        // Append target path if provided
        if ($target_path !== null) {
            $command .= " " . escapeshellarg($target_path);
        }

        // Redirect stderr to stdout
        $command .= " 2>&1";

        // echo "Command: $command\n";

        // Execute the command and capture both output and error messages
        $output = shell_exec($command);

        // Display the raw output from shell_exec for debugging purposes
        // echo "Raw output: $output\n";

        // Trim whitespace characters from the output
        $output = trim($output);

        // echo "Trimmed output: $output\n";

        // Parse the JSON output
        $result = json_decode($output, true);

        // Check if there was an error in the Python script
        if (isset($result['error'])) {
            echo "Error: " . $result['error'] . "\n";
            return null;
        }

        // Read the processed image content
        if (isset($result['output_path']) && file_exists($result['output_path'])) {
            $processed_image_content = file_get_contents($result['output_path']);

            return $processed_image_content;
        }

        return null;
    }
}
?>
