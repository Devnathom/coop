@extends('adminlte::page')

@section('title', 'รายงานสมาชิก')

@section('content_header')
    <h1><i class="fas fa-users mr-2"></i>รายงานสมาชิก</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <form action="{{ route('reports.members') }}" method="GET" class="form-inline">
            <label class="mr-2">ช่วงเวลา:</label>
            <input type="date" name="date_from" class="form-control mr-2" value="{{ $dateFrom }}">
            <span class="mr-2">ถึง</span>
            <input type="date" name="date_to" class="form-control mr-2" value="{{ $dateTo }}">
            <button type="submit" class="btn btn-info"><i class="fas fa-search"></i> ค้นหา</button>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-lg-4 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ number_format($memberStats['total_members']) }}</h3>
                <p>สมาชิกทั้งหมด</p>
            </div>
            <div class="icon"><i class="fas fa-users"></i></div>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ number_format($memberStats['new_members']) }}</h3>
                <p>สมาชิกใหม่ในช่วงนี้</p>
            </div>
            <div class="icon"><i class="fas fa-user-plus"></i></div>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>฿{{ number_format($memberStats['total_shares'], 2) }}</h3>
                <p>ทุนเรือนหุ้นรวม</p>
            </div>
            <div class="icon"><i class="fas fa-coins"></i></div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-warning">
        <h3 class="card-title"><i class="fas fa-trophy mr-2"></i>สมาชิกที่ซื้อมากที่สุด Top 20</h3>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>รหัสสมาชิก</th>
                    <th>ชื่อ</th>
                    <th>จำนวนครั้ง</th>
                    <th>ยอดซื้อรวม</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topMembers as $index => $item)
                <tr>
                    <td>
                        @if($index < 3)
                            <span class="badge badge-{{ $index == 0 ? 'warning' : ($index == 1 ? 'secondary' : 'danger') }}">{{ $index + 1 }}</span>
                        @else
                            {{ $index + 1 }}
                        @endif
                    </td>
                    <td>{{ $item->member->member_code }}</td>
                    <td>
                        <a href="{{ route('members.show', $item->member) }}">{{ $item->member->name }}</a>
                    </td>
                    <td>{{ number_format($item->transaction_count) }} ครั้ง</td>
                    <td class="text-success font-weight-bold">฿{{ number_format($item->total_purchase, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop
