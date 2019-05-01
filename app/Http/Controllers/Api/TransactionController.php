<?php

namespace App\Http\Controllers\Api;

use Validator;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\TransactionItem;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $transactions = Transaction::with([
            'product' => function($query){
                $query->select('qty', 'price', 'product_name', 
                    DB::raw('qty * price AS sub_total')
                );
            }, 
            'customer'
            ])
            ->select();

        if ($request->query()){
            foreach ($request->query() as $key => $value) {
                if (Schema::hasColumn('transactions', $key)){
                    $transactions = $transactions->where('transactions.'.$key, 'like', "%{$value}%");
                }else if (Schema::hasColumn('products', $key)){
                    $transactions = $transactions->whereHas('product', function ($query) use ($key, $value) {
                        $query->where("products.{$key}", 'like', "%{$value}%");
                    });
                }else if (Schema::hasColumn('customers', $key)){
                    $transactions = $transactions->whereHas('customer', function ($query) use($key, $value) {
                        $query->where("customers.{$key}", 'like', "%{$value}%");
                    });
                }else {
                    return response()->json([
                        'message' => 'Field not found',
                        'status_code' => 404
                    ], 404);
                }
            }
        }
        $result = $transactions->get()->each(function ($tran, $key) {
            $tran->total = $tran->product->sum('sub_total');
        });  
        
              
        return response()->json([
            'result' => $result,
            'status_code' => 200,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[ 
            'customer_id' => 'required',
            
        ]);

        if ($validator->fails()){
            return response()->json([
                'message' =>  $validator->errors(),
                'status_code' => 400
            ], 400);
        }

        $transaction = [
            'customer_id' => $request->customer_id,
        ];
        $transaction = Transaction::create($transaction);

        $rows = $request->products;
        foreach ($rows as $row => $key)
        {
            $transactionItems[] = [
                'product_id' => $key['product_id'],
                'qty' => $key['qty'],
                'transaction_id' => $transaction->id,
                
            ];
        }

        TransactionItem::insert($transactionItems);
        $lastTransaction = Transaction::with([
                'transactionItem.product' => function($query){
                    $query->select('id', 'product_name', 'price', 'stock');
                },
                'customer' => function($query){
                    $query->select('id', 'name', 'email', 'gender', 'phone');
                }
            ])
            ->orderBy('id', 'desc')
            ->first();

        return response()->json([
            'result' => $lastTransaction,
            'status_code' => 200,
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $transaction = Transaction::with([
                'product' => function($query){
                    $query->select('id', 'product_name', 'price', 'stock');
                },
                'customer' => function($query){
                    $query->select('id', 'name', 'email', 'gender', 'phone');
                }
            ])->findOrFail($id);  
        return response()->json($transaction, 200);
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
        
        $transaction = Transaction::findOrFail($id);
        $validator = Validator::make($request->all(),[ 
            'customer_id' => 'required',
            'products' => 'required'
        ]);

        if ($validator->fails()){
            return response()->json([
                'message' =>  $validator->errors(),
                'status_code' => 400
            ], 400);
        }
        $newTransaction = [
            'customer_id' => $request->customer_id,
        ];
        $transaction->update($newTransaction);
        
        $transactionItem = TransactionItem::where('transaction_id', $id)->delete();
        
        $rows = $request->products;
        
        foreach ($rows as $row => $key)
        {
            $transactionItems[] = [
                'product_id' => $key['product_id'],
                'qty' => $key['qty'],
                'transaction_id' => $id
            ];
        }

        TransactionItem::insert($transactionItems);
        
        $lastTransaction = Transaction::with([
                'transactionItem.product' => function($query){
                    $query->select('id', 'product_name', 'price', 'stock');
                },
                'customer' => function($query){
                    $query->select('id', 'name', 'email', 'gender', 'phone');
                }
            ])
            ->orderBy('id', 'desc')
            ->first();

        return response()->json([
            'result' => $lastTransaction,
            'status_code' => 200,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);
        $lastTransaction = Transaction::with([
            'transactionItem.product' => function($query){
                $query->select('id', 'product_name', 'price', 'stock');
            },
            'customer' => function($query){
                $query->select('id', 'name', 'email', 'gender', 'phone');
            }
        ])
            ->where('transactions.id', $id)
            ->first();
        $transaction->delete();

        return response()->json([
            'result' => $lastTransaction,
            'status_code' => 200,
        ], 200);
    }
}
