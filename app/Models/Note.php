<?php

namespace App\Models;

class Note extends Model
{
    // Add your validation rules here
    public static $rules = [
        'note' => 'required',
    ];
    // Don't forget to fill this array

    protected $fillable = ['note', 'user_id', 'related_id', 'created_at', 'updated_at', 'type', 'address_id'];
    protected $table = 'notes';

    /**
     * [writtenBy description]
     * 
     * @return [type] [description]
     */
    public function writtenBy()
    {
        return $this->belongsTo(User::class, 'user_id')->with('person');
    }
    /**
     * [relatesToLocation description]
     * 
     * @return [type] [description]
     */
    public function relatesToLocation()
    {
        return $this->belongsTo(Address::class, 'address_id', 'id');
    }
    /**
     * [relatesToLead description]
     * 
     * @return [type] [description]
     */
    public function relatesToLead()
    {
        return $this->belongsTo(Lead::class, 'related_id')->where('type', '=', 'lead');
    }
    /**
     * [relatesToProspect description]
     * 
     * @return [type] [description]
     */
    public function relatesToProspect()
    {
        return $this->belongsTo(Lead::class, 'related_id')->where('type', '=', 'prospect');
    }
    /**
     * [relatesToProject description]
     * 
     * @return [type] [description]
     */
    public function relatesToProject()
    {
        return $this->belongsTo(Project::class, 'related_id')->where('type', '=', 'project');
    }
    /**
     * [relatedContact description]
     * 
     * @return [type] [description]
     */
    public function relatedContact()
    {
        return $this->belongsTo(LeadContact::class, 'contact_id');
    }
    /**
     * [myNotes description]
     * 
     * @return [type] [description]
     */
    public function myNotes()
    {
        return $this->where('user_id', '=', auth()->user()->id)->with('relatesToLocation');
    }
    /**
     * [locationNotes description]
     * 
     * @param [type] $location [description]
     * 
     * @return [type]           [description]
     */
    public function locationNotes($location)
    {
        return $this->where('address_id', '=', $location);
    }
    /**
     * [scopeSearch description]
     * 
     * @param [type] $query  [description]
     * @param [type] $search [description]
     * 
     * @return [type]         [description]
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('note', 'like', "%{$search}%");
    }
}
