@extends('adminlte::page')

@section('title', 'จัดการสมาชิก')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>จัดการสมาชิก</h1>
        <a href="{{ route('members.create') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-1"></i> เพิ่มสมาชิก
        </a>
    </div>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <form action="{{ route('members.index') }}" method="GET" class="form-inline">
            <input type="text" name="search" class="form-control mr-2" placeholder="ค้นหาชื่อ, รหัส, เบอร์โทร..." value="{{ request('search') }}">
            <select name="status" class="form-control mr-2">
                <option value="">-- สถานะทั้งหมด --</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>ใช้งาน</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>ไม่ใช้งาน</option>
            </select>
            <button type="submit" class="btn btn-info"><i class="fas fa-search"></i> ค้นหา</button>
        </form>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>รหัสสมาชิก</th>
                    <th>ชื่อ-นามสกุล</th>
                    <th>เบอร์โทร</th>
                    <th>วันที่สมัคร</th>
                    <th>หุ้น</th>
                    <th>ยอดซื้อสะสม</th>
                    <th>สถานะ</th>
                    <th width="150">จัดการ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($members as $member)
                <tr>
                    <td>{{ $member->member_code }}</td>
                    <td>{{ $member->name }}</td>
                    <td>{{ $member->phone ?? '-' }}</td>
                    <td>{{ $member->join_date->format('d/m/Y') }}</td>
                    <td class="text-info">฿{{ number_format($member->share_amount, 2) }}</td>
                    <td class="text-success">฿{{ number_format($member->accumulated_purchase, 2) }}</td>
                    <td>
                        @if($member->is_active)
                            <span class="badge badge-success">ใช้งาน</span>
                        @else
                            <span class="badge badge-danger">ไม่ใช้งาน</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('members.show', $member) }}" class="btn btn-sm btn-info" title="ดูรายละเอียด">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('members.edit', $member) }}" class="btn btn-sm btn-warning" title="แก้ไข">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-danger btn-delete" data-id="{{ $member->id }}" data-name="{{ $member->name }}" title="ลบ">
                            <i class="fas fa-trash"></i>
                        </button>
                        <form id="delete-form-{{ $member->id }}" action="{{ route('members.destroy', $member) }}" method="POST" class="d-none">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">ไม่พบข้อมูลสมาชิก</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        <div class="row">
            <div class="col-md-8">
                {{ $members->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Summary Section -->
<div class="row">
    <div class="col-lg-2 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ number_format($summary['total_members']) }}</h3>
                <p>สมาชิกทั้งหมด</p>
            </div>
            <div class="icon"><i class="fas fa-users"></i></div>
        </div>
    </div>
    <div class="col-lg-2 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ number_format($summary['active_members']) }}</h3>
                <p>ใช้งาน</p>
            </div>
            <div class="icon"><i class="fas fa-user-check"></i></div>
        </div>
    </div>
    <div class="col-lg-2 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ number_format($summary['inactive_members']) }}</h3>
                <p>ไม่ใช้งาน</p>
            </div>
            <div class="icon"><i class="fas fa-user-times"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>฿{{ number_format($summary['total_shares'], 2) }}</h3>
                <p>รวมทุนเรือนหุ้น</p>
            </div>
            <div class="icon"><i class="fas fa-hand-holding-usd"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>฿{{ number_format($summary['total_purchases'], 2) }}</h3>
                <p>รวมยอดซื้อสะสม</p>
            </div>
            <div class="icon"><i class="fas fa-shopping-cart"></i></div>
        </div>
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
                text: `คุณต้องการลบสมาชิก "${name}" ใช่หรือไม่?`,
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
        Swal.fire({
            icon: 'success',
            title: 'สำเร็จ!',
            text: '{{ session("success") }}',
            timer: 3000,
            showConfirmButton: false
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'เกิดข้อผิดพลาด!',
            text: '{{ session("error") }}'
        });
    @endif
</script>
@stop
