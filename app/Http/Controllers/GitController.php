<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\GitVersion;

class GitController extends Controller
{
    
    protected $git;
    /**
     * [__construct description]
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

        $this->git->history();

        $versions = $this->git->all();
        return response()->view('git.index', compact('versions'));
    }

    /**
     * [replaceMessage description]
     * 
     * @param [type] $versions [description]
     * 
     * @return [type]           [description]
     */
    public function replaceMessage($versions)
    {
        foreach ($versions as $version) {
            if (strpos($version->message, " -0700 ")) {
                $version->update(['message'=>preg_replace('\A\N* -0[7,8]00 ', '', $version->message)]);
            }
        }
        return true;
    }

    
}
