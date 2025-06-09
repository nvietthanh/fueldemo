<?php

namespace Rules;

use Fuel\Core\Config;
use Fuel\Core\Validation;

class ImageRules extends Validation
{
    public static function _validation_valid_image_file_error($file)
    {
        if (!empty($file)) {
            if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
                return false;
            }
        }

        return true;
    }

    public static function _validation_valid_image_file_type($file, $allowed_types = null)
    {
        if (!empty($file)) {
            $allowed_types = $allowed_types ?: ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'];

            if (!in_array($file['type'], $allowed_types)) {
                return false;
            }
        }

        return true;
    }

    public static function _validation_valid_image_file_size($file, $max_size = 5 * 1024 * 1024)
    {
        if (!empty($file)) {
            Config::load('upload', true);
            $default = Config::get('upload.default');

            $max_size = $max_size ?: ($default['max_size'] ?? 5 * 1024 * 1024);

            Validation::active()->set_message('valid_image_file_size', 'The file size of :label must not exceed ' . $max_size / 1024 / 1024 . ' MB.');

            if ($file['size'] > $max_size) {
                return false;
            }
        }

        return true;
    }
}
