<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Http\UploadedFile;
use  App\Traits\HandelImages;
use Illuminate\Database\Eloquent\Builder;

class CategoryService
{
 
    use HandelImages;
    public function create(array $data): Category
    {
        $data['is_active'] = $this->cheackActive($data);
        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $data['image'] = $this->handleImageUpload($data['image'] , 'categories');
        }
        return Category::create($data);
    }

    public function update(Category $category, array $data): Category
    {
        $data['is_active'] = $this->cheackActive($data);
        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {    
            $data['image'] = $this->handleImageUpload($data['image'], 'categories');
        } else {
            $data['image'] = $category->image;
        }
        
        $category->update($data);
        return $category->fresh();
    }

    public function delete(Category $category): bool
    {
        return $category->delete();
    }


    public function cheackActive(array &$data)
    {
        if (!isset($data['is_active'])) {
            return 0;
        }
        return 1;
    }

    public function applySearch(Builder $query, string $search): Builder
    {
        return $query->where('name', 'like', "%{$search}%");
    }


}