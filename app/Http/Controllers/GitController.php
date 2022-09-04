<?php

namespace App\Http\Controllers;

use App\Models\GitVersion;
use Illuminate\Http\Request;

class GitController extends Controller
{
    public $git;

    /**
     * [__construct description].
     *
     * @param GitVersion $git [description]
     */
    public function __construct(GitVersion $git)
    {
        $this->git = $git;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->view('git.index');
    }

    public function create()
    {
        $git = new GitVersion;
        $git->history();
        
        return redirect()->back()->withSuccess('git refreshed');
    }

    /**
     * [replaceMessage description].
     *
     * @param [type] $versions [description]
     *
     * @return [type]           [description]
     */
    public function replaceMessage($versions)
    {
        foreach ($versions as $version) {
            if (strpos($version->message, ' -0700 ')) {
                $version->update(['message'=>preg_replace('\A\N* -0[7,8]00 ', '', $version->message)]);
            }
        }

        return true;
    }

    
}
