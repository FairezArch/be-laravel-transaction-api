<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        try {
            //code...
            $lists = Product::paginate(20);
            Log::info('The Product: ', ['product' => $lists]);
            return response()->json([
                'success' => true,
                'message' => __('validation.success_json'),
                'data'    => $lists
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('Product error: ', ['product' => $th->getMessage()]);
            return response()->json([
                'success' => false,
                'message'   => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
