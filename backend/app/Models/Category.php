<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public static function createCategory(array $data): self
    {
        return self::create($data);
    }

    public static function updateCategory(self $category, array $data): self
    {
        $category->update($data);
        return $category->refresh();
    }
}
