<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $transactions = Transaction::select('created_at')
            ->where('created_at', '>=', Carbon::now()->subDays(7)->toDateString())
            ->orderBy('created_at')
            ->get();

        // Calculate the daily transaction totals
        $salesData = [];
        foreach ($transactions as $transaction) {
            $date = $transaction->created_at->format('Y-m-d');
            if (!array_key_exists($date, $salesData)) {
                $salesData[$date] = 0;
            }
            $salesData[$date] += 1;
        }

        // Map the sales data to a JavaScript array variable in the view
        $chartData = collect($salesData)->map(function ($total, $date) {
            return ['date' => Carbon::parse($date)->format('M d'), 'total' => $total];
        })->values();

        return view('pages.dashboard', compact('chartData'));
    }
}
