@extends('adminlte::page')

@section('title', 'รายละเอียดสินค้า')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>รายละเอียดสินค้า</h1>
        <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">
            <i class="fas fa-edit mr-1"></i> แก้ไข
        </a>
    </div>
@stop

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                @if($product->image)
                    <div class="text-center">
                        <img class="img-fluid" src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                    </div>
                @else
                    <div class="text-center">
                        <div class="bg-secondary rounded d-inline-flex justify-content-center align-items-center" style="width: 150px; height: 150px;">
                            <i class="fas fa-box fa-4x text-white"></i>
                        </div>
                    </div>
                @endif
                <h3 class="profile-username text-center mt-3">{{ $product->name }}</h3>
                <p class="text-muted text-center">{{ $product->code }}</p>
                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <b>หมวดหมู่</b> <a class="float-right">{{ $product->category->name }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>บาร์โค้ด</b> <a class="float-right">{{ $product->barcode ?? '-' }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>หน่วย</b> <a class="float-right">{{ $product->unit }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>สถานะ</b> 
                        <span class="float-right badge badge-{{ $product->is_active ? 'success' : 'danger' }}">
                            {{ $product->is_active ? 'ใช้งาน' : 'ไม่ใช้งาน' }}
                        </span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-info">
                <h3 class="card-title">ข้อมูลราคาและสต๊อก</h3>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>ราคาต้นทุน</span>
                    <strong>฿{{ number_format($product->cost_price, 2) }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>ราคาขาย</span>
                    <strong class="text-success">฿{{ number_format($product->selling_price, 2) }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>กำไรต่อชิ้น</span>
                    <strong class="text-info">฿{{ number_format($product->selling_price - $product->cost_price, 2) }}</strong>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-2">
                    <span>สต๊อกคงเหลือ</span>
                    <strong class="badge badge-{{ $product->stock_quantity == 0 ? 'danger' : ($product->stock_quantity <= $product->min_stock ? 'warning' : 'success') }}">
                        {{ $product->stock_quantity }} {{ $product->unit }}
                    </strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span>จำนวนขั้นต่ำ</span>
                    <strong>{{ $product->min_stock }} {{ $product->unit }}</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-history mr-2"></i>ประวัติการเคลื่อนไหวสต๊อก</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>วันที่</th>
                            <th>ประเภท</th>
                            <th>จำนวน</th>
                            <th>ก่อน</th>
                            <th>หลัง</th>
                            <th>อ้างอิง</th>
                            <th>ผู้ดำเนินการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stockMovements as $movement)
                        <tr>
                            <td>{{ $movement->created_at->format('d/m/Y H:i') }}</td>
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
                            <td>{{ $movement->user->name }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">ไม่มีประวัติการเคลื่อนไหว</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<a href="{{ route('products.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left mr-1"></i> กลับ</a>
@stop
