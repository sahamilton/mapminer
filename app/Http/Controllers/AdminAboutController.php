<?php

namespace App\Http\Controllers;

use App\Models\GitVersion;
use Illuminate\Http\Request;

class AdminAboutController extends Controller
{
    protected $version;

    public function __construct(GitVersion $version)
    {
        $this->version = $version;
    }

    public function getInfo()
    {
        $version = $this->version->get();

        return response()->view('site.about', compact('version'));
    }
}
