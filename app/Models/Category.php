<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Category extends Model
{
    use HasFactory, HasUuids;
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    public static function getCategory($id){
        $category = Category::where('id', $id)->get();
        return $category;
    }
      
}
