<?php

namespace App\Transformers;

use App\Activity;
use League\Fractal\TransformerAbstract;

class EventTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];
    
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];
    
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Activity $activity)
    {
   
        return [
            'id'      => (int) $activity->id,
            'title'   => $activity->relatesToAddress ? $activity->relatesToAddress->businessname : 'No Name',
            'type'    => $activity->type ? $activity->type->activity : 'Unknown',
            'start'   => $activity->activity_date,
            'completed' => $activity->completed ? '1' : '0',

        ];
    }
}
