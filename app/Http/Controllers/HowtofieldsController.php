<?php
namespace App\Http\Controllers;

use App\Howtofield;
use App\HowtofieldImport;
use App\Http\Requests\HowtofieldsFormRequest;
use Carbon\Carbon;
use App\Company;
use Illuminate\Http\Request;
class HowtofieldsController extends BaseController
{
    public $howtofield;
    
    /**
     * Display a listing of howtofields
     *
     * @return Response
     */
    public function __construct(Howtofield $howtofield)
    {
        
        $this->howtofield = $howtofield;
    }
    
     
    public function index()
    {
        $howtofields = $this->howtofield
            ->whereNull('parent_id')
            ->first()->getDescendants();
      
      
      
        
        return response()->view('howtofields.index', compact('howtofields'));
    }

    /**
     * Show the form for creating a new howtofield
     *
     * @return Response
     */
    public function create()
    {
        $parents = $this->howtofield->where('depth', 1)
            ->where('active', 1)
            ->orderBy('sequence')
            ->get();
        $types = $this->howtofield->getTypes();

        return response()->view('howtofields.create', compact('parents', 'types'));
    }

    /**
     * Store a newly created howtofield in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        
        
        $this->howtofield->create(request()->all());
        $this->howtofield->rebuild();
        return redirect()->route('howtofields.index');
    }

    /**
     * Display the specified howtofield.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Howtofield $howtofield)
    {
        

        return response()->view('howtofields.show', compact('howtofield'));
    }

    /**
     * Show the form for editing the specified howtofield.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Howtofield $howtofield)
    {
        $parents = $this->howtofield->where('depth', 1)->get();
        $types = $this->howtofield->getTypes();
        return response()->view('howtofields.edit', compact('howtofield', 'parents', 'types'));
    }

    /**
     * Update the specified howtofield in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(HowtofieldsFormRequest $request, Howtofield $howtofield)
    {
        
        $howtofield->update(request()->all());
        if (! request()->has('active')) {
        
            $howtofield->update(['active'=>0]);
        }
        if (! request()->has('required')) {
            $howtofield->update(['required'=>0]);
        }
        
        $howtofield->rebuild();

        return redirect()->route('howtofields.index');
    }

    /**
     * Remove the specified howtofield from storage.
     *
     * @param  int  $id
     * @return Redirect
     */
    public function destroy($id)
    {
        $this->howtofield->destroy($id);

        return redirect()->route('howtofields.index');
    }


    public function reorder(Request $request)
    {
        $data = json_decode(request('id'));
        
        $n = 0;
        foreach ($data as $el) {

            $n++;
            $item[$el->id] = ['parent_id'=>43, 'sequence'=>$n];
            $c=0;
            if (isset($el->children)) {
                foreach ($el->children as $child) {
               
                    $c++;
                    $item[$child->id] = ['parent_id'=>$el->id, 'sequence'=>$c];
                }
            }
            
           
        }
        
        foreach ($item as $key=>$value) {
            $field = $this->howtofield->findOrFail($key);
            $field->update($value);
        }
        
        $this->howtofield->rebuild();
    }

    /**
     * [import description]
     * 
     * @return [type] [description]
     */
    public function import()
    {
        
        $queries = HowtofieldImport::orderBy('vertical')->get();
        $vertical = null;
        $count['queries'] = 0;

        foreach ($queries as $query) {
            $count['queries']++;
            if ($vertical != $query->vertical) {

                $vertical = $query->vertical;
                
                $companies = $this->_getCompanies($vertical);
                $count['companies'][$vertical] = $companies->count();
            }

            $this->_addHowtofields($companies, $query);
           
        }
        dd('All done', $count);
    }
    /**
     * [_getCompanies retrieve companies in vertical]
     * 
     * @param  [type] $vertical [description]
     * @return [type]           [description]
     */
    private function _getCompanies($vertical)
    {
        return Company::whereVertical($vertical)->get();
    }
    /**
     * [_addHowtofields Insert how to fields for companies]
     * 
     * @param [type] $companies [description]
     * @param [type] $query     [description]
     *
     * @return count             
     */
    private function _addHowtofields($companies, $query) 
    {
        foreach ($companies as $company) {
            
            $company->salesnotes()->attach($query->fieldid, ['fieldvalue' => $query->fieldvalue]);
        }
    }
}
