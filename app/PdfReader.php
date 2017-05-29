<?php

namespace App;

use Smalot\PdfParser\Parser;


class PdfReader implements TextInterface
{
   public function read($data)
   {

	$parser = new Parser();
	$pdf    = $parser->parseFile($data['basepath']);

	 return $pdf->getText();

   }
}

