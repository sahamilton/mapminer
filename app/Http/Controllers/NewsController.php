<?php
namespace App\Http\Controllers;
use App\News;
class NewsController extends BaseController {

	
	public $news;
	public $userServiceLines;
	
	public function __construct(News $news)
	{
		$this->news = $news;
		
		parent::__construct();
	}
	
	/**
	 * Display a listing of news
	 *
	 * @return Response
	 */
	public function index()
	{
		$this->userServiceLines = $this->news->getUserServiceLines();
		$now = date('Y-m-d h:i:s');
		$news = $this->news
		->whereHas('serviceline', function($q) {
					    $q->whereIn('serviceline_id', $this->userServiceLines);

					})

		->with('author','author.person','serviceline','comments')
		->orderBy('startdate', 'DESC')->paginate(10);

		return response()->view('news.index', compact('news'));
	}

	public function admin()
	{
		$this->userServiceLines = $this->news->getUserServiceLines();
		$news = $this->news
		->whereHas('serviceline', function($q) {
					    $q->whereIn('serviceline_id', $this->userServiceLines);

					})
		->with('comments')
		->orderBy('startdate', 'DESC')->get();
		$fields = ['Date From'=>'startdate',
				'Date To'=>'enddate',
				'Title'=>'title',
				'Content'=>'news',
				'Comments'=>'comments',
				'Serviceline'=>'Serviceline',
				'Actions'=>'actions'];
		return response()->view('admin.news.index', compact('news','fields'));
		
	}
	/**
	 * Show the form for creating a new news
	 *
	 * @return Response
	 */
	public function create()
	{
		
		$servicelines = $this->news->getUserServiceLines();
		return response()->view('news.create', compact('servicelines'));
	}

	/**
	 * Store a newly created news in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		
		$data = $this->formatDates(\Input::all());
		$data = $this->makeSlug($data);
		$data['user_id']= \Auth::id();
		$rules = $this->news->rules;
		$rules['slug'] = 'unique:news';
		$rules['serviceline'] = 'required';
		$validator = Validator::make($data, $rules);

		if ($validator->fails())
		{
			return \Redirect::back()->withErrors($validator)->withInput();
		}
		$data['news'] = $this->cleanseTextofDivs($data['news']);
		$this->news = $this->news->create($data);
		$this->news->serviceline()->attach($data['serviceline']);
		return \Redirect::route('news.index');
	}

	/**
	 * Display the specified news.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($slug)
	{
		$this->userServiceLines = $this->news->getUserServiceLines();
		$news = $this->news
		->where('slug','=',$slug)
		->whereHas('serviceline', function($q) {
					    $q->whereIn('serviceline_id', $this->userServiceLines);

					})

		->with('author','author.person','comments','comments.postedBy.person')->get();


		return response()->view('news.show', compact('news'));
	}

	/**
	 * Show the form for editing the specified news.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($news)
	{
		
		$servicelines = $this->news->getUserServiceLines();
		return response()->view('news.edit', compact('news','servicelines'));
	}

	/**
	 * Update the specified news in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		
		$news = $this->news->findOrFail($id);
		$data = $this->formatDates(\Input::all());
		$data = $this->makeSlug($data);
		$data['user_id']= \Auth::id();
		$rules = $this->news->rules;
		$rules['slug'] = 'unique:news,id,'. $id;
		$rules['serviceline'] = 'required';
		$validator = Validator::make($data, $rules);
		
		if ($validator->fails())
		{
			
			return \Redirect::back()->withErrors($validator)->withInput();
		}

		$data['news'] = $this->cleanseTextofDivs($data['news']);
		if($news->update($data)) {
		
			$servicelines = $data['serviceline'];
			
			$news->serviceline()->sync($servicelines);
		}
		return \Redirect::route('admin.news.index');
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

		return \Redirect::route('admin.news.index');
	}
	
	
	private function makeSlug($data)
	{
		if($data['title']!= ""){
			$data['slug'] = strtolower(str_replace(" ","_",$data['title']));
		}
		return $data;
	}

	private function formatDates($data)
	{
		$datefields = ['startdate','enddate'];
		foreach ($datefields as $field){
			
			if($data[$field] != '')
			{
				$data[$field] = date('Y-m-d 00:00:00', strtotime($data[$field]));

			}
		}
		return $data;
	}
	
	public function noNews()
	{

		$noNewsDate = date('Y-m-d h:i:s');
		$user = User::findOrFail(\Auth::id());
		$user->nonews = $noNewsDate;
		$user->save();
	}
	
	public function setNews()
	{

		$noNewsDate = NULL;
		$user = User::findOrFail(\Auth::id());
		$user->nonews = $noNewsDate;
		$user->save();
	}
	
	private function getPersonId()
	{
		$person = Person::where('user_id','=',\Auth::id())->get();

		return $person[0]->id;
	}
	
	public function cleanseTextofDivs($text)
	{
		
		// remove <div> and </div>
		$text = preg_replace("'<div'", "<p",$text);
		$text = preg_replace("'</div>'", "</p>",$text);
		return $text;	
	}
	
	
}
