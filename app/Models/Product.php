<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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

    protected $with = ['content', 'tags', 'stocks'];

    public function content(): HasOne {
        return $this->hasOne(ProductContent::class);
    }


    public function tags(): BelongsToMany {
        return $this->belongsToMany(
            Tag::class,
            'product_tags',
            'product_id',
            'tag_id'
        );
    }

    public function stocks(): HasMany {
        return $this->hasMany(ProductStock::class, 'SKU', 'SKU');
    }

    public function similarProducts(): Collection|array {
        $parentId = $this->id;
        return $this->tags()->wherehas('products', function(Builder $query) use ($parentId) {
                    $query->without(['content', 'tags', 'stocks']);
                    $query->whereNot('products.id', $parentId);
                })->with(['products' => function(Builder $query) use ($parentId){
                    $query->without(['content', 'tags', 'stocks']);
                    $query->whereNot('products.id', $parentId);
                }])->get();
    }
}
