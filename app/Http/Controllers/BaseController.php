<?php
namespace App\Http\Controllers;
use App\User;
use App\State;
use App\Company;
use App\Model;
use Illuminate\Http\Request;
class BaseController extends Controller {
	
	public $userServiceLines;

    /**
     * Initializer.
     *
     * @access   public
     * @return BaseController
     */
    public function __construct(Model $model)
    {
       $this->middleware(function ($request, $next) use($model){

               $this->userServiceLines = session()->has('user.servicelines') ? session()->get( 'user.servicelines' ) : $model->getUserServiceLines();

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
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

}