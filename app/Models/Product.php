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

    protected $with = ['content', 'tag', 'stocks'];

    public function content(): HasOne {
        return $this->hasOne(ProductContent::class);
    }

    public function tag(): HasOne {
        return $this->hasOne(ProductTag::class);
    }

    public function stocks(): HasMany {
        return $this->hasMany(ProductStock::class, 'SKU', 'SKU');
    }
}
