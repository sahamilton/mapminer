<?php
namespace App\Http\Controllers;

use App\User;
use App\Person;
use App\Role;
use App\State;
use App\Company;
use App\Model;
use App\SearchFilter;
use App\Serviceline;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    
    public $userServiceLines;
    public $userVerticals;
    public $userRoles;
    public $person;
    public $user;
    /**
     * Initializer.
     *
     * @access   public
     * @return BaseController
     */
    public function __construct(Model $model)
    {
        $this->middleware(function ($request, $next) use ($model) {

               $this->user = auth()->user();
               $this->userServiceLines = session()->has('user.servicelines') && session()->get('user.servicelines') ? session()->get('user.servicelines') : $model->getUserServiceLines();
               $this->userVerticals = session()->has('user.verticals') && session()->get('user.verticals')  ? session()->get('user.verticals') : $model->getUserVerticals();
               $this->userRoles = session()->has('user.roles') && session()->get('user.roles')  ? session()->get('user.roles') : $model->getUserRoles();
              
                return $next($request);
        });
    }
   
    /**
     * Setup the layout used by the controller.
     *
     * @return void
     */
    protected function setupLayout()
    {
        if (! is_null($this->layout)) {
            $this->layout = View::make($this->layout);
        }
    }
    protected function setDates($data)
    {
        $data['datefrom'] = Carbon::createFromFormat('m/d/Y', $data['datefrom']);
        $data['dateto'] = Carbon::createFromFormat('m/d/Y', $data['dateto']);
         return$data;
    }

    protected function getAllVerticals()
    {
        $filters = new SearchFilter;
        return $filters->industrysegments();
    }
     
    protected function getAllServicelines()
    {
    
        return Serviceline::whereIn('id', $this->userServiceLines)->pluck('serviceline', 'id')->toArray();
    }
}
