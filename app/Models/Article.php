<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

use Terranet\Administrator\Annotations\ScopeFilter;
use Terranet\Administrator\Services\MediaLibraryProvider;
use Terranet\Administrator\Traits\Slug\HasSlug;


class Article extends Model implements HasMedia
{
    use HasMediaTrait,HasSlug;
    protected $slugBase = 'titre';

    protected $fillable = [  'type', 'etat', 'categorie_id', 'titre', 'extrait', 'texte', 'date', 'seo_title', 'seo_description','slug','nom'];

    protected $dates = [
        'date',
    ];


    const ETATS =['publie'=>'publie', 'brouillon'=>'brouillon'];
    const TYPE_PAGE = 'page';
    const TYPE_BLOG = 'blog';
    const TYPE_SERVICE = 'service';

    const ETAT_PUBLIE = 'publie';
    const ETAT_BROUILLON = 'brouillon';

    public static function bootHasMediaTrait()
    {
        static::deleting(function (HasMedia $entity) {
            if ($entity->shouldDeletePreservingMedia()) {
                return;
            }

            if (in_array(SoftDeletes::class, class_uses_recursive($entity))) {
                if (! $entity->forceDeleting) {
                    return;
                }
            }

            $entity->media()->get()->each(function ($media) use ($entity) {
                MediaLibraryProvider::forModel($entity)->detach($media->id);
            });
        });
    }

    /*
     * Relations
     *
     * @widget
     * @placement main-bottom
     *
     */

    public function categorie()
    {
        return $this->belongsTo(ArticleCategorie::class,'categorie_id');
    }


    /**
     * @widget
     * @placement main-bottom
     *
     * @return MorphToMany
     */
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class,'taggable');
    }

    /*
     * Scopes
     */

    public function scopeBlog($query)
    {
        return $query->where('type', self::TYPE_BLOG);
    }

    public function scopePages($query)
    {
        return $query->where('type', self::TYPE_PAGE);
    }

    public function scopeServices($query)
    {
        return $query->where('type', self::TYPE_SERVICE);
    }

    public function scopeCategorieService(Builder $query)
    {
        return $query->whereHas('categorie', function($q){
            $q->where('slug','service');
        });
    }

    /**
     * @ScopeFilter(name="Publié", icon="far fa-thumbs-up")
     * @param Builder $query
     * @return Builder
     */
    public function scopePublie($query)
    {
        return $query->where('etat', self::ETAT_PUBLIE);
    }

    /**
     * @ScopeFilter(name="Brouillon", icon="far fa-thumbs-down")
     * @param Builder $query
     * @return Builder
     */
    public function scopeBrouillon($query)
    {
        return $query->where('etat', self::ETAT_BROUILLON);
    }



    /*
     * Accessors & Mutators
     */

    public function getTagTitleAttribute()
    {
        return $this->attributes['seo_title'] == '' ? $this->titre : $this->attributes['seo_title'];
    }

    public function getTagMetaDescriptionAttribute()
    {
        return $this->attributes['seo_description'] == '' ? $this->extrait : $this->attributes['seo_description'];
    }

    public function getTypeNomAttribute()
    {
        return isset($this->types[$this->type]) ? $this->types[$this->type] : null;
    }

    public function getIsPostAttribute()
    {
        return $this->type == self::TYPE_BLOG;
    }

    public function getIsPageAttribute()
    {
        return $this->type == self::TYPE_PAGE;
    }

    public function getIsPublieAttribute()
    {
        return $this->etat == self::ETAT_PUBLIE;
    }



    public function setDateAttribute($value)
    {
        if($value)
            $this->attributes['date'] = Carbon::createFromFormat('d/m/Y',$value)->format('Y-m-d');

    }


    public function getNomTitleAttribute()
    {
        return $this->attributes['nom'] === null ? $this->titre : $this->attributes['nom'];
    }

}
