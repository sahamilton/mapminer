<?php

/**
 * This file is part of the GeocoderLaravel library.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * Added new geocode api key
 */

use Geocoder\Provider\Chain\Chain;
use Geocoder\Provider\GeoPlugin\GeoPlugin;
use Geocoder\Provider\GoogleMaps\GoogleMaps;
use Http\Client\Curl\Client;


return [
    'cache-duration' => 999999999,
    'providers' => [
         Chain::class => [
            GoogleMaps::class => [
                'en-US',
                env('GOOGLE_MAPS_GEOCODE_API_KEY'),
            ],
           
         ],
        
         GoogleMaps::class => [
            'us',
            env('GOOGLE_MAPS_GEOCODE_API_KEY'),
         ],
    ],
    'adapter'  => Client::class,
];
