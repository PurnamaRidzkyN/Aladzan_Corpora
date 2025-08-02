<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MediaController extends Controller
{
public function downloadSelected(Request $request)
{
    $ids = $request->input('files', []);

    if (empty($ids)) {
        return response()->json(['error' => 'Tidak ada file untuk diunduh.'], 400);
    }

    $files = ProductMedia::whereIn('id', $ids)->get();

    $productName = $files->first()->product->name ?? 'File';

    $result = $files->values()->map(function ($file, $index) use ($productName) {
        $fileType = str_contains($file->file_type, 'video') ? 'video' : 'image';
        $filename = $productName . '-' . ($index + 1) . '.' . pathinfo($file->file_path, PATHINFO_EXTENSION);

        return [
            'url' => cloudinary_download_url($file->file_path, $fileType, $filename),
            'name' => $filename
        ];
    });

    return response()->json(['files' => $result]);
}
}
