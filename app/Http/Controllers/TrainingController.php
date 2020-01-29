<?php

namespace App\Http\Controllers;

use App\Http\Requests\TrainingFormRequest;
use App\Role;
use App\SearchFilter;
use App\Serviceline;
use App\Training;
use Illuminate\Http\Request;

class TrainingController extends BaseController
{
    protected $training;
    public $userVerticals;

    public function __construct(Training $training)
    {
        $this->training = $training;
        parent::__construct($training);
    }

    /**
     * [index description].
     *
     * @return [type] [description]
     */
    public function index()
    {
        $trainings = $this->training->myTraining()->get();
        if (auth()->user()->can('manage_training')) {
            return response()->view('training.index', compact('trainings'));
        } else {
            return response()->view('training.mytrainings', compact('trainings'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all();
        $verticals = $this->getAllVerticals();
        $servicelines = $this->getAllServicelines();
        $selectedRoles = \Input::old('roles', []);
        $mode = 'create';
        $training = null;

        return response()->view('training.create', compact('training', 'roles', 'servicelines', 'verticals', 'selectedRoles', 'mode'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(TrainingFormRequest $request)
    {
        $data = request()->all();
        $data = $this->setDates($data);

        if (request()->has('noexpiration')) {
            $data['dateto'] = null;
        }
        if ($training = $this->training->create($data)) {
            $training->servicelines()->attach(request('serviceline'));
            if (request()->filled('vertical')) {
                $training->relatedIndustries()->attach(request('vertical'));
            }
            if (request()->filled('roles')) {
                $training->relatedRoles()->attach(request('roles'));
            }
        }

        return redirect()->route('training.index');
    }

    /**
     * [show description].
     *
     * @param Training $training [description]
     *
     * @return [type]             [description]
     */
    public function show(Training $training)
    {
        return response()->view('training.view', compact('training'));
    }

    /**
     * [edit description].
     *
     * @param Training $training [description]
     *
     * @return [type]             [description]
     */
    public function edit(Training $training)
    {
        $roles = Role::all();
        $verticals = $this->getAllVerticals();
        $servicelines = $this->getAllServicelines();
        $training->load('relatedRoles', 'relatedIndustries');

        return response()->view('training.edit', compact('training', 'roles', 'servicelines', 'verticals'));
    }

    /**
     * [update description].
     *
     * @param TrainingFormRequest $request  [description]
     * @param Training            $training [description]
     *
     * @return [type]                        [description]
     */
    public function update(TrainingFormRequest $request, Training $training)
    {
        $data = request()->all();
        $data = $this->setDates($data);

        if (request()->has('noexpiration')) {
            $data['dateto'] = null;
        }

        $training->update($data);
        $training->relatedRoles()->sync($data['roles']);
        $training->servicelines()->sync($data['serviceline']);
        $training->relatedIndustries()->sync($data['vertical']);

        return redirect()->route('training.show', $training->id)->withMessage('Training updated');
    }

    /**
     * [destroy description].
     *
     * @param Training $training [description]
     *
     * @return [type]             [description]
     */
    public function destroy(Training $training)
    {
        $training->delete();

        return redirect()->route('training.index')->withWarning('Training deleted');
    }
}
