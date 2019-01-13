<?php

namespace App\Http\Controllers;
use App\Orders;
use App\Branch;
use App\Person;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
   public $orders;
   public $branch;
   public $person;
   public function __construct(Orders $order, Branch $branch,Person $person){
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
        $mybranches = array_keys($this->person->myBranches());
        
        $orders = $this->orders->periodOrders($mybranches);
        return response()->view('orders.index',compact('orders'));
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
        
        $orders = $this->orders->where('branch_id','=',$id)
        ->with('addresses','addresses.company')
        ->branchOrders($id)->get();
        $branch = $this->branch->with('manager')->findOrFail($id);
        
        return response()->view('orders.show',compact('branch','orders'));
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
}
