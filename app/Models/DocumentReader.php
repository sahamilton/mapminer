<?php

namespace App\Models;

use App\User;
use Carbon\Carbon;
use Goutte\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentReader extends Model
{
    public function readDocument(Request $request)
    {
        $data = $request->all();

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $data['extension'] = $file->getClientOriginalExtension();
            $file = $request->file('file')->store('public/library');
            $data['location'] = asset(Storage::url($file));
            $data['basepath'] = base_path().'/public'.Storage::url($file);
            $data['filename'] = basename($file);
            $data['doctype'] = $this->getDocumentType(Storage::mimeType($file));
            $data = $this->getFileContents($data);
        } else {
            $data['location'] = $request->get('location');
            $data['doctype'] = 'html';
            $document = new HtmlReader();
            $data['plaintext'] = $document->read($data['location']);
            $data = $this->cleanse($data);
        }
        $data = $this->convertDates($data);

        return $data;
    }

    private function getFileContents($data)
    {
        $class = 'App\\'.ucwords($data['extension']).'Reader';

        $document = new $class();

        $data['plaintext'] = $document->read($data);

        return $data;
    }

    private function cleanse($data)
    {
        $data['plaintext'] = trim(preg_replace('/\r\n?/', ' ', $data['plaintext']));
        $data['plaintext'] = trim(str_replace('  ', ' ', $data['plaintext']));
        $data['plaintext'] = trim(preg_replace('/\t+/', ' ', $data['plaintext']));
        $data['plaintext'] = trim(preg_replace('/\\n/', ' ', $data['plaintext']));
        $data['plaintext'] = trim(strip_tags($data['plaintext']));

        return $data;
    }

    private function uploadStoreDocument($file)
    {
        $destinationPath = public_path().'/library';
        // upload path
          $fileName = time().$file->getClientOriginalName(); // renaming image

          $file->move($destinationPath, $fileName); // uploading file to given path
        $fileName = asset('library/'.$fileName);

        return $fileName;
    }

    private function getDocumentType($type)
    {
        $types = [
        'xls'=>'application/vnd.ms-excel',
        'xlsx'=>'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'ppt'=>'application/vnd.ms-powerpoint',

        'pptx'=>'application/vnd.openxmlformats-officedocument.presentationml.presentation',

        'doc'=>'application/msword',

        'docx'=>'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'pdf'=>'application/pdf', ];

        return $docType = array_search($type, $types);
    }

    private function convertDates($data)
    {
        $data['datefrom'] = Carbon::createFromFormat('m/d/Y', $data['datefrom']);
        $data['dateto'] = Carbon::createFromFormat('m/d/Y', $data['dateto']);

        return $data;
    }
}
