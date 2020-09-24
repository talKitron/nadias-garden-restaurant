<?php
namespace App;

use Illuminate\Support\Facades\Storage;

if (! function_exists('deleteImageFromDisk')) {
    function deleteImageFromDisk($disk, $imageName) {
        return Storage::disk($disk)->exists("images/" . $imageName) 
            && Storage::disk($disk)->delete("images/" . $imageName);
    }
}