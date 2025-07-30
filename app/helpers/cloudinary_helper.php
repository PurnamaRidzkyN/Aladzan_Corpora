<?php
if (!function_exists('cloudinary_url')) {
    function cloudinary_url(string $publicId, string $type = 'image', string $options = ''): string
    {
        $cloudName = config('cloudinary.cloud_name');
        $prefix = "https://res.cloudinary.com/{$cloudName}/{$type}/upload/";

        return $prefix . ($options ? "{$options}/" : '') . $publicId;
    }
}