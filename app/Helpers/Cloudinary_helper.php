<?php

if (!function_exists('cloudinary_url')) {
    function cloudinary_url(string $publicId, string $type = 'image', string $options = ''): string
    {
        $cloudName = config('cloudinary.cloud_name');
        $prefix = "https://res.cloudinary.com/{$cloudName}/{$type}/upload/";

        return $prefix . ($options ? "{$options}/" : '') . $publicId;
    }
}
if (!function_exists('cloudinary_download_url')) {
    function cloudinary_download_url(string $publicId, string $type = 'image', string $name = null): string
    {
        $cloudName = config('cloudinary.cloud_name');
        $safeName = $name ? rawurlencode($name) : null;
        $attachment = $safeName ? "fl_attachment:$safeName" : 'fl_attachment';

        return "https://res.cloudinary.com/{$cloudName}/{$type}/upload/{$attachment}/{$publicId}";
    }
}
