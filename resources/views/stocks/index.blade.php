@extends('adminlte::page')

@section('title', 'เคลื่อนไหวสต๊อก')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>เคลื่อนไหวสต๊อก</h1>
        <a href="{{ route('stocks.create') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-1"></i> รับสินค้าเข้า/ปรับปรุงสต๊อก
        </a>
    </div>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <form action="{{ route('stocks.index') }}" method="GET" class="form-inline">
            <select name="product_id" class="form-control mr-2">
                <option value="">-- สินค้าทั้งหมด --</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                @endforeach
            </select>
            <select name="type" class="form-control mr-2">
                <option value="">-- ประเภททั้งหมด --</option>
                <option value="in" {{ request('type') == 'in' ? 'selected' : '' }}>รับเข้า</option>
                <option value="out" {{ request('type') == 'out' ? 'selected' : '' }}>จ่ายออก</option>
                <option value="adjust" {{ request('type') == 'adjust' ? 'selected' : '' }}>ปรับปรุง</option>
            </select>
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
                    <th>วันที่</th>
                    <th>สินค้า</th>
                    <th>ประเภท</th>
                    <th>จำนวน</th>
                    <th>ก่อน</th>
                    <th>หลัง</th>
                    <th>อ้างอิง</th>
                    <th>หมายเหตุ</th>
                    <th>ผู้ดำเนินการ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($movements as $movement)
                <tr>
                    <td>{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $movement->product->name }}</td>
                    <td>
                        @if($movement->type == 'in')
                            <span class="badge badge-success">รับเข้า</span>
                        @elseif($movement->type == 'out')
                            <span class="badge badge-danger">จ่ายออก</span>
                        @else
                            <span class="badge badge-info">ปรับปรุง</span>
                        @endif
                    </td>
                    <td>{{ $movement->quantity }}</td>
                    <td>{{ $movement->stock_before }}</td>
                    <td>{{ $movement->stock_after }}</td>
                    <td>{{ $movement->reference ?? '-' }}</td>
                    <td>{{ $movement->note ?? '-' }}</td>
                    <td>{{ $movement->user->name }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center">ไม่พบข้อมูล</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ $movements->withQueryString()->links() }}
    </div>
</div>
@stop

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    @if(session('success'))
        Swal.fire({ icon: 'success', title: 'สำเร็จ!', text: '{{ session("success") }}', timer: 3000, showConfirmButton: false });
    @endif
    @if(session('error'))
        Swal.fire({ icon: 'error', title: 'เกิดข้อผิดพลาด!', text: '{{ session("error") }}' });
    @endif
</script>
@stop
