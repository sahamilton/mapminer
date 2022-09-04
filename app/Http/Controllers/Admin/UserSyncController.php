<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Person;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UserSyncDeleteRequest;
use App\Http\Requests\UserSyncSelectRequest;
use App\Jobs\NotifyManagerOfDeletedUsers;
class UserSyncController extends Controller
{
    public function index()
    {
        $methods = [
            'delete'=>[
                'text'=>'DeActivate users on Employee ID', 
                'icon'=>'far fa-trash-alt'
            ]
        ];
        return response()->view('admin.users.sync.index', compact('methods'));
    }
    public function delete()
    {
        
        return response()->view('admin.users.sync.delete');
    }
    
    
    public function confirm(Request $request) : \Illuminate\Http\Response
    {
        $validated = $request->validate(
            [
            'employee_ids' => 'required|filled',
            
            ]
        );
        $pattern = '/(\t)/i';
        $deactivate = preg_split("/\r\n|\n|\r/", preg_replace($pattern, "", request()->employee_ids));
   
        $users = $this->_getUsersFromEmployeeID($deactivate);
        $message = "We were able to match " . $users->count() . " of these ". count($deactivate) ." employee ids.";
        $request->session()->flash('success', $message); 
        return response()->view('admin.users.sync.confirm', compact('users'))
            ;
    }
    /**
     * Used when the confirm method fails validation
     * 
     * @param Request $request [description]
     * 
     * @return Illuminate\Http\Response           [description]
     */
    public function reconfirm(Request $request) : \Illuminate\Http\Response
    {
        $originalusers = explode(",", request()->old('originalusers'));
        $users = $this->_getUsersFromEmployeeID($originalusers);
        return response()->view('admin.users.sync.confirm', compact('users'));
    }
    public function purge(UserSyncDeleteRequest $request)
    {
        $confirmed = request('confirmed'); 
        /*
        
        User::whereIn('id', $confirmed)->delete();
        Person::whereIn('user_id', $confirmed)->delete();
          
            //flash message
            send email to reporting manager ($confirmed with trashed)
            log results (auth, user_id, date)
        */
        NotifyManagerOfDeletedUsers::dispatch($confirmed);
        return redirect()->route('users.sync')->with('success', count($confirmed) . " users have been deleted from Mapminer. Their manager has been notified.");
    }

    private function _getUsersFromEmployeeID(array $deactivate) : \Illuminate\Database\Eloquent\Collection
    {
        return  User::whereIn('employee_id', $deactivate)
            ->with('person.reportsTo', 'roles')
            ->with('person.directReports')
            ->get();
    }
}
