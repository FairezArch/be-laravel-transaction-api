<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Models\TransactionDetail;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Models\TransactionPaymentMethod;

class TransactionController extends Controller
{
    //

    public function index(): JsonResponse
    {
        try {
            //code...
            $lists = Transaction::with(['customer' => function ($query) {
                $query->where('id', auth()->guard('api')->user()->id)->with('customer_address');
            },'transaction_details', 'transaction_payment_method'])->get();
            Log::info('List Transaction: ', ['transaction' => $lists]);
            return response()->json([
                'success' => true,
                'message' => __('validation.success_json'),
                'data' => $lists,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('List Transaction error: ', ['transaction' => $th->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function store(Request $request): JsonResponse
    {

        try {
            //code...
            $now = Carbon::now()->toDateTimeString();
            $trans = Transaction::create([
                'customer_id' => auth()->guard('api')->user()->id,
                'order_date' => $now
            ]);

            $lastID_transaction = $trans->id;

            $inputProduct = [];
            $total_price = 0;
            foreach($request->products as $key => $value){
                $currentPrice = Product::select('price')->firstWhere('id', $value['id'])->price;
                $inputProduct[$key]['transaction_id'] = $lastID_transaction;
                $inputProduct[$key]['product_id'] = $value['id'];
                $inputProduct[$key]['quantity'] = $value['quantity'];
                $inputProduct[$key]['subtotal'] = $value['quantity'] * $currentPrice;

                $inputProduct[$key]['created_at'] = $now;
                $inputProduct[$key]['updated_at'] = $now;

                $total_price = $total_price + ($value['quantity'] * $currentPrice);
            }

            $inputPayment = [];
            foreach($request->payment_method as $key => $value){
                $inputPayment[$key]['transaction_id'] = $lastID_transaction;
                $inputPayment[$key]['payment_method_id'] = $value['id'];
                $inputPayment[$key]['amount'] = $value['amount'];

                $inputPayment[$key]['created_at'] = $now;
                $inputPayment[$key]['updated_at'] = $now;
            }

            TransactionDetail::insert($inputProduct);

            TransactionPaymentMethod::insert($inputPayment);

            $transaction_price = Transaction::find($lastID_transaction);
            $transaction_price->update(['total_price' => $total_price]);

            $info = Transaction::with(['customer' => function ($query) {
                $query->where('id', auth()->guard('api')->user()->id)->with('customer_address');
            },'transaction_details', 'transaction_payment_method'])->get();

            Log::info('Store Transaction: ', ['transaction' => $info]);

            return response()->json([
                'success' => true,
                'message' => __('validation.success_json'),
                'data' => $info
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('Store Transaction error: ', ['transaction' => $th->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}
