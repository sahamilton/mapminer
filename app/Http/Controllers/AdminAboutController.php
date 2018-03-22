<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\GitVersion;

class AdminAboutController extends Controller
{
    protected $version;
    public function __construct(GitVersion $version){
    	$this->version = $version;
    }

    public function getInfo(){
    	$version = $this->version->get();
    	return response()->view('site.about',compact('version'));
    }
}
