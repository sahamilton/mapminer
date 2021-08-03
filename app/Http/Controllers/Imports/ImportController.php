<?php

namespace App\Http\Controllers\Imports;

use App\Http\Requests\ImportFormRequest;
use App\Imports;
use App\Model;
use App\Project;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;

class ImportController extends BaseController
{
    public $userServiceLines;

    /**
     * [index description].
     *
     * @return [type] [description]
     */
    public function index()
    {
        $imports = ['branches','branch_team','companies','contacts','locations','users'];
        $exports = ['allcompanies','branches','companies','person','projects','vertical','watch', 'persons_data', 'users'];
        return response()->view('imports.index', compact('imports', 'exports'));
    }

    /**
     * [uploadfile description].
     *
     * @param [type] $file [description]
     *
     * @return [type]       [description]
     */
    protected function uploadfile($file)
    {
        $file = $file->store('uploads');
        $data['file'] = $file;
        $data['linkfile'] = asset(\Storage::disk('local')->url("uploads/".$file));

        $data['filename'] = storage_path("/").$file;

        return $data;
    }

    /**
     * [getFileFields description].
     *
     * @param [type] $data [description]
     *
     * @return [type]       [description]
     */
    protected function getFileFields($data)
    {
       
        $content = fopen($data['filename'], 'r');
        $row = 1;
        for ($i = 0; $i < 10; $i++) {
            $fields[$i] = fgetcsv($content);
        }

        return $fields;
    }

    /**
     * [getData description].
     *
     * @param [type] $request [description]
     *
     * @return [type]          [description]
     */
    protected function getData($request)
    {
        $data = request()->all();
        $data['fields'] = array_values(request('fields'));

        return $data;
    }

    /**
     * [validateInput description].
     *
     * @param Request $request [description]
     *
     * @return [type]           [description]
     */
    protected function validateInput(Request $request)
    {
        if ($fields = $this->import->detectDuplicateSelections(request('fields'))) {
            return $error = ['You have to mapped a field more than once.  Field: '.implode(' , ', $fields)];
        }

        if ($fields = $this->import->validateImport(request('fields'))) {
            return $error = ['You have to map all required fields.  Missing: '.implode(' , ', $fields)];
        }

        return false;
    }
}
