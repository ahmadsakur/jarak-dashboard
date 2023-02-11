<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    use HasFactory, HasUuids;

    /**
     * The table associated with the model.
     *
     * @var string
     */

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'variant_name',
        'price',
        'product_id'
    ];

    /**
     * Get the product that owns the variants.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public static function getVariant($id)
    {
        return Variant::where('id', $id)->get();
    }
}
