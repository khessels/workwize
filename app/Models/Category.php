<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    // self referencing table.
    // https://blog.ghanshyamdigital.com/building-a-self-referencing-model-in-laravel-a-step-by-step-guide

    use HasFactory;
    protected $fillable = ['id', 'parent_id', 'label', 'description', 'icon', 'active'];


    /**
     * This will give model's Parent
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * This will give model's Parent, Parent's parent, and so on until root.
     * @return BelongsTo
     */
    public function parentRecursive(): BelongsTo
    {
        return $this->parent()->with('parentRecursive');
    }

    /**
     * Get current model's all recursive parents in a collection in flat structure.
     */
    public function parentRecursiveFlatten()
    {
        $result = collect();
        $item = $this->parentRecursive;
        if ($item instanceof Category) {
            $result->push($item);
            $result = $result->merge($item->parentRecursiveFlatten());
        }
        return $result;
    }

    /**
     * This will give model's Children
     * @return HasMany
     */
    public function childrenWithParent(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * This will give model's Children, Children's Children and so on until last node.
     * @return HasMany
     */
    public function children(): HasMany
    {
        return $this->childrenWithParent()->with('children');
    }
    /**
     * This will give model's items
     * @return HasMany
     */
    public function itemsWithParent(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * This will give model's items, item's Children and so on until last node.
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->itemsWithParent()->with('items');
    }
}
