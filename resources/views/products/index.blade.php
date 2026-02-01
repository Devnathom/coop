@extends('adminlte::page')

@section('title', 'รายการสินค้า')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>รายการสินค้า</h1>
        <a href="{{ route('products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-1"></i> เพิ่มสินค้า
        </a>
    </div>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <form action="{{ route('products.index') }}" method="GET" class="form-inline">
            <input type="text" name="search" class="form-control mr-2" placeholder="ค้นหาชื่อ, รหัส, บาร์โค้ด..." value="{{ request('search') }}">
            <select name="category_id" class="form-control mr-2">
                <option value="">-- หมวดหมู่ทั้งหมด --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
            <select name="stock_status" class="form-control mr-2">
                <option value="">-- สต๊อกทั้งหมด --</option>
                <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>สินค้าใกล้หมด</option>
                <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>สินค้าหมด</option>
            </select>
            <button type="submit" class="btn btn-info"><i class="fas fa-search"></i> ค้นหา</button>
        </form>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>รหัส</th>
                    <th>ชื่อสินค้า</th>
                    <th>หมวดหมู่</th>
                    <th>ต้นทุน</th>
                    <th>ราคาขาย</th>
                    <th>สต๊อก</th>
                    <th>สถานะ</th>
                    <th width="150">จัดการ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr class="{{ $product->stock_quantity <= $product->min_stock ? 'table-warning' : '' }}">
                    <td>{{ $product->code }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category->name }}</td>
                    <td>฿{{ number_format($product->cost_price, 2) }}</td>
                    <td class="text-success">฿{{ number_format($product->selling_price, 2) }}</td>
                    <td>
                        <span class="badge badge-{{ $product->stock_quantity == 0 ? 'danger' : ($product->stock_quantity <= $product->min_stock ? 'warning' : 'success') }}">
                            {{ $product->stock_quantity }} {{ $product->unit }}
                        </span>
                    </td>
                    <td>
                        @if($product->is_active)
                            <span class="badge badge-success">ใช้งาน</span>
                        @else
                            <span class="badge badge-danger">ไม่ใช้งาน</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-info" title="ดูรายละเอียด">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning" title="แก้ไข">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-danger btn-delete" data-id="{{ $product->id }}" data-name="{{ $product->name }}" title="ลบ">
                            <i class="fas fa-trash"></i>
                        </button>
                        <form id="delete-form-{{ $product->id }}" action="{{ route('products.destroy', $product) }}" method="POST" class="d-none">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">ไม่พบข้อมูลสินค้า</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ $products->withQueryString()->links() }}
    </div>
</div>
@stop

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const name = this.dataset.name;
            Swal.fire({
                title: 'ยืนยันการลบ?',
                text: `คุณต้องการลบสินค้า "${name}" ใช่หรือไม่?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'ลบ',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        });
    });

    @if(session('success'))
        Swal.fire({ icon: 'success', title: 'สำเร็จ!', text: '{{ session("success") }}', timer: 3000, showConfirmButton: false });
    @endif
    @if(session('error'))
        Swal.fire({ icon: 'error', title: 'เกิดข้อผิดพลาด!', text: '{{ session("error") }}' });
    @endif
</script>
@stop
