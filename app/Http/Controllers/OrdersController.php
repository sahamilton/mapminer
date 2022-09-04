<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Orders;
use App\Models\Person;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public $orders;
    public $branch;
    public $person;

    /**
     * [__construct description].
     *
     * @param Orders $order  [description]
     * @param Branch $branch [description]
     * @param Person $person [description]
     */
    public function __construct(Orders $order, Branch $branch, Person $person)
    {
        $this->person = $person;
        $this->orders = $order;
        $this->branch = $branch;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $myBranches = array_keys($this->person->myBranches());

        $branchOrders = $this->branch->whereIn('id', $myBranches)
            ->with('orders')
            ->get();

        $orders = $branchOrders->map(
            function ($branch) {
                return $branch->orders->sum('orders');
            }
        );

        return response()->view('orders.index', compact('orders', 'branchOrders'));
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
     * @param \Illuminate\Http\Request $request [description]
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id [description]
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $orders = $this->orders->where('branch_id', '=', $id)
            ->with('addresses', 'addresses.company')
            ->branchOrders($id)
            ->get();
        $branch = $this->branch->with('manager')->findOrFail($id);

        return response()->view('orders.show', compact('branch', 'orders'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id [description]
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request [description]
     * @param int                      $id      [description]
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id [description]
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
