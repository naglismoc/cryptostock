<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Stock;
use App\Models\UserStock;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Auth;
use DB;
use Response;
class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      
    }

/*


*/
    public function data()
    {
        $orders = Transaction::where('stock_id','=',$_GET['stock_id']);
        $data = [ ['Year', 'Sales', 'Expenses'] ];
        foreach ($orders as  $order) {
            $data[] = ['yes',$order->price, $order->quantity];
        }

        return Response::json(
            [
                'status' =>'200',
                'orders' => json_encode($data)
            ]
           
        
        );
    }


    public function dataStepped()
    {
   
        $orders = Order::where('action','=','buy')
        ->where('stock_id','=',$_GET['stock_id'])
        ->where('quantity_left','>','0')
        ->orderBy('price')
        ->get();
        $data = [ ['Year', 'sell', 'buy'] ];
        foreach ($orders as  $order) {
            $data[] = [$order->price,$order->quantity_left, 0];
        }

        $orders = Order::where('action','=','sell')
        ->where('stock_id','=',$_GET['stock_id'])
        ->where('quantity_left','>','0')
        ->orderBy('price')
        ->get();

        foreach ($orders as  $order) {
            $data[] = [$order->price,0, $order->quantity_left];
        }

        return Response::json(
            [
                'status' =>'200',
                'orders' => json_encode($data)
            ]
        );
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


        $euro = Stock::where('nickname','=','EUR')->first();
        $order = new Order();
        $order->user_id = Auth::user()->id;
        $order->stock_id = $stock->id;
        $order->status = 0;
        $order->price =  $request->price;
        $order->currency = $euro->id;
        $order->action = (isset($_POST['buy'])) ? "buy":"sell";
        $order->quantity = $request->quantity;
        $order->quantity_left = $request->quantity;
        $order->trigger_price = 0;
        $order->save();

        $EUR = UserStock::where('user_id', '=', Auth::user()->id)->where('stock_id','=',$order->currency)->first();

        if(isset($_POST['buy'])){
            $estimatedPrice = $request->price * $request->quantity;
            if($estimatedPrice <= $EUR->quantity){
                $EUR->quantity -= $estimatedPrice;
                $EUR->save();
            }
        }
        $stocks;
        $euros = 0;
        $eurosReturn = 0;

        if(isset($_POST['buy'])){
            $stocks = Order::where('stock_id','=', $stock->id)->
            where('action','=','sell')->
            where('quantity_left','>','0')->
            where('price','<=',$request->price)->orderBy('price','desc')->get();

        }

        if(isset($_POST['sell'])){
            // DB::enableQueryLog();
            $stocks = Order::where('stock_id','=', $stock->id)->
            where('action','=','buy')->
            where('quantity_left','>','0')->
            where('price','>=',$request->price)->orderBy('price','desc')->get();
            // print_r($stocks);
            // dd(DB::getQueryLog());
        }

            foreach ($stocks as $stock) {
               if($stock->quantity_left > $order->quantity_left){
                $t = new Transaction();
                $t->price = $stock->price;
                $t->quantity = $order->quantity_left;
                $t->order_buy = $stock->id;
                $t->order_sell =$order->id;
                $t->save();

                $stock->quantity_left -= $order->quantity_left;
                $euros += $order->quantity_left * $stock->price;

                $eurosReturn = $order->quantity_left * $order->price -  $order->quantity_left * $stock->price;
                
                if(isset($_POST['buy'])){
                    $foreignEUR = UserStock::where('user_id', '=',  $stock->user_id)->where('stock_id','=',$order->currency)->first();
                    // echo $foreignEUR->quantity."<br>";
                    $foreignEUR->quantity += $order->quantity_left * $stock->price;
                    $foreignEUR->save();
                    // echo $order->quantity_left * $stock->price."<br>";
                    // echo $foreignEUR->quantity; die;
                }


                if(isset($_POST['sell'])){
                    $foreignEUR = UserStock::where('user_id', '=',  $stock->user_id)->where('stock_id','=',$order->stock_id)->first();
                    if($foreignEUR == null){
                        $foreignEUR = new UserStock();
                        $foreignEUR->user_id = $stock->user_id;
                        $foreignEUR->stock_id = $stock->stock_id;
                        $foreignEUR->quantity = 0;
                        $foreignEUR->save();

                    };

                    $foreignEUR->quantity += $order->quantity_left;
                    $foreignEUR->save();
                }


                $order->quantity_left = 0;
                $stock->save();
                
               
                break;
               }
               if($stock->quantity_left == $order->quantity_left){
                $t = new Transaction();
                $t->price = $stock->price;
                $t->quantity = $order->quantity_left;
                $t->order_buy = $stock->id;
                $t->order_sell =$order->id;
                $t->save();


                   $euros += $order->quantity_left * $stock->price;
                   $eurosReturn = $order->quantity_left * $order->price -  $order->quantity_left * $stock->price;
                  
                   if(isset($_POST['buy'])){
                        $foreignEUR = UserStock::where('user_id', '=',  $stock->user_id)->where('stock_id','=',$order->currency)->first();
                    
                        $foreignEUR->quantity += $order->quantity_left * $stock->price;
                        $foreignEUR->save();
                    }
                    if(isset($_POST['sell'])){
                        $foreignEUR = UserStock::where('user_id', '=',  $stock->user_id)->where('stock_id','=',$order->stock_id)->first();
                        if($foreignEUR == null){
                            $foreignEUR = new UserStock();
                            $foreignEUR->user_id = $stock->user_id;
                            $foreignEUR->stock_id = $stock->stock_id;
                            $foreignEUR->quantity = 0;
                            $foreignEUR->save();
    
                        };
    
                        $foreignEUR->quantity += $order->quantity_left;
                        $foreignEUR->save();
                    }
                    $stock->save();
                    $stock->quantity_left = 0;
                    $stock->status = 0;
                    $order->quantity_left = 0;
                    break;
                }
                if($stock->quantity_left < $order->quantity_left){
                    $t = new Transaction();
                    $t->price = $stock->price;
                    $t->quantity = $stock->quantity_left;
                    $t->order_buy = $stock->id;
                    $t->order_sell =$order->id;
                    $t->save();

                    $order->quantity_left -=  $stock->quantity_left;
                    $euros += $stock->quantity_left * $stock->price;       
                    $eurosReturn = $order->quantity_left * $order->price -  $order->quantity_left * $stock->price;            
                  
                    if(isset($_POST['buy'])){
                        $foreignEUR = UserStock::where('user_id', '=',  $stock->user_id)->where('stock_id','=',$order->currency)->first();
                        $foreignEUR->quantity += $order->quantity_left * $stock->price;
                        $foreignEUR->save();
                    }
                    if(isset($_POST['sell'])){
                        $foreignEUR = UserStock::where('user_id', '=',  $stock->user_id)->where('stock_id','=',$order->stock_id)->first();
                        if($foreignEUR == null){
                            $foreignEUR = new UserStock();
                            $foreignEUR->user_id = $stock->user_id;
                            $foreignEUR->stock_id = $stock->stock_id;
                            $foreignEUR->quantity = 0;
                            $foreignEUR->save();
    
                        };
    
                        $foreignEUR->quantity += $order->quantity_left;
                        $foreignEUR->save();
                    }
                    $stock->quantity_left = 0;            
                    $stock->status = 0;
                    $stock->save(); 
                }
            
        }

    

        if(isset($_POST['sell'])){
            $order->action = "sell";
        }else{
            $order->action = "buy";
        }

        if($order->quantity_left > 0){
            $order->status = 1;
        }
        $order->save();
        // dd($euros);

    if(isset($_POST['buy'])){
        $EUR->quantity +=  $eurosReturn;       
    }else{
        $EUR->quantity += $euros;
    }
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
