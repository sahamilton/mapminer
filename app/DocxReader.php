<?php

namespace App;

use ZipArchive;

class DocxReader implements TextInterface
{
    public function read($data)
    {
        $dataFile = 'word/document.xml';
        $zip = new \ZipArchive;

        // Open received archive file
        if (true === $zip->open($data['basepath'])) {
            // If done, search for the data file in the archive
            if (($index = $zip->locateName($dataFile)) !== false) {
                // If found, read it to the string
                $data = $zip->getFromIndex($index);
                // Close archive file
                $zip->close();
                // Load XML from a string
                // Skip errors and warnings
                $xml = new \DOMDocument();
                $xml->loadXML($data, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
                // Return data without XML formatting tags
                return strip_tags($xml->saveXML());
            }
        }
        $zip->close();
    }
}
