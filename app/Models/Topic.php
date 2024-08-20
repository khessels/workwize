<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tag;
class Topic extends Model
{
    protected $table = 'topic';
    protected $fillable = ['name'];
    use HasFactory;

    public function tags()
    {
        return $this->hasMany(Tag::class);
    }
}
