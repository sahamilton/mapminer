<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Document extends Model
{
    use Searchable;
    public $table='documents';

    public $dates =['datefrom','dateto'];

    public $fillable=['title','summary','description','plaintext','location','doctype','user_id','datefrom','dateto'];

    public $doctypes =['docx'=>'word','pdf'=>'pdf','html'=>'webpage'];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->with('person');
    }

    public function vertical()
    {
        return $this->belongsToMany(SearchFilter::class, 'document_searchfilter', 'document_id', 'searchfilter_id');
    }

    public function process()
    {
        return $this->belongsToMany(SalesProcess::class, 'document_salesprocess', 'document_id', 'salesprocess_id');
    }

    public function getDocumentsWithVerticalProcess($data)
    {

        return $documents = $this->with('author', 'vertical', 'process')
              ->when($data['verticals'], function ($q) use ($data) {
                  $q->whereHas('vertical', function ($q1) use ($data) {
                      $q1->whereIn('id', $data['verticals']);
                  });
              })
              ->when($data['salesprocess'], function ($q) use ($data) {
                   
                  $q->whereHas('process', function ($q1) use ($data) {
                      $q1->whereIn('id', $data['salesprocess']);
                  });
              })
              ->where('datefrom', '<=', date('Y-m-d'))
              ->where('dateto', '>=', date('Y-m-d'))
              ->get();
    }

    /*
    
    Rank documents

     */
    public function rankings()
    {
        return $this->belongsToMany(User::class)->withPivot('rank');
    }

    public function myranking()
    {
        return $this->belongsToMany(User::class)->where('user_id', '=', auth()->user()->id)->withPivot('rank')->first();
    }

    public function rank()
    {
        return $this->rankings()
        ->selectRaw('document_id, avg(rank) as rank')
        ->groupBy('document_id');
    }
    public function score()
    {
        return $this->rankings()
        ->selectRaw('document_id, sum(rank) as score')
        ->groupBy('document_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scrubDocument()
    {
        $documents = $this->document->all();
        foreach ($documents as $document) {
            $data ['text'] = $document->plaintext;
            $clean = $this->cleanse($data);
            $document->plaintext = $clean['text'];
            $document->save();
        }
    }



    private function cleanse($data)
    {

                $data['text'] = trim(preg_replace('/\r\n?/', " ", $data['text']));
                $data['text'] = trim(str_replace("  ", " ", $data['text']));
                $data['text'] = trim(preg_replace('/\t+/', ' ', $data['text']));
                $data['text'] = trim(preg_replace("/\\n/", " ", $data['text']));
                $data['text'] = trim(strip_tags($data['text']));
                return $data;
    }
}
