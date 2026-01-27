<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait ImageTrait
{
    public function uploadImage($fileName, $file ,$folder)
    {
        return $file->storeAs($folder, $fileName, 'public');
    }

    public function deleteImage($path)
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
