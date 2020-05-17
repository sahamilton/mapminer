<?php

namespace App\Transfomers;

use App\Activity;
use Illuminate\Database\Eloquent\Model;
use League\Fractal;

class EventTransformer extends  Fractal\TransformerAbstract
{
    /**
     * [transform description]
     * 
     * @param  Activity  $activity [description]
     * 
     * @return [type]        [description]
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