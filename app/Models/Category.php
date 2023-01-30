<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Category extends Model
{
    use HasFactory, HasUuids;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    /**
     * Get the products for the categories.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public static function getCategory($id)
    {
        $category = Category::where('id', $id)->get();
        return $category;
    }
}
