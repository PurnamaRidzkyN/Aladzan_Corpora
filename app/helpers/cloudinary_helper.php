<?php
if (!function_exists('cloudinary_url')) {
    function cloudinary_url(string $publicId, string $type = 'image', string $options = ''): string
    {
        $cloudName = env('CLOUDINARY_CLOUD_NAME', 'default-cloud');
        $prefix = "https://res.cloudinary.com/{$cloudName}/{$type}/upload/";

        return $prefix . ($options ? "{$options}/" : '') . $publicId;
    }
}