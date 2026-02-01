@extends('adminlte::page')

@section('title', 'รายละเอียดปีบัญชี')

@section('content_header')
    <h1>ปีบัญชี: {{ $fiscalYear->name }}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">ข้อมูลปีบัญชี</h3>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-6">ช่วงเวลา</dt>
                    <dd class="col-6">{{ $fiscalYear->start_date->format('d/m/Y') }} - {{ $fiscalYear->end_date->format('d/m/Y') }}</dd>
                    <dt class="col-6">ยอดขายรวม</dt>
                    <dd class="col-6">฿{{ number_format($fiscalYear->total_sales, 2) }}</dd>
                    <dt class="col-6">กำไรรวม</dt>
                    <dd class="col-6 text-success">฿{{ number_format($fiscalYear->total_profit, 2) }}</dd>
                    <dt class="col-6">อัตราปันผล</dt>
                    <dd class="col-6">{{ $fiscalYear->dividend_rate }}%</dd>
                    <dt class="col-6">อัตราเฉลี่ยคืน</dt>
                    <dd class="col-6">{{ $fiscalYear->patronage_refund_rate }}%</dd>
                    <dt class="col-6">สถานะ</dt>
                    <dd class="col-6">
                        @if($fiscalYear->status == 'open')
                            <span class="badge badge-success">เปิด</span>
                        @elseif($fiscalYear->status == 'closed')
                            <span class="badge badge-warning">ปิดแล้ว</span>
                        @else
                            <span class="badge badge-info">คำนวณแล้ว</span>
                        @endif
                    </dd>
                </dl>
            </div>
        </div>

        @if($fiscalYear->status == 'open')
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">คำนวณยอดขาย/กำไร</h3>
            </div>
            <div class="card-body">
                <p>คำนวณยอดขายและกำไรจากการขายในช่วงเวลาของปีบัญชีนี้</p>
                <form action="{{ route('fiscal-years.calculate', $fiscalYear) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-warning btn-block">
                        <i class="fas fa-calculator mr-1"></i> คำนวณยอดขาย/กำไร
                    </button>
                </form>
            </div>
        </div>
        @endif

        @if($fiscalYear->status == 'closed')
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">คำนวณปันผลและเฉลี่ยคืน</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('fiscal-years.dividends', $fiscalYear) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>อัตราปันผล (%)</label>
                        <input type="number" name="dividend_rate" class="form-control" value="5" min="0" max="100" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label>อัตราเฉลี่ยคืน (%)</label>
                        <input type="number" name="patronage_refund_rate" class="form-control" value="3" min="0" max="100" step="0.01" required>
                    </div>
                    <button type="submit" class="btn btn-success btn-block">
                        <i class="fas fa-coins mr-1"></i> คำนวณปันผล/เฉลี่ยคืน
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-8">
        @if($fiscalYear->status == 'calculated')
        <div class="card">
            <div class="card-header bg-info">
                <h3 class="card-title"><i class="fas fa-coins mr-2"></i>รายการปันผล</h3>
            </div>
            <div class="card-body table-responsive p-0" style="max-height: 400px;">
                <table class="table table-hover table-sm">
                    <thead>
                        <tr>
                            <th>สมาชิก</th>
                            <th>ทุนเรือนหุ้น</th>
                            <th>เงินปันผล</th>
                            <th>สถานะ</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fiscalYear->dividends as $dividend)
                        <tr>
                            <td>{{ $dividend->member->member_code }} - {{ $dividend->member->name }}</td>
                            <td>฿{{ number_format($dividend->share_amount, 2) }}</td>
                            <td class="text-success">฿{{ number_format($dividend->dividend_amount, 2) }}</td>
                            <td>
                                @if($dividend->is_paid)
                                    <span class="badge badge-success">จ่ายแล้ว</span>
                                @else
                                    <span class="badge badge-warning">ยังไม่จ่าย</span>
                                @endif
                            </td>
                            <td>
                                @if(!$dividend->is_paid)
                                <button class="btn btn-sm btn-success btn-pay-dividend" data-id="{{ $dividend->id }}">
                                    <i class="fas fa-check"></i> จ่าย
                                </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-warning">
                <h3 class="card-title"><i class="fas fa-hand-holding-usd mr-2"></i>รายการเฉลี่ยคืน</h3>
            </div>
            <div class="card-body table-responsive p-0" style="max-height: 400px;">
                <table class="table table-hover table-sm">
                    <thead>
                        <tr>
                            <th>สมาชิก</th>
                            <th>ยอดซื้อสะสม</th>
                            <th>เงินเฉลี่ยคืน</th>
                            <th>สถานะ</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fiscalYear->patronageRefunds as $refund)
                        <tr>
                            <td>{{ $refund->member->member_code }} - {{ $refund->member->name }}</td>
                            <td>฿{{ number_format($refund->purchase_amount, 2) }}</td>
                            <td class="text-success">฿{{ number_format($refund->refund_amount, 2) }}</td>
                            <td>
                                @if($refund->is_paid)
                                    <span class="badge badge-success">จ่ายแล้ว</span>
                                @else
                                    <span class="badge badge-warning">ยังไม่จ่าย</span>
                                @endif
                            </td>
                            <td>
                                @if(!$refund->is_paid)
                                <button class="btn btn-sm btn-success btn-pay-refund" data-id="{{ $refund->id }}">
                                    <i class="fas fa-check"></i> จ่าย
                                </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</div>

<a href="{{ route('fiscal-years.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left mr-1"></i> กลับ</a>
@stop

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.querySelectorAll('.btn-pay-dividend').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            Swal.fire({
                title: 'ยืนยันการจ่ายปันผล?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'จ่าย',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/dividends/${id}/pay`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
                    }).then(res => res.json()).then(data => {
                        if (data.success) {
                            Swal.fire({ icon: 'success', title: 'สำเร็จ!', timer: 1500 }).then(() => location.reload());
                        }
                    });
                }
            });
        });
    });

    document.querySelectorAll('.btn-pay-refund').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            Swal.fire({
                title: 'ยืนยันการจ่ายเฉลี่ยคืน?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'จ่าย',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/patronage-refunds/${id}/pay`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
                    }).then(res => res.json()).then(data => {
                        if (data.success) {
                            Swal.fire({ icon: 'success', title: 'สำเร็จ!', timer: 1500 }).then(() => location.reload());
                        }
                    });
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
