<?php

namespace App;

class Howtofield extends NodeModel {


    // Add your validation rules here

    // Don't forget to fill this array
    protected $fillable = ['id', 'fieldname', 'required', 'type', 'fieldvalues', 'fieldgroup', 'sequence', 'parent_id', 'active'];
    protected $orderColumn = 'sequence';

    
    public function getLftName()
    {
        return 'lft';
    }

    public function getRgtName()
    {
        return 'rgt';
    }

    public function getParentIdName()
    {
        return 'reports_to';
    }

    // Specify parent id attribute mutator
    public function setParentAttribute($value)
    {
        $this->setParentIdAttribute($value);
    }
    public function usedBy()
    {
        return belongsToMany(Company::class);
    }
    protected $parentColumnName = 'parent_id';

    protected $leftColumnName = 'lft';
   
    protected $rightColumnName = 'rgt';
    public function getTypes()
    {
        return ['tab'=>'tab',
                'text'=>'text',
                'textarea'=>'textarea',
                'file'=>'file',
                'select'=>'select',
                'multiselect'=>'multiselect',
                'checkbox'=>'checkbox',
                'radio'=>'radio', ];
    }
}
