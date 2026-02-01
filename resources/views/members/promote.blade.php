@extends('adminlte::page')

@section('title', 'เลื่อนชั้นเรียน')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-graduation-cap mr-2"></i>เลื่อนชั้นเรียน</h1>
        <a href="{{ route('members.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-1"></i> กลับ
        </a>
    </div>
@stop

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-level-up-alt mr-2"></i>เลื่อนชั้นทั้งระดับ</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('members.promote-all') }}" method="POST" id="promote-all-form">
                    @csrf
                    <div class="form-group">
                        <label>เลือกระดับชั้นที่จะเลื่อน</label>
                        <select name="from_class" class="form-control" required>
                            <option value="">-- เลือกระดับชั้น --</option>
                            @foreach(\App\Models\Member::$classOrder as $class)
                                @if($class != 'ม.6')
                                <option value="{{ $class }}">{{ $class }} → {{ \App\Models\Member::$classOrder[array_search($class, \App\Models\Member::$classOrder) + 1] }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-level-up-alt mr-1"></i> เลื่อนชั้นทั้งหมด
                    </button>
                </form>
            </div>
        </div>

        <div class="card card-danger">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-user-graduate mr-2"></i>จบการศึกษา ม.6</h3>
            </div>
            <div class="card-body">
                <p class="text-muted">ปิดสถานะสมาชิก ม.6 ทั้งหมด (สำเร็จการศึกษา)</p>
                <form action="{{ route('members.graduate') }}" method="POST" id="graduate-form">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-block">
                        <i class="fas fa-user-graduate mr-1"></i> ปิดสถานะ ม.6 ทั้งหมด
                    </button>
                </form>
            </div>
        </div>

        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-info-circle mr-2"></i>สรุปจำนวนสมาชิก</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm">
                    @foreach(\App\Models\Member::$classLevels as $key => $value)
                    @php $count = $members->where('class_level', $key)->count(); @endphp
                    @if($count > 0)
                    <tr>
                        <td>{{ $value }}</td>
                        <td class="text-right"><strong>{{ $count }}</strong> คน</td>
                    </tr>
                    @endif
                    @endforeach
                    <tr class="bg-light">
                        <td><strong>รวมทั้งหมด</strong></td>
                        <td class="text-right"><strong>{{ $members->count() }}</strong> คน</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-users mr-2"></i>รายชื่อสมาชิก (เลื่อนทีละคน)</h3>
            </div>
            <div class="card-body table-responsive p-0" style="max-height: 600px;">
                <table class="table table-hover table-sm text-nowrap">
                    <thead class="sticky-top bg-white">
                        <tr>
                            <th>รหัส</th>
                            <th>ชื่อ-นามสกุล</th>
                            <th>ชั้น/ห้อง</th>
                            <th width="120">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($members as $member)
                        <tr id="member-{{ $member->id }}">
                            <td>{{ $member->member_code }}</td>
                            <td>{{ $member->name }}</td>
                            <td class="class-display">{{ $member->class_level }}{{ $member->room ? '/'.$member->room : '' }}</td>
                            <td>
                                @if(in_array($member->class_level, \App\Models\Member::$classOrder) && $member->class_level != 'ม.6')
                                <button type="button" class="btn btn-sm btn-success btn-promote" data-id="{{ $member->id }}">
                                    <i class="fas fa-arrow-up"></i> เลื่อนชั้น
                                </button>
                                @elseif($member->class_level == 'ม.6')
                                <span class="badge badge-warning">ม.6 (รอจบ)</span>
                                @else
                                <span class="badge badge-secondary">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">ไม่พบข้อมูลสมาชิก</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
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
    @if(session('success'))
        Swal.fire({ icon: 'success', title: 'สำเร็จ!', text: '{{ session("success") }}', timer: 3000, showConfirmButton: false });
    @endif

    // เลื่อนชั้นทีละคน
    document.querySelectorAll('.btn-promote').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const row = document.getElementById('member-' + id);
            
            fetch(`/members/${id}/promote`, {
                method: 'POST',
                headers: { 
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    row.querySelector('.class-display').textContent = data.new_class;
                    Swal.fire({ icon: 'success', title: 'เลื่อนชั้นแล้ว!', text: `เลื่อนเป็น ${data.new_class}`, timer: 1500, showConfirmButton: false });
                    if (data.new_class == 'ม.6') {
                        this.outerHTML = '<span class="badge badge-warning">ม.6 (รอจบ)</span>';
                    }
                } else {
                    Swal.fire({ icon: 'error', title: 'ไม่สำเร็จ', text: data.message });
                }
            });
        });
    });

    // ยืนยันเลื่อนชั้นทั้งระดับ
    document.getElementById('promote-all-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        Swal.fire({
            title: 'ยืนยันการเลื่อนชั้น?',
            text: 'ต้องการเลื่อนชั้นสมาชิกทั้งระดับใช่หรือไม่?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'เลื่อนชั้น',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) form.submit();
        });
    });

    // ยืนยันจบการศึกษา
    document.getElementById('graduate-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        Swal.fire({
            title: 'ยืนยันปิดสถานะ ม.6?',
            text: 'สมาชิก ม.6 ทั้งหมดจะถูกปิดสถานะ (จบการศึกษา)',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'ปิดสถานะทั้งหมด',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) form.submit();
        });
    });
</script>
@stop
