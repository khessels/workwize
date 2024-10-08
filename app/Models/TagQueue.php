<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Topic;
class TagQueue extends Model
{
    protected $table = 'tag_queue';
    protected $fillable = ['topic_id', 'name', 'visible', 'enables_at', 'expires_at'];
    use HasFactory;
     public function topic()
     {
         return $this->hasOne(Topic::class, 'id', 'topic_id');
     }
}
