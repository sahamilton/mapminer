<?php

namespace App\Http\Controllers;

use App\Branch;
use App\Http\Requests\ProjectContactFormRequest;
use App\Http\Requests\ProjectTransferRequest;
use App\Mail\NotifyProjectTransfer;
use App\Note;
use App\Person;
use App\Project;
use App\ProjectSource;
use Excel;
use Illuminate\Http\Request;
use Mail;

class ProjectsController extends BaseController
{
    public $project;
    public $branch;
    public $person;
    public $sources;

    public function __construct(Project $projects, Branch $branch, Person $person, Projectsource $sources)
    {
        $this->project = $projects;
        $this->branch = $branch;
        $this->person = $person;
        $this->sources = $sources;
        parent::__construct($projects);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        session(['geo.type'=>'projects']);

        if (session('geo')) {
            //Kludge for missing session geo data search
            if (! session('geo.number')) {
                session(['geo.number', 5]);
            }

            return redirect()->route('findme');
        }

        return response()->view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $statuses = $this->project->getStatusOptions;

        $project = $this->project
            ->with('companies', 'owner', 'relatedNotes', 'source')
            ->findOrFail($id);

        $branches = $this->branch
            ->whereHas(
                'servicelines', function ($q) {
                    $q->whereIn('servicelines.id', $this->userServiceLines);
                }
            )
            ->nearby($project, '100')
            ->limit(5)
            ->get();

        return response()->view('projects.show', compact('project', 'statuses', 'branches'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function transfer(ProjectTransferRequest $request)
    {
        $project = $this->project->findOrFail(request('project_id'));
        $person = $this->person->whereHas(
            'userdetails', function ($q) use ($request) {
                $q->where('email', '=', request('email'));
            }
        )->first();
        $transferor = $this->person->where('user_id', '=', auth()->user()->id)->first();
        $project->owner()->wherePivot('person_id', '=', auth()->user()->person->id)->detach();
        $project->owner()->attach($person, ['status'=>'Claimed', 'type'=>'project']);
        $this->addTransferNote($request);
        Mail::queue(new NotifyProjectTransfer($project, $person, $transferor));
        //NotifyProjectTransfer
        return redirect()->route('projects.show', $project->id);
    }

    public function updateField(Request $request, $id)
    {
        $input = request()->except('api_token');

        $project = $this->project->findOrFail($id);
        $data = [$input['name']=>$input['value']];

        $project->update($data);
        $response = [
                    'status' => 'success',
                    'msg' => 'Setting created successfully',
                ];

        return response()->json($response);
    }

    /**
     * closeProject user closes project.
     * @param  Request $request [description]
     * @param  int  $id      project id
     * @return redirect to users projects list
     */
    public function closeproject(Request $request, $id)
    {

        // find project
        $project = $this->project->findOrFail($id);
        // update status in project
        $project->pr_status = 'closed';
        $project->save();
        // upate status in person_project

        $project->owner()->updateExistingPivot(auth()->user()->person()->first()->id, ['status'=>'Closed', 'ranking'=>request('ranking')]);

        // add comment in project_note
        $this->addClosingNote($request);

        return redirect()->route('projects.show', $id);
    }

    /**
     * add closing note - user must enter notes on closed project.
     * @param Request $request
     */
    private function addClosingNote(Request $request)
    {
        $note = new Note;

        $note->note = 'Project Closed:'.request('comments');
        $note->type = 'project';
        $note->related_id = request('project_id');
        $note->user_id = auth()->user()->id;
        $note->save();
    }

    /**
     * add closing note - user must enter notes on closed project.
     * @param Request $request
     */
    private function addTransferNote(Request $request)
    {
        $note = new Note;

        $note->note = 'Project Transfered:'.request('comments');
        $note->type = 'project';
        $note->related_id = request('project_id');
        $note->user_id = auth()->user()->id;
        $note->save();
    }

    /**
     * addCompanyContact add Contact Details to project company.
     * @param Request $request [description]
     */
    public function addCompanyContact(ProjectContactFormRequest $request)
    {
        $request->request->add(['user_id', auth()->user()->id]);

        $contact = \App\ProjectContact::create(request()->all());

        return redirect()->back();
    }

    /**
     * [addProjectCompany add New Company to project (probably not user)].
     * @param Request $request [description]
     */
    public function addProjectCompany(Request $request)
    {
        $firm = \App\ProjectCompany::create(request()->all());
        $firm->project()->attach(request('project_id'));

        return redirect()->back();
    }

    /**
     * [findNearbyProjects description].
     * @param  int $distance
     * @param  string  $latlng  lat:lng of search from point
     * @return xml        nearby projects
     */
    public function findNearbyProjects($distance, $latlng)
    {
        $geo = explode(':', $latlng);
        $location = new Project;
        $location->lat = $geo[0];
        $location->lng = $geo[1];

        $limit = 100;
        $result = $this->project
                ->whereHas('source', function ($q) {
                    $q->where('status', '=', 'open');
                })
                ->nearby($location, $distance)
                ->with('owner')
                ->limit(100)
                ->get();

        return  $this->makeNearbyProjectsXML($result);
    }

    public function mapProjects()
    {
        $data['lat'] = '40.1492';
        $data['lng'] = '-86.2595';

        $data['distance'] = 20;
        $data['latlng'] = $data['lat'].':'.$data['lng'];
        $data['zoomLevel'] = 9;
        $data['urllocation'] = route('projects.nearby', ['distance'=>$data['distance'], 'latlng'=>$data['latlng']]);

        return response()->view('projects.map', compact('data'));
    }

    /**
     * makeNearbyProjectsXML Generate XML of nearby projects.
     * @param  Collection $result nearbyProjects
     * @return XML       [description]
     */
    private function makeNearbyProjectsXML($result)
    {
        $content = view('projects.xml', compact('result'));

        return response($content, 200)
            ->header('Content-Type', 'text/xml');
    }

    /**
     * [claimProject user claim project].
     * @param  int $id project id
     * @return redirect     Redirect to users projects
     */
    public function claimProject($id)
    {
        $project = $this->project->findOrFail($id);
        $project->owner()->attach(auth()->user()->person()->first()->id, ['status'=>'Claimed', 'type'=>'project']);

        return redirect()->route('projects.show', $id);
    }

    public function changeStatus(Request $request)
    {
        $project = $this->project->findOrFail(request('project_id'));
        if (! request()->filled('status')) {
            $project->owner()->detach(auth()->user()->person()->first()->id);
        } else {
            $project->owner()->updateExistingPivot(auth()->user()->person()->first()->id, ['status'=>request('status')]);
        }

        return redirect()->route('projects.show', request('project_id'));
    }

    public function myProjects()
    {
        $projects = $this->_getMyProjects();

        return response()->view('projects.myprojects', compact('projects'));
    }

    public function exportMyProjects()
    {
        $projects = $this->_getMyProjects();
        if ($projects->count() >0) {
            Excel::download(
                'Projects', function ($excel) {
                    $excel->sheet(
                        'Watching', function ($sheet) {
                            $projects = $this->_getMyProjects();
                            $sheet->loadView('projects.export', compact('projects'));
                        }
                    );
                }
            )->download('csv');
        } else {
            return redirect()->back()->withError('You currently have no projects');
        }
        

        return response()->return();
    }

    public function ownedProjects($id)
    {
        $projects = $this->project
            ->whereHas(
                'owner', function ($q) use ($id) {
                    $q->where('id', '=', $id);
                }
            )->with('owner')
            ->get();
        $owner = $this->person->findOrFail($id);

        return response()->view('projects.ownedBy', compact('projects', 'owner'));
    }

    public function projectStats(Request $request)
    {
        if (request()->filled('id')) {
            $id = request('id');
        } else {
            $id = null;
        }

        $projects = $this->project->projectStats($id);
        if ($id && count($projects) > 0) {
            $source = $projects[0]->source;
        }
        $total = $this->project->projectcount();
        $owned = count($this->getOwnedProjects());
        $sources = $this->sources->pluck('source', 'id');

        $projects = $this->createStats($projects);
        $statuses = $this->project->statuses;

        return response()->view('projects.stats', compact('projects', 'statuses', 'total', 'owned', 'source', 'sources'));
    }

    public function exportProjectStats()
    {
        Excel::download(
            'Projects', function ($excel) {
                $excel->sheet(
                    'Stats', function ($sheet) {
                        $projects = $this->project->projectStats($id = null);
                        $projects = $this->createStats($projects);
                        $statuses = $this->project->statuses;
                        $sheet->loadView('projects.exportstats', compact('projects', 'statuses'));
                    }
                );
            }
        )->download('csv');

        return response()->return();
    }

    public function release($id)
    {
        $project = $this->project->with('owner')->findOrFail($id);
        $owner = $project->owner[0]->id;
        $project->owner()->detach();
        $project->pr_status = null;
        $project->save();

        return redirect()->route('project.owner', $owner);
    }

    public function exportowned()
    {
        Excel::download('Projects', function ($excel) {
            $excel->sheet('Watching', function ($sheet) {
                $projects = $this->getOwnedProjects();
                $sheet->loadView('projects.exportowned', compact('projects'));
            });
        })->download('csv');

        return response()->return();
    }

    public function statuses($id = null)
    {
        $projects = $this->getOwnedProjects($id);

        return response()->view('projects.owned', compact('projects'));
    }

    private function createStats($projects)
    {
        $person = null;

        foreach ($this->project->statuses as $status) {
            $personProject['total']['status'][$status] = 0;
        }

        foreach ($projects as $project) {
            if ($project->id != $person) {
                $person = $project->id;
                $personProject[$project->id]['name'] = $project->firstname.' '.$project->lastname;
                $personProject[$project->id]['id'] = $project->id;
            }

            if ($project->pstatus) {
                $personProject[$project->id]['status'][$project->pstatus] = $project->count;
                $personProject[$project->id]['rating'] = $project->rating;
                $personProject['total']['status'][$project->pstatus] = $personProject['total']['status'][$project->pstatus] + $project->count;
            }
        }

        return $personProject;
    }

    private function _getMyProjects()
    {
        return $this->project->with('owner', 'companies')
            ->whereHas(
                'owner', function ($q) {
                    $q->where('person_id', '=', auth()->user()->person()->first()->id);
                }
            )->get();
    }

    private function getOwnedProjects($id = null)
    {
        if ($id) {
            return $this->project->where('project_source_id', '=', $id)
                ->with('source')
                ->has('owner')->get();
        }

        return $this->project->has('owner')->with('source')->get();
    }
}
