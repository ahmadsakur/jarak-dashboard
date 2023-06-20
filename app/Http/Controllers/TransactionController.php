<?php

namespace App\Http\Controllers;

use App\Events\OrderCreated;
use App\Models\Transaction;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    //

    public function index()
    {
        // get today transactions
        $transactions = Transaction::whereDate('created_at', today())->get()->sortBy('created_at');
        return view('pages.transactions', compact('transactions'));
    }

    public function getWeeklyTransaction()
    {
        // get weekly transactions
        $transactions = Transaction::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->get()->sortBy('created_at');
        return view('pages.transactions', compact('transactions'));
    }

    public function getMonthlyTransaction()
    {
        // get monthly transactions
        $transactions = Transaction::whereMonth('created_at', today()->month)->get()->sortBy('created_at');
        return view('pages.transactions', compact('transactions'));
    }

    public function getAllTransactions()
    {
        // get all transactions
        $transactions = Transaction::orderBy('created_at', 'desc')->get();
        return view('pages.transactions', compact('transactions'));
    }

    public function create(Request $request)
    {
        // validate data
        $validator = Validator::make($request->all(), [
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|string',
            'items.*.name' => 'required|string',
            'items.*.imageUrl' => 'required|string|url',
            'items.*.variant_id' => 'required|string',
            'items.*.variant_name' => 'required|string',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1',
            // Order Form Validation
            'orderForm.name' => 'required|string',
            'orderForm.whatsappNumber' => 'required|string',
            'orderForm.tableNumber' => 'required|string',
            'orderForm.notes' => 'nullable|string',
            // Payment Method Validation
            'paymentMethod.group' => 'required|string',
            'paymentMethod.code' => 'required|string',
            'paymentMethod.name' => 'required|string',
            'paymentMethod.type' => 'required|string',
            'paymentMethod.fee_merchant.flat' => 'required|numeric|min:0',
            'paymentMethod.fee_merchant.percent' => 'required|numeric|min:0|max:100',
            'paymentMethod.fee_customer.flat' => 'required|numeric|min:0',
            'paymentMethod.fee_customer.percent' => 'required|numeric|min:0|max:100',
            'paymentMethod.total_fee.flat' => 'required|numeric|min:0',
            'paymentMethod.total_fee.percent' => 'required|string|min:0|max:100',
            'paymentMethod.minimum_fee' => 'nullable|numeric|min:0',
            'paymentMethod.maximum_fee' => 'nullable|numeric|min:0',
            'paymentMethod.icon_url' => 'nullable|string|url',
            'paymentMethod.active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors(),
            ], 422);
        }



        // get payment method
        $method_code = $request->paymentMethod['code'];
        $method_name = $request->paymentMethod['name'];

        // get products
        $products = [];
        $totalPrice = 0;
        foreach ($request->items as $product) {
            $item = $this->getVariantDetails($product['variant_id']);
            $totalPrice += $item["price"] * $product['quantity'];
            $products[] = [
                // "sku" => $item["id"],
                "sku" => null,
                "name" => $item["product"]["name"] . " (" . $item["variant_name"] . ")",
                "price" => $item["price"],
                "quantity" => $product['quantity'],
                "subtotal" => $item["price"] * $product['quantity'],
                "image_url" => $item["product"]["imageUrl"],
            ];
        }


        // get user data
        $user = $request->orderForm;


        // Send Request to Tripay
        $tripay = new TripayController();
        $res = $tripay->createPaymentRequest($method_code, $totalPrice, $products, $user);

        // send event
        OrderCreated::dispatch('Order Created');


        $returnedData = $res;
        $data = json_decode($returnedData->getContent(), true);


        // Create Transaction
        Transaction::create([
            "transaction_id" => $data["data"]["data"]["reference"],
            "customer_name" => $user["name"],
            "customer_phone" => $user["whatsappNumber"],
            "table_number" => $user["tableNumber"],
            "notes" => $user["notes"],
            "total_price" => $totalPrice,
            "payment_method" => $method_name,
            "payment_status" => "UNPAID",
            "transaction_status" => "INITIAL",
        ]);

        // send to FE
        return $res;
    }

    public function getOrderItems($id)
    {
        // get order items from Tripay
        $tripayController = new TripayController();
        $res = $tripayController->getOrderDetails($id);
        $items = $res->data->order_items;

        // get order status from DB
        $transaction = Transaction::where('transaction_id', $id)->first();
        $status = $transaction->transaction_status;
        $notes = $transaction->notes;

        // append order status as a siblings of order items
        $response = [
            "items" => $items,
            "status" => $status,
            "notes" => $notes
        ];

        return $response;
    }

    public function getOrderStatus($invoice)
    {
        // get Transaction status field as json, return status 200 if success, and 500 if failed
        $transaction = Transaction::where('transaction_id', $invoice)->first();

        // return a json response based on if the transaction is found or not
        if ($transaction == null)
            return response()->json([
                'status' => 500,
                'message' => 'Transaction not found',
                'data'   => null
            ]);

        return response()->json([
            'status' => 200,
            'message' => 'success',
            'data'   => $transaction->transaction_status
        ]);
    }

    public function updateOrderStatus(Request $request, $id)
    {
        // validate data
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:INITIAL,CONFIRMED,PROCESSED,COMPLETED,CANCELLED',
        ]);

        if ($validator->fails()) {
            return redirect('/transactions')->with('toast_error', 'Invalid Data');
        }

        // update transaction status
        Transaction::where('transaction_id', $request["transaction_id"])->update([
            "transaction_status" => $request["status"],
        ]);
        return redirect('/transactions')->with('toast_success', 'Stat7us Updated Successfully');
    }

    public function getVariantDetails($id)
    {
        $variant = Variant::getVariantWithProduct($id);
        return $variant;
    }
}
