<?php

namespace App\Models;

use Goutte\Client;

class HtmlReader implements TextInterface
{
    /**
     * [read description].
     *
     * @param  [type] $location [description]
     * @return [type]           [description]
     */
    public function read($location)
    {
        $client = new Client();
        $guzzleClient = new \GuzzleHttp\Client(
            [
            'timeout' => 90,
            'verify' => false,
            ]
        );
        $client->setClient($guzzleClient);
        $crawler = $client->request('GET', $location);
        // get the body of the page
        $plaintext = $crawler->filter('body');
        // remove script tags
        foreach ($plaintext->filter('script') as $node) {
            $node->parentNode->removeChild($node);
        }
        //remove style tags
        foreach ($plaintext->filter('style') as $node) {
            $node->parentNode->removeChild($node);
        }

        return $plaintext->text();
    }
}
