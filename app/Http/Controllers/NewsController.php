<?php
namespace App\Http\Controllers;

use App\News;
use Carbon\Carbon;
use App\User;
use App\Serviceline;
use App\Person;
use App\Role;
use App\SearchFilter;
use App\Http\Requests\NewsFormRequest;

class NewsController extends BaseController
{

    
    public $news;

    /**
     * [__construct description]
     * 
     * @param News $news [description]
     */
    public function __construct(News $news)
    {
        $this->news = $news;
        
        parent::__construct($news);
    }
    
    /**
     * Display a listing of news
     *
     * @return Response
     */
    public function index()
    {
        
        
        $news = $this->news
            ->whereHas(
                'serviceline', function ($q) {
                    $q->whereIn('serviceline_id', $this->userServiceLines);
                }
            )
            ->with('author', 'author.person', 'serviceline', 'comments')
            ->orderBy('datefrom', 'DESC')->get();

        return response()->view('news.index', compact('news'));
    }
    /**
     * [admin description]
     * 
     * @return [type] [description]
     */
    public function admin()
    {
    
        $news = $this->news
            ->whereHas(
                'serviceline', function ($q) {
                    $q->whereIn('serviceline_id', $this->userServiceLines);
                }
            )
        ->with('comments')
        ->orderBy('datefrom', 'DESC')->get();
       
        return response()->view('news.index', compact('news'));
    }
    /**
     * Show the form for creating a new news
     *
     * @return Response
     */
    public function create()
    {
        
        $filters = new SearchFilter;
        $verticals = $filters->industrysegments();
        $servicelines = Serviceline::whereIn('id', $this->news->getUserServiceLines())
        ->pluck('serviceline', 'id')
        ->toArray();
        $roles=Role::all();
        $mode='create';

        return response()->view(
            'news.create', 
            compact('servicelines', 'verticals', 'roles', 'mode')
        );
    }

    /**
     * [store description]
     * 
     * @param NewsFormRequest $request [description]
     * 
     * @return [type]                   [description]
     */
    public function store(NewsFormRequest $request)
    {
        

        $data = request()->all();
        $data = $this->setDates($data);
        if ($news = $this->news->create($data)) {
            $news->serviceline()->attach(request('serviceline'));
            if (request()->filled('vertical')) {
                $news->relatedIndustries()->attach(request('vertical'));
            }
            if (request()->filled('roles')) {
                $news->relatedRoles()->attach(request('roles'));
            }
        }
        
        return redirect()->route('news.index');
    }
    /**
     * [currentNews description]
     * 
     * @return [type] [description]
     */
    public function currentNews()
    {
        $news = $this->news->currentNews();
        return response()->view('news.index', compact('news'));
    }
    /**
     * [show description]
     * 
     * @param [type] $slug [description]
     * 
     * @return [type]       [description]
     */
    public function show($slug)
    {

        $news = $this->news->with('relatedRoles')
            ->where('slug', '=', $slug)->first();
        
        if (! $news) {
            return redirect()->route('currentnews')
                ->with('message', "No news found");
        }
        return response()->view('news.show', compact('news'));
    }

    /**
     * Show the form for editing the specified news.
     *
     * @param int $id 
     * 
     * @return Response
     */
    public function edit($id)
    {
        $filters = new SearchFilter;
        $verticals = $filters->industrysegments();

        $news= $this->news
            ->with('author', 'author.person', 'serviceline', 'relatedRoles', 'relatedIndustries')
            ->findOrFail($id);
        $mode='edit';


        $roles=Role::all();
        $servicelines = Serviceline::whereIn('id', $this->userServiceLines)
            ->pluck('serviceline', 'id')
            ->toArray();
        return response()->view(
            'news.edit', 
            compact('news', 'servicelines', 'verticals', 'roles', 'mode')
        );
    }

    /**
     * [update description]
     * 
     * @param NewsFormRequest $request [description]
     * @param [type]          $id      [description]
     * 
     * @return [type]                   [description]
     */
    public function update(NewsFormRequest $request, $id)
    {
        
        $news = $this->news->findOrFail($id);
        $data = request()->all();

        $data = $this->setDates($data);

        if ($news->update($data)) {
            $news->serviceline()->sync(request('serviceline'));

            
            $news->relatedIndustries()->sync(request('vertical'));


            $news->relatedRoles()->sync(request('roles'));
        }
        return redirect()->route('news.index');
    }

    /**
     * Remove the specified news from storage.
     *
     * @param int $id [desctiption]
     * 
     * @return Response
     */
    public function destroy($id)
    {
        $this->news->destroy($id);

        return redirect()->route('news.index');
    }
    /**
     * [audience description]
     * 
     * @param [type] $id [description]
     * 
     * @return [type]     [description]
     */
    public function audience($id)
    {
        $news = $this->news->findOrFail($id);
        $people = $news->audience($id);
        $audience = User::whereIn('id', $people)
            ->with('person', 'person.industryfocus', 'roles')
            ->get();
        return response()->view('news.audience', compact('news', 'audience'));
    }
    /**
     * [noNews description]
     * 
     * @return [type] [description]
     */
    public function noNews()
    {
        $noNewsDate = now();
        $this->_updateNewsDate($noNewsDate);
    }
    /**
     * [setNews description]
     * 
     */
    public function setNews()
    {

        $noNewsDate = null;
        $this->_updateNewsDate($noNewsDate);
    }
    /**
     * [updateNewsDate description]
     * 
     * @param [type] $noNewsDate [description]
     * 
     * @return [type]             [description]
     */
    private function _updateNewsDate($noNewsDate)
    {
        $user = auth()->user();
        $user->nonews = $noNewsDate;
        // dont want to update the user last updated at fields
        $user->timestamps = false;
        $user->save();
        $user->timestamps = true;
    }
   
}