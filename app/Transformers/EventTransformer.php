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
    protected array $defaultIncludes = [
        //
    ];
    
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        //
    ];
    
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Activity $activity)
    {
        
        if (isset($activity->starttime)) {
            $start = $activity->activity_date->format('Y-m-d') .  " ". $activity->starttime->format('H:i:s');
        } else {
            $start   = $activity->activity_date->format('Y-m-d') . ' 00:00:00'; 
        }
       
        if (isset($activity->endtime)) {
            $end = $activity->activity_date->format('Y-m-d') .  " ". $activity->endtime->format('H:i:s');
        } else {
            $end   = $activity->activity_date->format('Y-m-d') . ' 00:00:00'; 
        }
        
        //$start === $end ? $allday =true : $allday = false;
        $activity->relatesToAddress ? $title=" " .$activity->relatesToAddress->businessname : $title.=' No Name';
        return [
            'id'      => (int) $activity->id,
            'title'   => $title,
            'url' => route('address.show', ['address'=>$activity->address_id, 'view'=>'activities']),
            'type'    => $activity->type ? $activity->type->activity : 'Unknown',
            'borderColor' => '#'.$activity->type->color,
            'color'=> $activity->completed ? '#cccccc' : '#cceecc',
            'textColor'=>'#000000',
            'start'   => $start,
            'end'   => $end,
            'allDay'=> $start === $end ? true : false,
            'completed' => $activity->completed ? '1' : '0',

        ];
        
    }
}
