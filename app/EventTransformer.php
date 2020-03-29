<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use League\Fractal;

class EventTransformer extends  Fractal\TransformerAbstract
{
    /**
     * [transform description]
     * 
     * @param  Event  $event [description]
     * 
     * @return [type]        [description]
     */
    public function transform($event)
    {
        return [
            'id'      => (int) $event->id,
            'title'   => $event->address ? $event->address->businessname : 'No Name',
            'type'    => $event->activityType ? $event->activityType->activity : 'Unknown',
            'start'   => $event->activity_date,
            'completed' => $event->completed ? '1' : '0',

        ];
    }
}