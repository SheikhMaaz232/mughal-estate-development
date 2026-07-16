<?php
namespace App\Services;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CommonService {

    public function uploadImage(UploadedFile $image, $path): string
    {
         // Ensure the directory exists
        $fullPath = 'public/' . ltrim($path, '/');

        if (!Storage::exists($fullPath)) {
            Storage::makeDirectory($fullPath, 0755, true); // recursive creation
        }

        // Generate a unique filename
        $fileName = uniqid() . '.' . $image->getClientOriginalExtension();

        // Store the file
        return $image->storeAs($path, $fileName, 'public');

    }
}
