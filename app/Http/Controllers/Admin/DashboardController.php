<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->authorize('isAdmin');
    }
  
    public function index()
    {
        // Tanggal sekarang dan bulan lalu
        $currentMonthStart = Carbon::now()->startOfMonth();
        $currentMonthEnd = Carbon::now()->endOfMonth();
        $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        // Query untuk bulan ini
        $currentMonthOrdersTotalAmount = Order::whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->sum('amount');

        $currentMonthOrdersTotalTransaction = Transaction::whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->count();

        $currentMonthOrdersTotalProducts = OrderItem::whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->count();


        // Query untuk bulan lalu
        $lastMonthOrdersTotalAmount = Order::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
            ->sum('amount');

        $lastMonthOrdersTotalTransaction = Transaction::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
            ->count();

        $lastMonthOrdersTotalProducts = OrderItem::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
            ->count();

        // Hitung persentase perubahan
        $revenuePercentage = $this->calculatePercentage(
            $currentMonthOrdersTotalAmount ?? 0,
            $lastMonthOrdersTotalAmount ?? 0
        );

        $transactionsPercentage = $this->calculatePercentage(
            $currentMonthOrdersTotalTransaction ?? 0,
            $lastMonthOrdersTotalTransaction ?? 0
        );

        $productsPercentage = $this->calculatePercentage(
            $currentMonthOrdersTotalProducts ?? 0,
            $lastMonthOrdersTotalProducts ?? 0
        );

        // Chart
        $now = Carbon::now();
        $sixMonthsAgo = Carbon::now()->subMonths(5)->startOfMonth();

        // Chart revenue
        // Get monthly sales data for last 6 months
        // dd('here');
        $monthlyRevenue = Order::selectRaw('
                MONTH(created_at) as month,
                YEAR(created_at) as year,
                SUM(amount) as total_amount
            ')
            ->whereBetween('created_at', [$sixMonthsAgo, $now])
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Generate labels for last 6 months
        $chart_revenue_labels = [];
        $chart_revenue_data = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $chart_revenue_labels[] = $date->translatedFormat('M');

            // Find matching data or set to 0
            $found = $monthlyRevenue->first(function ($item) use ($date) {
                return $item->month == $date->month && $item->year == $date->year;
            });

            $chart_revenue_data[] = $found ? $found->total_amount : 0;
        }

        // Chart transaction
        // Get monthly sales data for last 6 months
        $monthlyTransaction = Transaction::selectRaw('
                MONTH(created_at) as month,
                YEAR(created_at) as year,
                COUNT(*) as total_transaction
            ')
            ->whereBetween('created_at', [$sixMonthsAgo, $now])
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Generate labels for last 6 months
        $chart_transaction_labels = [];
        $chart_transaction_data = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $chart_transaction_labels[] = $date->translatedFormat('M');

            // Find matching data or set to 0
            $found = $monthlyTransaction->first(function ($item) use ($date) {
                return $item->month == $date->month && $item->year == $date->year;
            });

            $chart_transaction_data[] = $found ? $found->total_transaction : 0;
        }

        // Chart product sold
        // Get monthly sales data for last 6 months
        $monthlyProductSold = OrderItem::selectRaw('
                MONTH(created_at) as month,
                YEAR(created_at) as year,
                COUNT(*) as total_product_sold
            ')
            ->whereBetween('created_at', [$sixMonthsAgo, $now])
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Generate labels for last 6 months
        $chart_product_sold_labels = [];
        $chart_product_sold_data = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $chart_product_sold_labels[] = $date->translatedFormat('M');

            // Find matching data or set to 0
            $found = $monthlyProductSold->first(function ($item) use ($date) {
                return $item->month == $date->month && $item->year == $date->year;
            });

            $chart_product_sold_data[] = $found ? $found->total_product_sold : 0;
        }

        return view('admin.dashboard', [
            'title' => 'Dashboard',
            'total_revenue' => [
                'value' => $currentMonthOrdersTotalAmount ?? 0,
                'percentage' => $revenuePercentage,
                'change' => $revenuePercentage >= 0 ? 'increase' : 'decrease'
            ],
            'total_transactions' => [
                'value' => $currentMonthOrdersTotalTransaction ?? 0,
                'percentage' => $transactionsPercentage,
                'change' => $transactionsPercentage >= 0 ? 'increase' : 'decrease'
            ],
            'product_sold' => [
                'value' => $currentMonthOrdersTotalProducts ?? 0,
                'percentage' => $productsPercentage,
                'change' => $productsPercentage >= 0 ? 'increase' : 'decrease'
            ],
            'charts' => [
                'revenue' => [
                    'labels' => $chart_revenue_labels,
                    'data' => $chart_revenue_data
                ],
                'transaction' => [
                    'labels' => $chart_transaction_labels,
                    'data' => $chart_transaction_data
                ],
                'product_sold' => [
                    'labels' => $chart_product_sold_labels,
                    'data' => $chart_product_sold_data
                ],
            ]
            ]);
    }

    private function calculatePercentage($current, $previous)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }

        return round((($current - $previous) / $previous) * 100, 2);
    }
}