<?php
namespace App;

class Howtofield extends NodeModel
{

    // Add your validation rules here
    
    // Don't forget to fill this array
    protected $fillable = ['id', 'fieldname','required','type','values','group','sequence', 'parent_id'];
    protected $orderColumn = 'sequence';
    public function usedBy()
    {
        
        return belongsToMany(Company::class);
    }

    public function getTypes()
    {
        return ['text'=>'text',
                'textarea'=>'textarea',
                'file'=>'file',
                'select'=>'select',
                'multiselect'=>'multiselect',
                'checkbox'=>'checkbox',
                'radio'=>'radio'];
    }
}
