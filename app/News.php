<?php

namespace App;

use Carbon\Carbon;

class News extends Model
{
    // Don't forget to fill this array
    protected $fillable = ['title', 'news', 'datefrom', 'dateto', 'slug', 'user_id'];
    public $dates = ['created_at', 'updated_at', 'datefrom', 'dateto'];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(Comments::class);
    }

    public function serviceline()
    {
        return $this->belongsToMany(Serviceline::class);
    }

    public function relatedRoles()
    {
        return $this->belongsToMany(Role::class, 'news_role', 'news_id', 'role_id');
    }

    public function relatedIndustries()
    {
        return $this->belongsToMany(SearchFilter::class, 'news_searchfilter', 'news_id', 'searchfilter_id');
    }

    public function currentNews($slug = null)
    {
        $nonews = auth()->user()->nonews;
        
        $now = now('America/Vancouver');
        if (! isset($nonews)) {
            $nonews = now('America/Vancouver')->subYear();
        }

        $news = $this->where('datefrom', '>=', $nonews)
            ->where('dateto', '>=', $now)
            ->whereHas(
                'serviceline', function ($q) {
                    $q->whereIn('serviceline_id', $this->getUserServiceLines());
                }
            )
            ->where(
                function ($query) {
                    $query->whereHas(
                        'relatedIndustries', function ($q) {
                            $q->whereIn('searchfilter_id', auth()->user()->person()->first()->industryfocus()->pluck('searchfilters.id')->toArray());
                        }
                    )
                    ->orWhere(
                        function ($q) {
                            $q->doesntHave('relatedIndustries');
                        }
                    );
                }
            )
            ->where(
                function ($query) {
                    $query->whereHas(
                        'relatedRoles', function ($q) {
                            $q->whereIn('role_id', auth()->user()->roles->pluck('id')->toArray());
                        }
                    )
                    ->orWhere(
                        function ($q) {
                            $q->doesntHave('relatedRoles');
                        }
                    );
                }
            );
        if ($slug) {
            return $news->where('slug', '=', $slug)->orderBy('datefrom', 'desc')->first();
        } else {
            return $news->orderBy('datefrom', 'desc')->get();
        }
    }

    /**
     * [reach description].
     *
     * @return [type] [description]
     */
    public function reach()
    {
        $roles = Role::withCount('assignedRoles')->whereIn('id', $this->relatedRoles->pluck('id')->toArray())->get();

        return $roles->sum('assigned_roles_count');
    }

    /**
     * [audience description].
     *
     * @param [type] $id [description]
     *
     * @return [type]     [description]
     */
    public function audience($id)
    {
        

        // find all people by role
        $audience = [];
        $news = $this->with('relatedRoles', 'relatedRoles.assignedRoles', 'relatedIndustries', 'relatedIndustries.people')->find($id);

        // Get roles
        foreach ($news->relatedRoles as $role) {
            $roleaudience[] = $role->assignedRoles->pluck('id')->toArray();
        }
        if (isset($roleaudience)) {
            foreach ($roleaudience as $group) {
                $audience = array_merge($group, $audience);
            }
        }
        // Get industry verticals
        foreach ($news->relatedIndustries as $vertical) {
            $industryaudience[] = $vertical->people->pluck('user_id')->toArray();
        }

        if (isset($industryaudience)) {
            foreach ($industryaudience as $group) {
                $audience = array_merge($group, $audience);
            }
        }

        return $audience;

        // find all people by vertical
        //
        // get unique number
    }
}
