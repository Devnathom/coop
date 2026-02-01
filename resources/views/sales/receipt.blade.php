<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ใบเสร็จรับเงิน - {{ $sale->invoice_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Sarabun', sans-serif; font-size: 12px; width: 80mm; margin: 0 auto; padding: 10px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .mb-2 { margin-bottom: 8px; }
        .mb-3 { margin-bottom: 12px; }
        .font-bold { font-weight: bold; }
        .border-top { border-top: 1px dashed #000; padding-top: 8px; }
        .border-bottom { border-bottom: 1px dashed #000; padding-bottom: 8px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 4px 0; vertical-align: top; }
        .header { font-size: 16px; font-weight: bold; }
        .small { font-size: 10px; }
        @media print {
            body { width: 80mm; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="text-center mb-3">
        <div class="header">สหกรณ์ร้านค้าโรงเรียน</div>
        <div class="small">School Cooperative Store</div>
        <div class="small">โทร. 02-XXX-XXXX</div>
    </div>

    <div class="border-bottom mb-2">
        <table>
            <tr>
                <td>เลขที่:</td>
                <td class="text-right font-bold">{{ $sale->invoice_number }}</td>
            </tr>
            <tr>
                <td>วันที่:</td>
                <td class="text-right">{{ $sale->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <td>พนักงาน:</td>
                <td class="text-right">{{ $sale->user->name }}</td>
            </tr>
            @if($sale->member)
            <tr>
                <td>สมาชิก:</td>
                <td class="text-right">{{ $sale->member->name }}</td>
            </tr>
            @endif
        </table>
    </div>

    <div class="mb-2">
        <table>
            <thead>
                <tr class="border-bottom">
                    <th style="width: 50%">รายการ</th>
                    <th class="text-right">จำนวน</th>
                    <th class="text-right">รวม</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td class="text-right">{{ $item->quantity }}x{{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-right">{{ number_format($item->subtotal, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="border-top mb-2">
        <table>
            <tr>
                <td>รวมเป็นเงิน</td>
                <td class="text-right">{{ number_format($sale->subtotal, 2) }}</td>
            </tr>
            @if($sale->discount > 0)
            <tr>
                <td>ส่วนลด</td>
                <td class="text-right">-{{ number_format($sale->discount, 2) }}</td>
            </tr>
            @endif
            <tr class="font-bold" style="font-size: 14px;">
                <td>ยอดสุทธิ</td>
                <td class="text-right">{{ number_format($sale->total, 2) }} บาท</td>
            </tr>
            <tr>
                <td>รับเงิน</td>
                <td class="text-right">{{ number_format($sale->paid_amount, 2) }}</td>
            </tr>
            <tr>
                <td>เงินทอน</td>
                <td class="text-right">{{ number_format($sale->change_amount, 2) }}</td>
            </tr>
            <tr>
                <td>ชำระโดย</td>
                <td class="text-right">
                    @if($sale->payment_method == 'cash') เงินสด
                    @elseif($sale->payment_method == 'transfer') โอนเงิน
                    @else เครดิต @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="text-center border-top small">
        <p class="mb-2">ขอบคุณที่ใช้บริการ</p>
        <p>Thank you for your purchase</p>
    </div>

    <div class="text-center no-print" style="margin-top: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 14px; cursor: pointer;">
            🖨️ พิมพ์ใบเสร็จ
        </button>
    </div>
</body>
</html>
