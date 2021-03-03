<?php

namespace App;

use Smalot\PdfParser\Document;
use Smalot\PdfParser\Parser;

class PdfReader implements TextInterface
{
    /**
     * [read description].
     *
     * @param  [type] $data [description]
     *
     * @return [type]       [description]
     */
    public function read($data)
    {
        $parser = new Parser();
        try {
            $pdf = $parser->parseFile($data['basepath']);
        } catch (\Exception $e) {
            return '';
        }

        return $pdf->getText();
    }
}
