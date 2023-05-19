<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\CustomerStoreRequest;
use App\Http\Requests\CustomerUpdateRequest;
use App\Models\CustomerAddress;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        try {
            //code...
            $lists = Customer::find(auth()->guard('api')->user())->with('customer_address')->get();
            Log::info('The Customer: ', ['customer' => auth()->guard('api')->user()]);
            return response()->json([
                'success' => true,
                'message' => __('validation.success_json'),
                'data'    => $lists
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('Customer error: ', ['customer' => $th->getMessage()]);
            return response()->json([
                'success' => false,
                'message'   => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerStoreRequest $request)
    {
        //
        try {
            //code...
            $cust = Customer::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            CustomerAddress::create([
                'customer_id' => $cust->id,
                'address' => $request->address
            ]);

            Log::info('Store Customer: ', ['customer' => [
                'name' => $request->name,
                'email' => $request->email,
            ]]);

            return response()->json([
                'success' => true,
                'message' => __('validation.success_json'),
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('Store Customer error: ', ['customer' => $th->getMessage()]);
            return response()->json([
                'success' => false,
                'message'   => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        //
        try {
            //code...
            Log::info('The Customer: ', ['customer' => $customer]);
            return response()->json([
                'success' => true,
                'message' => __('validation.success_json'),
                'data'    => $customer->with('customer_address')->get()
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('Customer error: ', ['customer' => $th->getMessage()]);
            return response()->json([
                'success' => false,
                'message'   => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerUpdateRequest $request, Customer $customer)
    {
        //
        try {
            //code...
            $customer->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => !empty($request->password) ? Hash::make($request->password) : $customer->password,
            ]);

            CustomerAddress::updateOrCreate(
                ['customer_id' => $customer->id],
                ['address' => $request->address]
            );

            Log::info('Update Customer: ', ['customer' => [
                'name' => $request->name,
                'email' => $request->email,
            ]]);

            return response()->json([
                'success' => true,
                'message' => __('validation.success_json'),
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('Update Customer error: ', ['customer' => $th->getMessage()]);
            return response()->json([
                'success' => false,
                'message'   => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        //
        try {
            //code...
            CustomerAddress::where('customer_id', $customer->id)->delete();
            $customer->delete();
            $removeToken = auth()->guard('api')->invalidate(auth()->guard('api')->getToken());
            if ($removeToken) {
                Log::info('Delete and Logout Customer: ', ['customer' => $customer]);
                return response()->json([
                    'status' => true,
                    'message' => __('validation.success_json'),
                ], 204);
            }
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('Delete and Logout Customer error: ', ['customer' => $th->getMessage()]);
            return response()->json([
                'success' => false,
                'message'   => $th->getMessage()
            ], 500);
        }
    }
}
