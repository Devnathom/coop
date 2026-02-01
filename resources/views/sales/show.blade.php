@extends('adminlte::page')

@section('title', 'รายละเอียดการขาย')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>รายละเอียดการขาย: {{ $sale->invoice_number }}</h1>
        <a href="{{ route('sales.receipt', $sale) }}" class="btn btn-secondary" target="_blank">
            <i class="fas fa-print mr-1"></i> พิมพ์ใบเสร็จ
        </a>
    </div>
@stop

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-list mr-2"></i>รายการสินค้า</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>สินค้า</th>
                            <th>ราคา/หน่วย</th>
                            <th>จำนวน</th>
                            <th>รวม</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sale->items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <strong>{{ $item->product->name }}</strong>
                                <br><small class="text-muted">{{ $item->product->code }}</small>
                            </td>
                            <td>฿{{ number_format($item->unit_price, 2) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td class="text-success">฿{{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4" class="text-right">รวมเป็นเงิน</th>
                            <th>฿{{ number_format($sale->subtotal, 2) }}</th>
                        </tr>
                        <tr>
                            <th colspan="4" class="text-right">ส่วนลด</th>
                            <th class="text-danger">-฿{{ number_format($sale->discount, 2) }}</th>
                        </tr>
                        <tr class="table-success">
                            <th colspan="4" class="text-right h5">ยอดสุทธิ</th>
                            <th class="h5">฿{{ number_format($sale->total, 2) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-info">
                <h3 class="card-title"><i class="fas fa-info-circle mr-2"></i>ข้อมูลการขาย</h3>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-5">เลขที่ใบเสร็จ</dt>
                    <dd class="col-7">{{ $sale->invoice_number }}</dd>
                    <dt class="col-5">วันที่</dt>
                    <dd class="col-7">{{ $sale->created_at->format('d/m/Y H:i') }}</dd>
                    <dt class="col-5">สมาชิก</dt>
                    <dd class="col-7">{{ $sale->member->name ?? 'ลูกค้าทั่วไป' }}</dd>
                    <dt class="col-5">ผู้ขาย</dt>
                    <dd class="col-7">{{ $sale->user->name }}</dd>
                    <dt class="col-5">วิธีชำระเงิน</dt>
                    <dd class="col-7">
                        @if($sale->payment_method == 'cash')
                            <span class="badge badge-success">เงินสด</span>
                        @elseif($sale->payment_method == 'transfer')
                            <span class="badge badge-info">โอนเงิน</span>
                        @else
                            <span class="badge badge-warning">เครดิต</span>
                        @endif
                    </dd>
                    <dt class="col-5">รับเงิน</dt>
                    <dd class="col-7">฿{{ number_format($sale->paid_amount, 2) }}</dd>
                    <dt class="col-5">เงินทอน</dt>
                    <dd class="col-7">฿{{ number_format($sale->change_amount, 2) }}</dd>
                    @if($sale->note)
                    <dt class="col-5">หมายเหตุ</dt>
                    <dd class="col-7">{{ $sale->note }}</dd>
                    @endif
                </dl>
            </div>
        </div>
    </div>
</div>

<a href="{{ route('sales.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left mr-1"></i> กลับ</a>
@stop
