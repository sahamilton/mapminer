<?php
namespace App\Http\Controllers;
use App\User;
use App\State;
use Illuminate\Http\Request;
class BaseController extends Controller {
	
	public $userServiceLines;

    /**
     * Initializer.
     *
     * @access   public
     * @return BaseController
     */
    public function __construct()
    {
       
     
      
        
		
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