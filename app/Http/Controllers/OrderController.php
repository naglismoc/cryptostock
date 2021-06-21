<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Stock;
use App\Models\UserStock;
use Illuminate\Http\Request;
use Auth;
use DB;
class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(Request $request, Stock $stock)
    {
        $order = new Order();
        $order->user_id = Auth::user()->id;
        $order->stock_id = $stock->id;
        $order->status = 1;
        $order->price =  $request->price;
        $order->currency = 3;
        $order->quantity = $request->quantity;
        $order->quantity_left = $request->quantity;
        $order->trigger_price = 0;


        if(isset($_POST['sell'])){
            // DB::enableQueryLog();
            $euros = 0;
            $stocks = Order::where('stock_id','=', $stock->id)->
            where('action','=','buy')->
            where('quantity_left','>','0')->
            where('price','>=',$request->price)->orderBy('price','desc')->get();
            // print_r($stocks);
            // dd(DB::getQueryLog());
            foreach ($stocks as $stock) {
               if($stock->quantity_left > $order->quantity_left){
                $stock->quantity_left -= $order->quantity_left;
                $euros += $order->quantity_left * $stock->price;
                $order->quantity_left = 0;
                $stock->save();
                break;
               }
               if($stock->quantity_left == $order->quantity_left){
                   $euros += $order->quantity_left * $stock->price;
                $stock->quantity_left = 0;
                $stock->save();
                $order->quantity_left = 0;
                break;
                }
                if($stock->quantity_left < $order->quantity_left){
                    $order->quantity_left -=  $stock->quantity_left;
                    $euros += $stock->quantity_left * $stock->price;                   
                    $stock->quantity_left = 0;
                    $stock->save(); 
                }
            }
        }

    

        if(isset($_POST['sell'])){
            $order->action = "sell";
        }else{
            $order->action = "buy";
        }
        $order->save();
        // dd($euros);
        $EUR = UserStock::where('user_id', '=', Auth::user()->id)->where('stock_id','=',3)->first();
        $EUR->quantity += $euros;
        $EUR->save();


        return redirect()->route('stock.index')->with('success_message', 'Order is active.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
}
