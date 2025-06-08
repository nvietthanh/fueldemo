<?php

namespace Helpers;

use Fuel\Core\Config;
use Fuel\Core\Upload;

class UploadHelper
{
    /**
     * Handles file upload from a specific input field.
     *
     * @param array  $config  Optional upload configuration (path, file types, max size, etc.)
     *
     * @return array {
     *     success: bool,              // true if upload succeeded
     *     paths?: array,               // file details if successful
     *     errors?: array              // error message if failed
     * }
     */
    public static function process_file(array $config = [])
    {
        // Load default config if not passed
        if (empty($config)) {
            Config::load('upload', true);
            $config = Config::get('upload.default');
        }

        // Process uploaded files
        Upload::process($config);

        // Save only valid + allowed files
        if (Upload::is_valid()) {
            Upload::save();

            $paths = [];
            foreach (Upload::get_files() as $file) {
                $paths[$file['field']] = ltrim(str_replace(DOCROOT, '/', rtrim($config['path'], '/') . '/' . $file['saved_as']), '/');
            }

            return ['success' => true, 'paths' => $paths];
        } else {
            // Errors
            $errors = [];
            $uploadErrors = Upload::get_errors();

            foreach ($uploadErrors as $error) {
                $errors[$error['field']][] = $error['errors'][0]['message'];
            }

            return ['success' => false, 'errors' => $errors];
        }
    }
}
