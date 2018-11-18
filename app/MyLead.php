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
    
        3=>'No sales / service opportunity.',
        4=>'Possibility of sales / service opportunity.',
        5=>'Definite opportunity for sales / service'
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
