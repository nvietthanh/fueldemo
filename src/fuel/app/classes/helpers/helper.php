<?php

if (!function_exists('env')) {
    function env($key, $default = null)
    {
        $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);

        if ($value === false) return $default;

        $value = trim($value);
        switch (strtolower($value)) {
            case 'null':
                return null;
            case 'true':
                return true;
            case 'false':
                return false;
            case 'empty':
                return '';
        }

        return $value;
    }
}

if (!function_exists('dd')) {
    function dd(...$vars)
    {
        echo '<pre style="background:#111;color:#0f0;padding:10px;border-radius:4px;">';
        foreach ($vars as $var) {
            print_r($var);
        }
        echo '</pre>';
        die;
    }
}
