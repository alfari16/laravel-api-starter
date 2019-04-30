<?php

namespace App\Http\Controllers\Api;

use Validator;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;

class ProductController extends Controller
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
        $products = DB::table('products');
        if ($request->query()){
            foreach ($request->query() as $key => $value) {
                if (Schema::hasColumn('products', $key)){
                    $products = $products->where($key, 'like', "%{$value}%");
                }
            }
        }
        return response()->json([
            'result' => $products->get(),
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
            'product_name' => 'required',
            'price' => 'required|integer',
            'stock' => 'required|integer'
        ]);

        if ($validator->fails()){
            return response()->json([
                'message' =>  $validator->errors(),
                'status_code' => 400
            ], 400);
        }

        $transaction = [
            'product_name' => $request->product_name,
            'price' => $request->price,
            'stock' => $request->stock,
        ];
        Product::create($transaction);

        return response()->json([
            'result' => $transaction,
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
        $product = Product::findOrFail($id);  
        return response()->json($product, 200);
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
        $product = Product::findOrFail($id);
        $validator = Validator::make($request->all(),[ 
            'product_name' => 'required',
            'price' => 'required|integer',
            'stock' => 'required|integer'
        ]);

        if ($validator->fails()){
            return response()->json([
                'message' =>  $validator->errors(),
                'status_code' => 400
            ], 400);
        }

        $newProduct = [
            'product_name' => $request->product_name,
            'price' => $request->price,
            'stock' => $request->stock,
        ];
        $product->update($newProduct);
       
        return response()->json([
            'result' => $newProduct,
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
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json([
            'result' => $product,
            'status_code' => 200,
        ], 200);
    }
}
