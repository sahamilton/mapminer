<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Document;
use App\Exports\CompanyWatchExport;
use App\Exports\UsersExport;
use App\Exports\WatchListExport;
use App\Models\Location;
use App\Models\User;
use App\Models\Watch;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class WatchController extends BaseController
{
    protected $watch;
    public $document;

    /**
     * Display a listing of locations on watch list.
     *
     * @return Response
     */
    public function __construct(Watch $watch, Document $document)
    {
        $this->document = $document;
        $this->watch = $watch;
    }

    public function index()
    {
        $watchlist = $this->watch->getMyWatchList(auth()->user()->id);

        return response()->view('watch.index', compact('watchlist'));
    }

    /**
     * Create a new watched locationed.
     *
     * @return list of watched locations
     */
    public function create($id)
    {
        $this->watch->create(['user_id'=>auth()->user()->id, 'address_id'=>$id]);

        return redirect()->route('watch.index');
    }

    /**
     * Store new watched location.
     */
    protected function add($id)
    {
        return $this->watch->create(['user_id'=>auth()->user()->id, 'address_id'=>$id]);
    }

    /**
     * Remove the specified watched location.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->delete($id);

        return redirect()->route('watch.index');
    }

    /**
     * Delete the specified watched location from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function delete($id)
    {
        $watch = $this->watch->find($id);

        if ($watch && $watch->destroy($id)) {
            return redirect()->route('watch.index')->with('success', 'Watch item deleted');
        }

        return redirect()->route('watch.index')->with('error', 'Unable to delete that item');
    }

    /**
     * Show watch list for user.
     *
     * @param  int  $user_id
     * @return list of watched locations for given user
     */
    public function watching($user)
    {
        $watch = $this->watch->getMyWatchList($user->id);

        return response()->view('watch.show', compact('watch', 'user'));
    }

    /**
     * Create CSV of watch list.
     *
     * @return Response
     */
    public function export($id = null)
    {
        if (! $id) {
            $id = auth()->id();
        }
        $user = User::find($id);

        return Excel::download(new WatchListExport($id), 'Watch_List_for_'.$user->fullName().'.csv');
        /*Excel::download('Watch_List_for_'.$user->fullName(),function($excel) use($id) {
            $excel->sheet('Watching',function($sheet) use($id) {
                $result = $this->watch->getMyWatchList($id);
                $sheet->loadview('watch.export',compact('result'));
            });
        })->download('csv');*/
    }

    public function showwatchmap()
    {
        $data = null;
        $result = $this->watch->getMyWatchList(auth()->user()->id);

        if (count($result) > 0) {
            foreach ($result as $row) {
                if ($row->watching) {
                    $lat[] = $row->watching->lat;
                    $lng[] = $row->watching->lng;
                }
            }

            $data['lat'] = array_sum($lat) / count($lat);
            $data['lng'] = array_sum($lng) / count($lng);
        }

        return response()->view('watch.map', compact('data'));
    }

    public function watchmap()
    {
        $locations = $this->watch->getMyWatchList(auth()->user()->id);

        $content = view('watch.watchlistxml', compact('locations'));

        return response($content, 200)
            ->header('Content-Type', 'text/xml');
    }

    public function watchupdate(Request $request)
    {
        switch (request('action')) {
            case 'add':
                if ($this->add(request('id'))) {
                    return 'success';
                } else {
                    return 'error';
                }

                break;

            case 'remove':
                $watch = $this->watch->where('address_id', '=', request('id'))->where('user_id', '=', auth()->id())->firstOrFail();

                if ($watch->destroy($watch->id)) {
                    return 'success';
                } else {
                    return 'error';
                }
                break;
        }
    }

    public function companywatchexport(Request $request)
    {
        if (request()->has('id')) {
            $accounts = explode(',', str_replace("'", '', request('id')));
            $result = Address::whereIn('company_id', $accounts)
                ->has('watchedBy')
                ->with('relatedNotes', 'relatedNotes.writtenBy', 'company', 'watchedBy', 'watchedBy.person')
                ->get();

            return Excel::download(new CompanyWatchExport($result), 'ActiveWatcher.csv');
            /*Excel::download(
                'Watch_List_for_', function ($excel) use ($accounts) {
                    $excel->sheet(
                        'Watching', function ($sheet) use ($accounts) {
                            $result = Location::whereIn('company_id', $accounts)->has('watchedBy')
                                ->with('relatedNotes', 'relatedNotes.writtenBy', 'company', 'watchedBy', 'watchedBy.person')
                                ->get();
                            dd($result);
                            $sheet->loadview('watch.companyexport', compact('result'));
                        }
                    );
                }
            )->download('csv');
            */
        }
    }

    public function getCompaniesWatched()
    {
        $watch = $this->watch->getMyWatchList(auth()->user()->id);
        $data['verticals'] = $this->watch->getUserVerticals();

        if ($data['verticals']) {
            $data['verticals'] = null;
        }
        $data['salesprocess'] = null;
        $documents = $this->document->getDocumentsWithVerticalProcess($data);

        return response()->view('resources.show', compact('watch', 'documents'));
    }
}
