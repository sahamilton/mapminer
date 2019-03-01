<?php
namespace App;

class Note extends Model
{

    // Add your validation rules here
    public static $rules = [
        'note' => 'required'
    ];
    // Don't forget to fill this array

    protected $fillable = ['note','user_id','related_id','created_at','updated_at','type','address_id'];
    protected $table ='notes';


    public function writtenBy()
    {
            return $this->belongsTo(User::class, 'user_id')->with('person');
    }
        
    public function relatesToLocation()
    {

            return $this->belongsTo(Address::class, 'address_id', 'id');
    }
    public function relatesToLead()
    {
            return $this->belongsTo(Lead::class, 'related_id')->where('type', '=', 'lead');
    }

    public function relatesToProspect()
    {
            return $this->belongsTo(Lead::class, 'related_id')->where('type', '=', 'prospect');
    }
    public function relatesToProject()
    {
            return $this->belongsTo(Project::class, 'related_id')->where('type', '=', 'project');
    }
    public function relatedContact()
    {
            return $this->belongsTo(LeadContact::class, 'contact_id');
    }
    public function myNotes()
    {
        return $this->where('user_id', '=', auth()->user()->id)->with('relatesToLocation');
    }

    public function locationNotes($location)
    {
        return $this->where('address_id', '=', $location);
    }
}
