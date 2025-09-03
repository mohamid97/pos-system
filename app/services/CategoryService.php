<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use  App\Traits\HandelImages;

class CategoryService
{
    use HandelImages;
    public function create(array $data): Category
    {
        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $data['image'] = $this->handleImageUpload($data['image'] , 'categories');
        }
        return Category::create($data);
    }

    public function update(Category $category, array $data): Category
    {
        $category->update($data);
        return $category->fresh();
    }

    public function delete(Category $category): bool
    {
        return $category->delete();
    }


}