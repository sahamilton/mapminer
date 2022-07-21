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
        
        
        $activity->relatesToAddress ? $title=" " .$activity->relatesToAddress->businessname : $title.=' No Name';
        return [
            'id'      => (int) $activity->id,
            'title'   => $title,
            'url' => route('address.show', $activity->address_id),
            'type'    => $activity->type ? $activity->type->activity : 'Unknown',
            'borderColor' => '#'.$activity->type->color,
            'color'=> $activity->completed ? '#cccccc' : '#cceecc',
            'textColor'=>'#000000',
            'start'   => $activity->activity_date->format('Y-m-d'),
            'completed' => $activity->completed ? '1' : '0',

        ];
    }
}
