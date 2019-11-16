<?php

namespace App;

class Salesactivity extends Model implements \MaddHatter\LaravelFullcalendar\IdentifiableEvent
{
    public $table='salesactivity';
    public $fillable=['datefrom','dateto','title','description'];
    public $dates = ['datefrom','dateto'];
// Methods for Calendar
// 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the event's title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Is it an all day event?
     *
     * @return bool
     */
    public function isAllDay()
    {
        return true;
    }

    /**
     * Get the start time
     *
     * @return DateTime
     */
    public function getStart()
    {
        return $this->datefrom;
    }
    /**
     * [getEventOptions description]
     * 
     * @return [type] [description]
     */
    public function getEventOptions()
    {
        return [
            'url' => route('salesactivity.show', $this->id),
            //etc
        ];
    }
    /**
     * Get the end time
     *
     * @return DateTime
     */
    public function getEnd()
    {
        return $this->dateto;
    }
    /**
     * [salesprocess description]
     * 
     * @return [type] [description]
     */
    public function salesprocess()
    {
        return $this->belongsToMany(SalesProcess::class, 'activity_process_vertical', 'activity_id', 'salesprocess_id')->withPivot('company_id');
    }
    /**
     * [vertical description]
     * 
     * @return [type] [description]
     */
    public function vertical()
    {
        return $this->belongsToMany(SearchFilter::class, 'activity_process_vertical', 'activity_id', 'vertical_id')->withPivot('salesprocess_id');
    }
    /**
     * [vertical description]
     * 
     * @return [type] [description]
     */
    public function companies()
    {
        return $this->belongsToMany(Company::class, 'activity_process_company', 'activity_id', 'company_id')->withPivot('salesprocess_id');
    }
    /**
     * [relatedDocuments description]
     * 
     * @return [type] [description]
     */
    public function relatedDocuments()
    {
     
        return Document::whereHas(
            'process', function ($q) {
                $q->whereIn('id', $this->salesprocess()->pluck('salesprocess_id'));
            }
        )
        ->whereHas(
            'vertical', function ($q) {
                $q->whereIn('id', $this->vertical()->pluck('vertical_id'));
            }
        )
        ->with('process', 'vertical', 'rankings', 'score')
        ->get();
    }

    /**
     * [campaignleads description]
     * 
     * @return [type] [description]
     */
    public function campaignLeads()
    {
        return $this->belongsToMany(Address::class);
    }
    /**
     * [states description]
     * 
     * @return [type] [description]
     */
    public function states()
    {
        return $this->belongsToMany(State::class);
    }
    /**
     * [relatedSalesReps description]
     * 
     * @return [type] [description]
     */
    public function relatedSalesReps()
    {
        $salesreps = User::whereHas(
            'roles', function ($q) {
                $q->where('name', '=', 'Sales');
            }
        )->get();
    }
    /**
     * [currentActivities description]
     * 
     * @return [type] [description]
     
    public function currentActivities()
    {

        return $this->where('datefrom', '<=', date('Y-m-d'))
            ->where('dateto', '>=', date('Y-m-d'));
    }*/
    /**
     * [scopeCurrentActivities description]
     * 
     * @param Query $query [<description>]
     * 
     * @return [type] [description]
     */
    public function scopeCurrentActivities($query)
    {

        return $query->where('datefrom', '<=', now())
            ->where('dateto', '>=', now());
    }
    /**
     * [campaignparticipants description]
     * 
     * @return [type] [description]
     */
    public function campaignparticipants()
    {
        return $this->belongsToMany(Person::class)->withPivot('role');
    }
    /**
     * [campaignBranches description]
     * 
     * @return [type] [description]
     */
    public function campaignBranches()
    {
        return $this->belongsToMany(Branch::class);
    }
    /**
     * [campaignSalesReps description]
     * 
     * @return [type] [description]
    
    public function campaignSalesReps()
    {
        $verticals = $this->vertical->pluck('id')->toArray();
        return Person::with('userdetails', 'reportsTo', 'reportsTo.userdetails')
            ->whereHas(
                'userdetails.roles', function ($q) {
                    $q->where('role_id', '=', 5);
                }
            )
            ->whereHas(
                'userdetails', function ($q) {
                    $q->where('confirmed', '=', 1);
                }
            )
            ->where(
                function ($query) use ($verticals) {
                    $query->whereHas(
                        'industryfocus', function ($q) use ($verticals) {
                            $q->whereIn('search_filter_id', $verticals);
                        }
                    );
                }
            )
            ->whereNotNull('lat')
            ->whereNotNull('lng')
            ->get();
    } */
    /**
     * [getCampaignBranches description]
     * 
     * @param [type] $request [description]
     * 
     * @return [type]          [description]
     */
    public function getCampaignBranches($request)
    {
        if (isset($request['org']['mm'])) {
            return Person::findOrFail($request['org']['mm'])->myBranches();
            
        } elseif (isset($request['org']['rvp'])) {
            return Person::findOrFail($request['org']['rvp'])->myBranches();
        } elseif (isset($request['org']['rvp'])) {
            return Person::findOrFail($request['org']['svp'])->myBranches();
        }
        return Branch::all()->pluck('branchname', 'id')->toArray();
    }
}
