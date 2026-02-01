@extends('adminlte::page')

@section('title', 'รายการขาย')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>รายการขาย</h1>
        <a href="{{ route('sales.create') }}" class="btn btn-success">
            <i class="fas fa-cash-register mr-1"></i> ขายสินค้า (POS)
        </a>
    </div>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <form action="{{ route('sales.index') }}" method="GET" class="form-inline">
            <input type="text" name="search" class="form-control mr-2" placeholder="เลขที่ใบเสร็จ..." value="{{ request('search') }}">
            <input type="date" name="date_from" class="form-control mr-2" value="{{ request('date_from') }}">
            <span class="mr-2">ถึง</span>
            <input type="date" name="date_to" class="form-control mr-2" value="{{ request('date_to') }}">
            <button type="submit" class="btn btn-info"><i class="fas fa-search"></i> ค้นหา</button>
        </form>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>เลขที่ใบเสร็จ</th>
                    <th>วันที่</th>
                    <th>สมาชิก</th>
                    <th>ยอดรวม</th>
                    <th>ส่วนลด</th>
                    <th>ยอดสุทธิ</th>
                    <th>ชำระ</th>
                    <th>ผู้ขาย</th>
                    <th width="100">จัดการ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sales as $sale)
                <tr>
                    <td><a href="{{ route('sales.show', $sale) }}">{{ $sale->invoice_number }}</a></td>
                    <td>{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $sale->member->name ?? 'ลูกค้าทั่วไป' }}</td>
                    <td>฿{{ number_format($sale->subtotal, 2) }}</td>
                    <td class="text-danger">-฿{{ number_format($sale->discount, 2) }}</td>
                    <td class="text-success font-weight-bold">฿{{ number_format($sale->total, 2) }}</td>
                    <td>
                        @if($sale->payment_method == 'cash')
                            <span class="badge badge-success">เงินสด</span>
                        @elseif($sale->payment_method == 'transfer')
                            <span class="badge badge-info">โอนเงิน</span>
                        @else
                            <span class="badge badge-warning">เครดิต</span>
                        @endif
                    </td>
                    <td>{{ $sale->user->name }}</td>
                    <td>
                        <a href="{{ route('sales.show', $sale) }}" class="btn btn-sm btn-info" title="ดูรายละเอียด">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('sales.receipt', $sale) }}" class="btn btn-sm btn-secondary" title="พิมพ์ใบเสร็จ" target="_blank">
                            <i class="fas fa-print"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center">ไม่พบข้อมูลการขาย</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ $sales->withQueryString()->links() }}
    </div>
</div>
@stop
