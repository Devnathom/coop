<?php

namespace App\Http\Controllers;

use App\Models\FiscalYear;
use App\Models\Member;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Dividend;
use App\Models\PatronageRefund;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FiscalYearController extends Controller
{
    public function index()
    {
        $fiscalYears = FiscalYear::latest()->paginate(10);
        return view('fiscal-years.index', compact('fiscalYears'));
    }

    public function create()
    {
        return view('fiscal-years.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        FiscalYear::create($validated);

        return redirect()->route('fiscal-years.index')
            ->with('success', 'สร้างปีบัญชีเรียบร้อยแล้ว');
    }

    public function show(FiscalYear $fiscalYear)
    {
        $fiscalYear->load(['dividends.member', 'patronageRefunds.member']);
        return view('fiscal-years.show', compact('fiscalYear'));
    }

    public function calculate(FiscalYear $fiscalYear)
    {
        if ($fiscalYear->status !== 'open') {
            return back()->with('error', 'ปีบัญชีนี้ไม่สามารถคำนวณได้');
        }

        $totalSales = Sale::whereBetween('created_at', [$fiscalYear->start_date, $fiscalYear->end_date])
            ->sum('total');

        $totalCost = SaleItem::whereHas('sale', function($q) use ($fiscalYear) {
            $q->whereBetween('created_at', [$fiscalYear->start_date, $fiscalYear->end_date]);
        })->selectRaw('SUM(cost_price * quantity) as total')->value('total') ?? 0;

        $totalProfit = $totalSales - $totalCost;

        $fiscalYear->update([
            'total_sales' => $totalSales,
            'total_profit' => $totalProfit,
            'status' => 'closed',
        ]);

        return back()->with('success', 'คำนวณยอดขายและกำไรเรียบร้อยแล้ว');
    }

    public function calculateDividends(Request $request, FiscalYear $fiscalYear)
    {
        $validated = $request->validate([
            'dividend_rate' => 'required|numeric|min:0|max:100',
            'patronage_refund_rate' => 'required|numeric|min:0|max:100',
        ]);

        if ($fiscalYear->status === 'calculated') {
            return back()->with('error', 'ปีบัญชีนี้คำนวณปันผลแล้ว');
        }

        DB::transaction(function() use ($validated, $fiscalYear) {
            $fiscalYear->dividends()->delete();
            $fiscalYear->patronageRefunds()->delete();

            $members = Member::where('is_active', true)->get();

            foreach ($members as $member) {
                if ($member->share_amount > 0) {
                    $dividendAmount = ($member->share_amount * $validated['dividend_rate']) / 100;
                    Dividend::create([
                        'fiscal_year_id' => $fiscalYear->id,
                        'member_id' => $member->id,
                        'share_amount' => $member->share_amount,
                        'dividend_rate' => $validated['dividend_rate'],
                        'dividend_amount' => $dividendAmount,
                    ]);
                }

                $purchaseAmount = Sale::where('member_id', $member->id)
                    ->whereBetween('created_at', [$fiscalYear->start_date, $fiscalYear->end_date])
                    ->sum('total');

                if ($purchaseAmount > 0) {
                    $refundAmount = ($purchaseAmount * $validated['patronage_refund_rate']) / 100;
                    PatronageRefund::create([
                        'fiscal_year_id' => $fiscalYear->id,
                        'member_id' => $member->id,
                        'purchase_amount' => $purchaseAmount,
                        'refund_rate' => $validated['patronage_refund_rate'],
                        'refund_amount' => $refundAmount,
                    ]);
                }
            }

            $fiscalYear->update([
                'dividend_rate' => $validated['dividend_rate'],
                'patronage_refund_rate' => $validated['patronage_refund_rate'],
                'status' => 'calculated',
            ]);
        });

        return back()->with('success', 'คำนวณปันผลและเฉลี่ยคืนเรียบร้อยแล้ว');
    }

    public function payDividend(Request $request, Dividend $dividend)
    {
        $dividend->update([
            'is_paid' => true,
            'paid_date' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'จ่ายปันผลเรียบร้อยแล้ว']);
    }

    public function payPatronageRefund(Request $request, PatronageRefund $patronageRefund)
    {
        $patronageRefund->update([
            'is_paid' => true,
            'paid_date' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'จ่ายเฉลี่ยคืนเรียบร้อยแล้ว']);
    }
}
