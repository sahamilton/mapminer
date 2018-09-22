<?php

namespace App;


class DocReader implements TextInterface
{
    /**
     * read_doc
     *
     */
    public function read($data) {
        $fileHandle = fopen($data['basepath'], "r");

        $line = @fread($fileHandle, filesize($data['basepath']));

        $lines = explode(chr(0x0D),$line);
        $outtext = "";
        
        foreach($lines as $thisline)
          {
            $pos = strpos($thisline, chr(0x00));
            if (($pos !== FALSE)||(strlen($thisline)==0))
              {
              } else {
                $outtext .= $thisline." ";
              }
          }
         return preg_replace("/[^a-zA-Z0-9\s\,\.\-\n\r\t@\/\_\(\)]/"," ",$outtext);
         
    }
}