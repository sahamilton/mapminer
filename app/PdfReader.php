<?php

namespace App;

use Smalot\PdfParser\Parser;
use Smalot\PdfParser\Document;

class PdfReader implements TextInterface
{
   public function read($data)
   {

	$parser = new Parser();
	try{
		$pdf    = $parser->parseFile($data['basepath']);
		
	}
	catch(\Exception $e){

		return '';
	}

	 return $pdf->getText();

   }
}