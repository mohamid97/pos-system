<?php
namespace App\Traits;
use Illuminate\Http\UploadedFile;

trait HandelImages{
    
    public function handleImageUpload(UploadedFile $image , $path): string
    {
        return $image->store($path, 'public');
    } 
    
}


    