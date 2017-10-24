<?php
namespace App\Http\Controllers;
use App\News;
use Carbon\Carbon;
use App\User;
use App\Serviceline;
use App\Person;
use App\SearchFilter;
use App\Http\Requests\NewsFormRequest;
class NewsController extends BaseController {

	
	public $news;

	
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
		->whereHas('serviceline', function($q) {
			$q->whereIn('serviceline_id', $this->userServiceLines);

		})
		->with('author','author.person','serviceline','comments')
		->orderBy('datefrom', 'DESC')->get();
		return response()->view('news.index', compact('news'));
	}

	public function admin()
	{
	
		$news = $this->news
		->whereHas('serviceline', function($q) {
					    $q->whereIn('serviceline_id', $this->userServiceLines);

					})
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
		$servicelines = Serviceline::whereIn('id',$this->news->getUserServiceLines())->pluck('serviceline','id')->toArray();
		
		return response()->view('news.create', compact('servicelines','verticals'));
	}

	/**
	 * Store a newly created news in storage.
	 *
	 * @return Response
	 */
	public function store(NewsFormRequest $request)
	{
		
		$data = $request->all();
		$data = $this->setDates($data);
		if($news = $this->news->create($data)){
			$news->serviceline()->attach($request->get('serviceline'));
			if($request->filled('vertical')){
				$news->relatedIndustries()->attach($request->get('vertical'));
			}
			if($request->filled('role')){
				$news->relatedRoles()->attach($request->get('role'));
			}
		}
		
		return redirect()->route('news.index');
	}
	public function currentNews(){
		$news = $this->news->currentNews();
		return response()->view('news.index', compact('news'));

	}
	/**
	 * Display the specified news.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($slug)
	{
		
		$news = $this->news->currentNews($slug);
		
		if(! $news){
			return redirect()->route('currentnews')->with('message',"No news found");
		}
		return response()->view('news.show', compact('news'));
	}

	/**
	 * Show the form for editing the specified news.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$filters = new \App\SearchFilter;
		$verticals = $filters->industrysegments();

		$news = $this->news
		->whereHas('serviceline', function($q) {
					    $q->whereIn('serviceline_id', $this->userServiceLines);

					})

		->with('author','author.person','serviceline','relatedRoles','relatedIndustries')
		->findOrFail($id);


		$servicelines = Serviceline::whereIn('id',$this->userServiceLines)->pluck('serviceline','id')->toArray();
		return response()->view('news.edit', compact('news','servicelines','verticals'));
	}

	/**
	 * Update the specified news in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(NewsFormRequest $request,$id)
	{
		$news = $this->news->findOrFail($id);
		$data = $request->all();
		$data = $this->setDates($data);

		if($news->update($data)) {
			
			$news->serviceline()->sync($request->get('serviceline'));

			$vertical = [];
			$vertical = $request->get('vertical');
			$news->relatedIndustries()->sync($vertical);
			
			$role = [];
			$role = $request->get('role');
			$news->relatedRoles()->sync($role);
			
		}
		return redirect()->route('news.index');
	}

	/**
	 * Remove the specified news from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$this->news->destroy($id);

		return redirect()->route('news.index');
	}
	
	public function audience($id){
		$news = $this->news->findOrFail($id);
		$people = $news->audience($id);
		$audience = User::whereIn('id',$people)->with('person','person.industryfocus','roles')->get();
		return response()->view('news.audience', compact('news','audience'));

	}
	
	public function noNews()
	{

		$noNewsDate = date('Y-m-d h:i:s');
		$user = User::findOrFail(auth()->user()->id);
		$user->nonews = $noNewsDate;
		$user->save();
	}
	
	public function setNews()
	{

		$noNewsDate = NULL;
		$user = User::findOrFail(auth()->user()->id);
		$user->nonews = $noNewsDate;
		$user->save();
	}
	
	private function getPersonId()
	{
		$person = Person::where('user_id','=',auth()->user()->id)->findOrFail();

		return $person->id;
	}
	
	private function setDates($data){
        $data['datefrom'] = Carbon::createFromFormat('m/d/Y', $data['datefrom']);
        $data['dateto'] = Carbon::createFromFormat('m/d/Y', $data['dateto']);
         return$data;
    }
	
	
}