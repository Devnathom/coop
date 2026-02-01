@extends('adminlte::page')

@section('title', 'ข้อมูลสมาชิก')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>ข้อมูลสมาชิก: {{ $member->name }}</h1>
        <a href="{{ route('members.edit', $member) }}" class="btn btn-warning">
            <i class="fas fa-edit mr-1"></i> แก้ไข
        </a>
    </div>
@stop

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <div class="text-center">
                    <div class="bg-primary rounded-circle d-inline-flex justify-content-center align-items-center" style="width: 80px; height: 80px;">
                        <i class="fas fa-user fa-3x text-white"></i>
                    </div>
                </div>
                <h3 class="profile-username text-center mt-3">{{ $member->name }}</h3>
                <p class="text-muted text-center">{{ $member->member_code }}</p>
                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <b>เบอร์โทร</b> <a class="float-right">{{ $member->phone ?? '-' }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>อีเมล</b> <a class="float-right">{{ $member->email ?? '-' }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>วันที่สมัคร</b> <a class="float-right">{{ $member->join_date->format('d/m/Y') }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>สถานะ</b> 
                        <span class="float-right badge badge-{{ $member->is_active ? 'success' : 'danger' }}">
                            {{ $member->is_active ? 'ใช้งาน' : 'ไม่ใช้งาน' }}
                        </span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">ข้อมูลการเงิน</h3>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>ทุนเรือนหุ้น</span>
                    <strong class="text-info">฿{{ number_format($member->share_amount, 2) }}</strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span>ยอดซื้อสะสม</span>
                    <strong class="text-success">฿{{ number_format($member->accumulated_purchase, 2) }}</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-shopping-cart mr-2"></i>ประวัติการซื้อล่าสุด</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>เลขที่ใบเสร็จ</th>
                            <th>วันที่</th>
                            <th>ยอดรวม</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($member->sales as $sale)
                        <tr>
                            <td><a href="{{ route('sales.show', $sale) }}">{{ $sale->invoice_number }}</a></td>
                            <td>{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                            <td class="text-success">฿{{ number_format($sale->total, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">ไม่มีประวัติการซื้อ</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-coins mr-2"></i>ประวัติปันผล</h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>ปีบัญชี</th>
                                    <th>จำนวนเงิน</th>
                                    <th>สถานะ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($member->dividends as $dividend)
                                <tr>
                                    <td>{{ $dividend->fiscalYear->name }}</td>
                                    <td>฿{{ number_format($dividend->dividend_amount, 2) }}</td>
                                    <td>
                                        <span class="badge badge-{{ $dividend->is_paid ? 'success' : 'warning' }}">
                                            {{ $dividend->is_paid ? 'จ่ายแล้ว' : 'ยังไม่จ่าย' }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">ไม่มีข้อมูล</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-warning">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-hand-holding-usd mr-2"></i>ประวัติเฉลี่ยคืน</h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>ปีบัญชี</th>
                                    <th>จำนวนเงิน</th>
                                    <th>สถานะ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($member->patronageRefunds as $refund)
                                <tr>
                                    <td>{{ $refund->fiscalYear->name }}</td>
                                    <td>฿{{ number_format($refund->refund_amount, 2) }}</td>
                                    <td>
                                        <span class="badge badge-{{ $refund->is_paid ? 'success' : 'warning' }}">
                                            {{ $refund->is_paid ? 'จ่ายแล้ว' : 'ยังไม่จ่าย' }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">ไม่มีข้อมูล</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<a href="{{ route('members.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left mr-1"></i> กลับ</a>
@stop
