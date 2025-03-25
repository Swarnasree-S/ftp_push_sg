<?php

function get_config($configFile) {
    // Path to the JSON file
    $filePath = $configFile;
    
    // Check if the file exists
    if (!file_exists($filePath)) {
        throw new Exception("File not found: " . $filePath);
    }
    
    // Get the file contents
    $jsonContent = file_get_contents($filePath);
    
    // Decode the JSON data into an associative array
    $data = json_decode($jsonContent, true);
    
    // Check for JSON parsing errors
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("JSON decode error: " . json_last_error_msg());
    }
    
    return $data;
}


?>
