@extends('adminlte::page')

@section('title', 'หมวดหมู่สินค้า')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>หมวดหมู่สินค้า</h1>
        <a href="{{ route('categories.create') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-1"></i> เพิ่มหมวดหมู่
        </a>
    </div>
@stop

@section('content')
<div class="card">
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>#</th>
                    <th>ชื่อหมวดหมู่</th>
                    <th>รายละเอียด</th>
                    <th>จำนวนสินค้า</th>
                    <th>สถานะ</th>
                    <th width="120">จัดการ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->description ?? '-' }}</td>
                    <td><span class="badge badge-info">{{ $category->products_count }} รายการ</span></td>
                    <td>
                        @if($category->is_active)
                            <span class="badge badge-success">ใช้งาน</span>
                        @else
                            <span class="badge badge-danger">ไม่ใช้งาน</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-warning" title="แก้ไข">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-danger btn-delete" data-id="{{ $category->id }}" data-name="{{ $category->name }}" title="ลบ">
                            <i class="fas fa-trash"></i>
                        </button>
                        <form id="delete-form-{{ $category->id }}" action="{{ route('categories.destroy', $category) }}" method="POST" class="d-none">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">ไม่พบข้อมูลหมวดหมู่</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ $categories->links() }}
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
                text: `คุณต้องการลบหมวดหมู่ "${name}" ใช่หรือไม่?`,
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
