@extends('adminlte::page')

@section('title', 'ปีบัญชี')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>ปีบัญชี (ปันผลและเฉลี่ยคืน)</h1>
        <a href="{{ route('fiscal-years.create') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-1"></i> สร้างปีบัญชีใหม่
        </a>
    </div>
@stop

@section('content')
<div class="card">
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>ชื่อปีบัญชี</th>
                    <th>ช่วงเวลา</th>
                    <th>ยอดขายรวม</th>
                    <th>กำไรรวม</th>
                    <th>อัตราปันผล</th>
                    <th>อัตราเฉลี่ยคืน</th>
                    <th>สถานะ</th>
                    <th width="100">จัดการ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($fiscalYears as $fy)
                <tr>
                    <td><strong>{{ $fy->name }}</strong></td>
                    <td>{{ $fy->start_date->format('d/m/Y') }} - {{ $fy->end_date->format('d/m/Y') }}</td>
                    <td>฿{{ number_format($fy->total_sales, 2) }}</td>
                    <td class="text-success">฿{{ number_format($fy->total_profit, 2) }}</td>
                    <td>{{ $fy->dividend_rate }}%</td>
                    <td>{{ $fy->patronage_refund_rate }}%</td>
                    <td>
                        @if($fy->status == 'open')
                            <span class="badge badge-success">เปิด</span>
                        @elseif($fy->status == 'closed')
                            <span class="badge badge-warning">ปิดแล้ว</span>
                        @else
                            <span class="badge badge-info">คำนวณแล้ว</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('fiscal-years.show', $fy) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i> ดู
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">ไม่พบข้อมูลปีบัญชี</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ $fiscalYears->links() }}
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
