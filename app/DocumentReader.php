<?php

namespace App;

use App\User;

use Goutte\Client;
use Carbon\Carbon;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;





class DocumentReader extends Model
{
    
    public function plaintext(Request $request)
    {
            
            $data = $request->all();
           
            if ($request->hasFile('file')) {
                
             /*   $data['location'] =  
                $this->uploadStoreDocument($request->file('file'));*/

                $data['location'] = asset(Storage::url($request->file('file')->store('public/library')));
               
              
        
            }
            if($request->has('plaintext') && $request->get('plaintext') != '')
            {
                $plaintext['text'] = $request->get('plaintext');
                $plaintext['doctype'] = 'store';

            }else{

                $type =  get_headers($data['location'], 1)["Content-Type"];
                dd($type);
                //set type here and pass array back;
                // if application then get file content

                if(strpos($type, 'application')!== false)
                    {
                        $plaintext = $this->getFileContents($data['location']);

                    }else{
                       
                       // $plaintext['text'] = $this->scrapeWebPage($location);
                        $plaintext['doctype'] ='html';
                        $document =  new HtmlReader(); 
                        $plaintext['text']= $this->cleanse($document->read($data['location']));
                    }
            }
        return $plaintext;
       
     

       //$filepath = $document->location;

       //$text = new DocxConversion($document->location);
       //echo $text->convertToText();
    }

    private function getFileContents($location)
    {

            $storage = public_path()."/library";

            $filename = $storage . "/". basename ( $location);

            $fileArray = pathinfo($filename);
            $file_ext  = $fileArray['extension'];
            $class = "App\\". ucwords($file_ext).'Reader';
            $document = new $class($filename);   
            $plaintext['doctype'] =$file_ext;
            $plaintext['text'] = $document->read($filename);
            return $plaintext;
    }



    
    private function cleanse($data)
    {

            $data['text'] = trim( preg_replace('/\r\n?/', " ", $data['text']));
            $data['text'] = trim(str_replace("  "," ",$data['text']));
            $data['text'] = trim(preg_replace('/\t+/', ' ',$data['text']));
            $data['text'] = trim( preg_replace("/\\n/", " ", $data['text']));
            $data['text'] = trim( strip_tags($data['text']));
            return $data;
    }

    private function uploadStoreDocument($file)
    {   
      
          $destinationPath = public_path() .'/library';
          // upload path
          $fileName = time().$file->getClientOriginalName()  ; // renaming image
         
          $file->move($destinationPath, $fileName); // uploading file to given path
          $fileName = asset('library/'. $fileName);

          return $fileName;
    
    }
   
}