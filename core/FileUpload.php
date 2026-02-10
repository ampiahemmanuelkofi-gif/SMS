<?php
/**
 * File Upload Handler
 */

class FileUpload {
    
    /**
     * Upload image file
     * 
     * @param array $file $_FILES array element
     * @param string $destination Destination directory
     * @param int $maxWidth Maximum width for resize
     * @param int $maxHeight Maximum height for resize
     * @return array ['success' => bool, 'filename' => string, 'error' => string]
     */
    public static function uploadImage($file, $destination, $maxWidth = 800, $maxHeight = 800) {
        // Validate file
        $validation = Security::validateFileUpload($file, ALLOWED_IMAGE_TYPES, MAX_FILE_SIZE);
        
        if (!$validation['valid']) {
            return ['success' => false, 'filename' => null, 'error' => $validation['error']];
        }
        
        // Create destination directory if not exists
        if (!is_dir($destination)) {
            mkdir($destination, 0755, true);
        }
        
        // Generate safe filename
        $filename = Security::sanitizeFilename($file['name']);
        $filepath = $destination . '/' . $filename;
        
        // Resize and save image
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if ($extension === 'jpg' || $extension === 'jpeg') {
            $image = imagecreatefromjpeg($file['tmp_name']);
        } elseif ($extension === 'png') {
            $image = imagecreatefrompng($file['tmp_name']);
        } else {
            return ['success' => false, 'filename' => null, 'error' => 'Unsupported image type'];
        }
        
        if (!$image) {
            return ['success' => false, 'filename' => null, 'error' => 'Failed to process image'];
        }
        
        // Get original dimensions
        $width = imagesx($image);
        $height = imagesy($image);
        
        // Calculate new dimensions
        $ratio = min($maxWidth / $width, $maxHeight / $height);
        
        if ($ratio < 1) {
            $newWidth = (int)($width * $ratio);
            $newHeight = (int)($height * $ratio);
            
            // Create resized image
            $resized = imagecreatetruecolor($newWidth, $newHeight);
            
            // Preserve transparency for PNG
            if ($extension === 'png') {
                imagealphablending($resized, false);
                imagesavealpha($resized, true);
            }
            
            imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            
            // Save resized image
            if ($extension === 'jpg' || $extension === 'jpeg') {
                imagejpeg($resized, $filepath, 85);
            } else {
                imagepng($resized, $filepath, 8);
            }
            
            imagedestroy($resized);
        } else {
            // Save original size
            if ($extension === 'jpg' || $extension === 'jpeg') {
                imagejpeg($image, $filepath, 85);
            } else {
                imagepng($image, $filepath, 8);
            }
        }
        
        imagedestroy($image);
        
        return ['success' => true, 'filename' => $filename, 'error' => null];
    }
    
    /**
     * Upload document file
     * 
     * @param array $file $_FILES array element
     * @param string $destination Destination directory
     * @return array ['success' => bool, 'filename' => string, 'error' => string]
     */
    public static function uploadDocument($file, $destination) {
        // Validate file
        $validation = Security::validateFileUpload($file, ALLOWED_DOCUMENT_TYPES, MAX_FILE_SIZE);
        
        if (!$validation['valid']) {
            return ['success' => false, 'filename' => null, 'error' => $validation['error']];
        }
        
        // Create destination directory if not exists
        if (!is_dir($destination)) {
            mkdir($destination, 0755, true);
        }
        
        // Generate safe filename
        $filename = Security::sanitizeFilename($file['name']);
        $filepath = $destination . '/' . $filename;
        
        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return ['success' => true, 'filename' => $filename, 'error' => null];
        }
        
        return ['success' => false, 'filename' => null, 'error' => 'Failed to upload file'];
    }
    
    /**
     * Delete file
     * 
     * @param string $filepath Full path to file
     * @return bool
     */
    public static function deleteFile($filepath) {
        if (file_exists($filepath)) {
            return unlink($filepath);
        }
        return false;
    }
}
