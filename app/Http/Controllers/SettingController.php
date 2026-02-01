<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'store_name' => Setting::get('store_name', 'สหกรณ์ร้านค้าโรงเรียน'),
            'store_address' => Setting::get('store_address', ''),
            'store_phone' => Setting::get('store_phone', ''),
            'store_email' => Setting::get('store_email', ''),
            'store_logo' => Setting::get('store_logo', ''),
            'tax_id' => Setting::get('tax_id', ''),
            'receipt_header' => Setting::get('receipt_header', ''),
            'receipt_footer' => Setting::get('receipt_footer', 'ขอบคุณที่ใช้บริการ'),
            'currency_symbol' => Setting::get('currency_symbol', '฿'),
            'low_stock_alert' => Setting::get('low_stock_alert', '10'),
            'share_value' => Setting::get('share_value', '10'),
            'min_shares' => Setting::get('min_shares', '10'),
            'max_shares' => Setting::get('max_shares', '1000'),
            'dividend_calculation' => Setting::get('dividend_calculation', 'share_based'),
            'fiscal_year_start' => Setting::get('fiscal_year_start', '01-01'),
            'backup_enabled' => Setting::get('backup_enabled', '0'),
            'backup_frequency' => Setting::get('backup_frequency', 'daily'),
        ];

        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'store_name' => 'required|string|max:255',
            'store_address' => 'nullable|string|max:500',
            'store_phone' => 'nullable|string|max:20',
            'store_email' => 'nullable|email|max:255',
            'store_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tax_id' => 'nullable|string|max:20',
            'receipt_header' => 'nullable|string|max:500',
            'receipt_footer' => 'nullable|string|max:500',
            'currency_symbol' => 'required|string|max:5',
            'low_stock_alert' => 'required|integer|min:1',
            'share_value' => 'required|numeric|min:1',
            'min_shares' => 'required|integer|min:1',
            'max_shares' => 'required|integer|min:1',
            'dividend_calculation' => 'required|in:share_based,purchase_based,mixed',
            'fiscal_year_start' => 'required|string',
            'backup_enabled' => 'nullable|boolean',
            'backup_frequency' => 'required|in:daily,weekly,monthly',
        ]);

        // Handle logo upload
        if ($request->hasFile('store_logo')) {
            $oldLogo = Setting::get('store_logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }
            $logoPath = $request->file('store_logo')->store('logos', 'public');
            Setting::set('store_logo', $logoPath, 'general', 'image');
        }

        // Save general settings
        Setting::set('store_name', $request->store_name, 'general', 'text');
        Setting::set('store_address', $request->store_address, 'general', 'textarea');
        Setting::set('store_phone', $request->store_phone, 'general', 'text');
        Setting::set('store_email', $request->store_email, 'general', 'email');
        Setting::set('tax_id', $request->tax_id, 'general', 'text');

        // Save receipt settings
        Setting::set('receipt_header', $request->receipt_header, 'receipt', 'textarea');
        Setting::set('receipt_footer', $request->receipt_footer, 'receipt', 'textarea');
        Setting::set('currency_symbol', $request->currency_symbol, 'receipt', 'text');

        // Save stock settings
        Setting::set('low_stock_alert', $request->low_stock_alert, 'stock', 'number');

        // Save member/share settings
        Setting::set('share_value', $request->share_value, 'member', 'number');
        Setting::set('min_shares', $request->min_shares, 'member', 'number');
        Setting::set('max_shares', $request->max_shares, 'member', 'number');
        Setting::set('dividend_calculation', $request->dividend_calculation, 'member', 'select');
        Setting::set('fiscal_year_start', $request->fiscal_year_start, 'member', 'text');

        // Save backup settings
        Setting::set('backup_enabled', $request->backup_enabled ? '1' : '0', 'backup', 'boolean');
        Setting::set('backup_frequency', $request->backup_frequency, 'backup', 'select');

        Setting::clearCache();

        return redirect()->route('settings.index')->with('success', 'บันทึกการตั้งค่าเรียบร้อยแล้ว');
    }

    public function removeLogo()
    {
        $logo = Setting::get('store_logo');
        if ($logo && Storage::disk('public')->exists($logo)) {
            Storage::disk('public')->delete($logo);
        }
        Setting::set('store_logo', '', 'general', 'image');
        Setting::clearCache();

        return response()->json(['success' => true]);
    }
}
