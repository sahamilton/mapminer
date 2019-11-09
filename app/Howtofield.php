<?php
namespace App;

class Howtofield extends NodeModel
{

    // Add your validation rules here
    
    // Don't forget to fill this array
    protected $fillable = ['id', 'fieldname','required','type','fieldvalues','fieldgroup','sequence', 'parent_id', 'active'];
    protected $orderColumn = 'sequence';
    public function usedBy()
    {
        
        return belongsToMany(Company::class);
    }

    public function getTypes()
    {
        return ['tab'=>'tab',
                'text'=>'text',
                'textarea'=>'textarea',
                'file'=>'file',
                'select'=>'select',
                'multiselect'=>'multiselect',
                'checkbox'=>'checkbox',
                'radio'=>'radio'];
    }
}
