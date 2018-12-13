<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MyLead extends Lead
{
    

	public $fillable = ['companyname',
			'businessname',
            'customer_number',
			'address',
			'city',
			'state',
			'zip',
            'phone',
			'description',
			'lat',
			'lng',
			'lead_source_id'];
	public $getStatusOptions =  [
    
        1=>'Prospect data is completely inaccurate. No project or project completed.',
        2=>'Prospect data is incomplete and / or not useful.',
        3=>'Prospect data is accurate but there is no sales / service opportunity.',
        4=>'Prospect data is accurate and there is a possibility of sales / service.',
        5=>'Prospect data is accurate and there is a definite opportunity for sales / service'
      ];
    public function relatedLeadNotes() {
      
      return $this->hasMany(MyLeadActivity::class,'related_id')->where('type','=','mylead')->with('writtenBy');
     }

    public function contacts(){
      return $this->hasMany(LeadContact::class,'lead_id');
    }

    public function addClosingNote($request){
        $note = new Note;
        $note->note = "Lead Closed:" .request('comments');
        $note->type = 'lead';
        $note->related_id = $this->id;
        $note->user_id = auth()->user()->id;
        return $note->save();
    }

}
