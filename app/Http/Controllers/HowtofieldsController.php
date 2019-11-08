<?php
namespace App\Http\Controllers;

use App\Howtofield;
use App\Http\Requests\HowtofieldsFormRequest;
use Carbon\Carbon;
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
        $howtofields = $this->howtofield->whereNull('parent_id')->first()->getDescendants();
      
        
        
        return response()->view('howtofields.index', compact('howtofields'));
    }

    /**
     * Show the form for creating a new howtofield
     *
     * @return Response
     */
    public function create()
    {
        $groups = $this->howtofield->where('depth', 1)->get();
        $types = $this->howtofield->getTypes();

        return response()->view('howtofields.create', compact('groups', 'types'));
    }

    /**
     * Store a newly created howtofield in storage.
     *
     * @return Response
     */
    public function store(HowtofieldsFormRequest $request)
    {
        
        if (request()->filled('addGroup')) {
            $request->request->add(['group' => request('addGroup')]);
        }
        $this->howtofield->create(request()->all());
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
        $groups = $this->howtofield->select('group')->distinct()->get();
        $types = $this->howtofield->getTypes();
        return response()->view('howtofields.edit', compact('howtofield', 'groups', 'types'));
    }

    /**
     * Update the specified howtofield in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(HowtofieldsFormRequest $request, Howtofield $howtofield)
    {
        

        if (request()->filled('addGroup')) {
            $request->request->add(['group' => request('addGroup')]);
        }

        $howtofield->update(request()->all());


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
            foreach ($el->children as $child) {
                
                $c++;
                $item[$child->id] = ['parent_id'=>$el->id, 'sequence'=>$c];
            }
        }
        foreach ($item as $key=>$value) {
            $field = $this->howtofield->findOrFail($key);
            $field->update($value);
        }
        
        $this->howtofield->rebuild();
    }


}
