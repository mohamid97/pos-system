<?php
namespace App\Traits;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HandelImages{
    
    public function handleImageUpload(UploadedFile $image , $path): string
    {
        return $image->store($path, 'public');
    } 

    public function deleteImage(string $imagePath): bool
    {
        return \Storage::disk('public')->delete($imagePath);
    }
    
}


    