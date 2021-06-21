<?php

namespace App\Http\Controllers;

use App\Models\UserStock;
use Illuminate\Http\Request;
use Auth;
class UserStockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $stocks = UserStock::where('user_id','=',Auth::user()->id)->get();
       return view('personal_stocks.index',['stocks' => $stocks]);
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
     * @param  \App\Models\UserStock  $userStock
     * @return \Illuminate\Http\Response
     */
    public function show(UserStock $userStock)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserStock  $userStock
     * @return \Illuminate\Http\Response
     */
    public function edit(UserStock $userStock)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserStock  $userStock
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserStock $userStock)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserStock  $userStock
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserStock $userStock)
    {
        //
    }
}
