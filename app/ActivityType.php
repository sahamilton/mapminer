<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class ActivityType extends Model
{

    use HasSlug;
    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('activity')
            ->saveSlugsTo('slug');
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public $table = 'activity_type';
    public $fillable = ['activity', 'color', 'definition'];
    /**
     * [activities description]
     * 
     * @return [type] [description]
     */
    public function activities()
    {
         return $this->hasMany(Activity::class, 'activitytype_id');
    }
    /**
     * [scopeActive description]
     * 
     * @param [type] $query [description]
     * 
     * @return [type]       [description]
     */
    public function scopeActive($query)
    {
        return $query->whereActive(1);
    }

}
