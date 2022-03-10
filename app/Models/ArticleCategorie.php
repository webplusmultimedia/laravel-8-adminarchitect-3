<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Terranet\Administrator\Traits\Slug\HasSlug;

class ArticleCategorie extends Model
{
    use HasSlug;
    protected $table = 'article_categories';

    protected $fillable = ['parent_id', 'slug', 'nom', 'description', 'order', 'seo_title', 'seo_description'];

    protected $slugBase = 'nom';
    public $timestamps = false;

    /*
     * Relations
     */
    public function articles()
    {
        return $this->hasMany(Article::class,'categorie_id');
    }
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('order');
    }
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id', 'id');
    }
    /*
     * Functions
     */
    public function freres()
    {
        return $this->parent->children();
    }
    /*
     * Scopes
     */
    public function scopeRoot($query)
    {
        return $query->where('parent_id', null)->orderBy('order');
    }



    /*
     * Accessors & Mutators
     */
    public function getIsRootAttribute()
    {
        return $this->parent_id === null;
    }
    public function getTagTitleAttribute()
    {
        return $this->attributes['seo_title'] == '' ? $this->nom : $this->attributes['seo_title'];
    }

    public function getTagMetaDescriptionAttribute()
    {
        return $this->attributes['seo_description'] == '' ? $this->description : $this->attributes['seo_description'];
    }
}
