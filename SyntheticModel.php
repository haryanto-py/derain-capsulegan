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

        // Execute the command and capture both output and error messages
        $output = shell_exec($command);

        // Debugging: Display the raw output from shell_exec for debugging purposes
        if ($output === null) {
            echo "Error: Command failed or returned null.";
            return null;
        }

        // echo "Raw output: $output\n";

        // Trim whitespace characters from the output
        $output = trim($output);

        // Debugging: Check the trimmed output
        // echo "Trimmed output: $output\n";

        // Parse the JSON output
        $result = json_decode($output, true);

        // Check if json_decode failed
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo "Error decoding JSON: " . json_last_error_msg();
            return null;
        }

        // Return the decoded JSON result directly
        return $result;
    }
}
?>
