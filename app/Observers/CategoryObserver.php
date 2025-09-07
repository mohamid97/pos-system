<?php

namespace App\Observers;

use App\Models\Category;
use App\Traits\HandelImages;

class CategoryObserver
{
    use HandelImages;


    public function updating(Category $category): void
    {
        $originalImage = $category->getOriginal('image');
        if ($category->isDirty('image') && $originalImage) {
            $this->deleteImage($originalImage);
        }
    }


    public function deleted(Category $category): void
    {
        if ($category->image) {
            $this->deleteImage($category->image);
        }
    }

}