<?php

namespace App;

use ZipArchive;

class XlsxReader implements TextInterface
{
    public function read($data)
    {
        $xml_filename = "xl/sharedStrings.xml"; //content file name
        $zip_handle = new ZipArchive;
        $output_text = "";

        if (true === $zip_handle->open($data['basepath'])) {
            if (($xml_index = $zip_handle->locateName($xml_filename)) !== false) {
                $xml_datas = $zip_handle->getFromIndex($xml_index);
                $dom = new \DomDocument;
                $dom->loadXML($xml_datas, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);

                $output_text .= strip_tags($dom->saveXML());
            } else {
                $output_text .="";
            }
                $zip_handle->close();
        } else {
            $output_text .="";
        }
        return $output_text;
    }
}
