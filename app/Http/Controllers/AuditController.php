<?php

namespace App\Http\Controllers;
use \OwenIt\Auditing\Models\Audit;
use Illuminate\Http\Request;
use App\User;
use App\Person; 

class AuditController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->view('audit.index');
    }
    /**
     * [show description]
     * 
     * @param Audit $audit [description]
     * 
     * @return view        [description]
     */
    public function show(Audit $audit)
    {
        $audit->load('user.person');
        
        switch($audit->auditable_type) {
        case "App\User":
            $model = User::with('person')->withTrashed()->find($audit->auditable_id);
            break;

        case "person":
        case "App\Person":
            $model = Person::with('userdetails')->withTrashed()->find($audit->auditable_id);
            break;
        
        default:
                dd($audit);
            break;
        }
        return response()->view('audit.show', compact('model', 'audit'));

    }
}
