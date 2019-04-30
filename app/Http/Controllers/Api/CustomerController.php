<?php

namespace App\Http\Controllers\Api;

use Validator;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;

class CustomerController extends Controller
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
        $customers = DB::table('customers');
        if ($request->query()){
            foreach ($request->query() as $key => $value) {
                if (Schema::hasColumn('customers', $key)){
                    $customers = $customers->where($key, 'like', "%{$value}%");
                }
            }
        }
        return response()->json([
            'result' => $customers->get(),
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
            'name' => 'required',
            'email' => 'required|unique:customers',
            'gender' => 'required',
            'phone' => 'required'
        ]);

        if ($validator->fails()){
            return response()->json([
                'message' =>  $validator->errors(),
                'status_code' => 400
            ], 400);
        }

        $customer = [
            'name' => $request->name,
            'email' => $request->email,
            'gender' => $request->gender,
            'phone' => $request->phone
        ];
        Customer::create($customer);

        return response()->json([
            'result' => $customer,
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
        $customer = Customer::findOrFail($id);  
        return response()->json($customer, 200);
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
        $customer = Customer::findOrFail($id);
        $validator = Validator::make($request->all(),[ 
            'name' => 'required',
            'email' => 'required|unique:customers',
            'gender' => 'required',
            'phone' => 'required'
        ]);

        if ($validator->fails()){
            return response()->json([
                'message' =>  $validator->errors(),
                'status_code' => 400
            ], 400);
        }

        $newCustomer = [
            'name' => $request->name,
            'email' => $request->email,
            'gender' => $request->gender,
            'phone' => $request->phone
        ];
        $customer->update($newCustomer);

        return response()->json([
            'result' => $newCustomer,
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
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return response()->json([
            'result' => $customer,
            'status_code' => 200,
        ], 200);
    }
}
