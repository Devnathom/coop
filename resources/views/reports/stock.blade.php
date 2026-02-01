@extends('adminlte::page')

@section('title', 'รายงานสต๊อก')

@section('content_header')
    <h1><i class="fas fa-clipboard-list mr-2"></i>รายงานสต๊อกสินค้า</h1>
@stop

@section('content')
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ number_format($summary['total_products']) }}</h3>
                <p>สินค้าทั้งหมด</p>
            </div>
            <div class="icon"><i class="fas fa-box"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>฿{{ number_format($summary['total_stock_value'], 2) }}</h3>
                <p>มูลค่าสต๊อกรวม</p>
            </div>
            <div class="icon"><i class="fas fa-coins"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ number_format($summary['low_stock_count']) }}</h3>
                <p>สินค้าใกล้หมด</p>
            </div>
            <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ number_format($summary['out_of_stock']) }}</h3>
                <p>สินค้าหมด</p>
            </div>
            <div class="icon"><i class="fas fa-times-circle"></i></div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">รายการสินค้าและสต๊อก</h3>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap" id="stockTable">
            <thead>
                <tr>
                    <th>รหัส</th>
                    <th>ชื่อสินค้า</th>
                    <th>หมวดหมู่</th>
                    <th>ต้นทุน</th>
                    <th>ราคาขาย</th>
                    <th>สต๊อก</th>
                    <th>มูลค่า</th>
                    <th>สถานะ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr class="{{ $product->stock_quantity == 0 ? 'table-danger' : ($product->stock_quantity <= $product->min_stock ? 'table-warning' : '') }}">
                    <td>{{ $product->code }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category->name }}</td>
                    <td>฿{{ number_format($product->cost_price, 2) }}</td>
                    <td>฿{{ number_format($product->selling_price, 2) }}</td>
                    <td>{{ $product->stock_quantity }} {{ $product->unit }}</td>
                    <td class="text-success">฿{{ number_format($product->stock_quantity * $product->cost_price, 2) }}</td>
                    <td>
                        @if($product->stock_quantity == 0)
                            <span class="badge badge-danger">หมด</span>
                        @elseif($product->stock_quantity <= $product->min_stock)
                            <span class="badge badge-warning">ใกล้หมด</span>
                        @else
                            <span class="badge badge-success">ปกติ</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop
