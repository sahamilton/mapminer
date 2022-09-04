<?php
namespace App\Http\Controllers;

use App\Models\News;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Serviceline;
use App\Models\Person;
use App\Models\Role;
use App\Models\SearchFilter;
use App\Http\Requests\NewsFormRequest;
use Illuminate\Support\Str;

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
            ->orderBy('datefrom', 'desc')->get();

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
        ->orderBy('datefrom', 'desc')->get();
       
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
        $data['slug'] = Str::slug($data['title']);
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
    public function show(News $slug)
    {
  
        $news = $slug->load('relatedRoles');
        
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
    public function edit(News $news)
    {
        $filters = new SearchFilter;
        $verticals = $filters->industrysegments();

        $news->load('author', 'author.person', 'serviceline', 'relatedRoles', 'relatedIndustries');
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
    public function update(NewsFormRequest $request, News $news)
    {
        

        $data = request()->all();
        $data['slug'] = Str::slug($data['title']);
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
    public function destroy(News $news)
    {
        $title = $news->title;
        $news->delete();

        return redirect()->route('news.index')->withMessage("News Item '".$title."' deleted.");
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
        
        $news = News::with('relatedRoles', 'relatedIndustries','serviceline')
            ->findOrFail($id);
        $audience = User::with('person')
        ->whereHas('roles', function ($q) use($news){
          $q->whereIn('roles.id', $news->relatedRoles()->pluck('id')->toArray());
        })->whereHas('serviceline', function ($q) use($news) {
          
         $q->whereIn('servicelines.id', $news->serviceline()->pluck('servicelines.id')->toArray());
          
        })
        ->when($news->relatedIndustries()->count()>0, function ($q) use($news){
          $q->whereHas('person.industryfocus', function ($q) use($news){
            
            $q->whereIn('searchFilter.id', $news->relatedIndustries->pluck('id')->toArray());
          });
        })
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