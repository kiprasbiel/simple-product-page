<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'SKU',
        'size',
        'photo_url',
        'updated_at'
    ];

    public function content(): HasOne {
        return $this->hasOne(ProductContent::class);
    }

    public function tags(): HasMany {
        return $this->hasMany(ProductTag::class);
    }
}
