<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class FileHelper
{
    static public function deleteFileByUrl($fileUrl)
    {
        try {
            $basePath = 'storage/';
            $relativePath = str_replace(asset($basePath), '', $fileUrl);
            $relativePath = ltrim($relativePath, '/');
            
            $storagePath = 'public/' . $relativePath;
            
            if (Storage::exists($storagePath)) {
                Storage::delete($storagePath);
                return true;
            }
            
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    static public function uploadFile($file, $directory, $fileName = null, $disk = 'public')
    {
        try {
            if (!Storage::disk($disk)->exists($directory)) {
                Storage::disk($disk)->makeDirectory($directory);
            }
            
            if ($fileName) {
                $imageStorage = Storage::disk('public')->putFileAs($directory, $file, $fileName);
                return Storage::url($imageStorage);
            } else {
                $imageName = time() . '.' . $file->extension();
                $imageStorage = Storage::disk('public')->putFile($directory, $file, $imageName);
                return Storage::url($imageStorage); 
            }
        } catch (\Exception $e) {
            throw new \Exception('Error uploading file');
        }
    }
}