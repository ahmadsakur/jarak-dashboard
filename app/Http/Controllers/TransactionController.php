<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Variant;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    //

    public function index()
    {
        $transactions = Transaction::all();
        return view('pages.transactions', compact('transactions'));
    }

    public function create(Request $request)
    {
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

        $returnedData = $res;
        $data = json_decode($returnedData->getContent(), true);


        // Create Transaction
        Transaction::create([
            "transaction_id" => $data["data"]["data"]["reference"],
            "customer_name" => $user["name"],
            "customer_phone" => $user["whatsappNumber"],
            "table_number" => $user["tableNumber"],
            "total_price" => $totalPrice,
            "payment_method" => $method_name,
            "payment_status" => "UNPAID",
            "transaction_status" => "INITIAL",
        ]);
        // send to FE
        return $res;
    }

    public function getVariantDetails($id)
    {
        $variant = Variant::getVariantWithProduct($id);
        return $variant;
    }
}
