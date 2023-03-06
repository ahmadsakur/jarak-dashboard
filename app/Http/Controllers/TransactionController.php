<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    //

    public function store(Request $request)
    {
        dd($request->all());
        // get payment method
        $method = $request->paymentMethod['code'];

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
        $res = $tripay->createPaymentRequest($method, $totalPrice, $products, $user);

        // //Create Transaction
        // Transaction::create([
        //     "transaction_id" => $res["data"]["payment_code"],
        //     "customer_name" => $user["name"],
        //     "customer_phone" => $user["whatAppNumber"],
        //     "table_number" => $user["tableNumber"],
        //     "total_price" => $totalPrice,
        //     "payment_method" => $method,
        //     "payment_status" => $res["data"]["status"],
        //     "transaction_status" => "INITIAL",
        // ]);

        // send to FE
        return $res;
    }
}
