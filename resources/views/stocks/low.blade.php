@extends('adminlte::page')

@section('title', 'สินค้าใกล้หมด')

@section('content_header')
    <h1><i class="fas fa-exclamation-triangle text-warning mr-2"></i>สินค้าใกล้หมด</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>รหัส</th>
                    <th>ชื่อสินค้า</th>
                    <th>หมวดหมู่</th>
                    <th>สต๊อกคงเหลือ</th>
                    <th>จำนวนขั้นต่ำ</th>
                    <th>สถานะ</th>
                    <th>จัดการ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr class="{{ $product->stock_quantity == 0 ? 'table-danger' : 'table-warning' }}">
                    <td>{{ $product->code }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category->name }}</td>
                    <td>
                        <strong class="{{ $product->stock_quantity == 0 ? 'text-danger' : 'text-warning' }}">
                            {{ $product->stock_quantity }} {{ $product->unit }}
                        </strong>
                    </td>
                    <td>{{ $product->min_stock }} {{ $product->unit }}</td>
                    <td>
                        @if($product->stock_quantity == 0)
                            <span class="badge badge-danger">หมดสต๊อก</span>
                        @else
                            <span class="badge badge-warning">ใกล้หมด</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('stocks.create') }}?product_id={{ $product->id }}" class="btn btn-sm btn-success">
                            <i class="fas fa-plus mr-1"></i> เพิ่มสต๊อก
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-success">
                        <i class="fas fa-check-circle mr-2"></i>ไม่มีสินค้าใกล้หมด
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ $products->links() }}
    </div>
</div>
@stop
