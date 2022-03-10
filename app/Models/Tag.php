<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Terranet\Administrator\Traits\Slug\HasSlug;

class Tag extends Model
{
    use HasSlug;

    protected $fillable = ['name','slug'];

    public $timestamps = false;

    public $slugBase='name';

    public function articles()
    {
        return $this->morphedByMany(Article::class, 'taggable');
    }
}
