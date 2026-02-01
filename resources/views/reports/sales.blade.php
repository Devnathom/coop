@extends('adminlte::page')

@section('title', 'รายงานการขาย')

@section('content_header')
    <h1><i class="fas fa-chart-line mr-2"></i>รายงานการขาย</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <form action="{{ route('reports.sales') }}" method="GET" class="form-inline">
            <label class="mr-2">ช่วงเวลา:</label>
            <input type="date" name="date_from" class="form-control mr-2" value="{{ $dateFrom }}">
            <span class="mr-2">ถึง</span>
            <input type="date" name="date_to" class="form-control mr-2" value="{{ $dateTo }}">
            <button type="submit" class="btn btn-info"><i class="fas fa-search"></i> ค้นหา</button>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>฿{{ number_format($summary['total_sales'], 2) }}</h3>
                <p>ยอดขายรวม</p>
            </div>
            <div class="icon"><i class="fas fa-chart-line"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ number_format($summary['total_transactions']) }}</h3>
                <p>จำนวนรายการ</p>
            </div>
            <div class="icon"><i class="fas fa-receipt"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>฿{{ number_format($summary['total_discount'], 2) }}</h3>
                <p>ส่วนลดรวม</p>
            </div>
            <div class="icon"><i class="fas fa-percent"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>฿{{ number_format($summary['average_sale'], 2) }}</h3>
                <p>เฉลี่ยต่อรายการ</p>
            </div>
            <div class="icon"><i class="fas fa-calculator"></i></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">กราฟยอดขายรายวัน</h3>
            </div>
            <div class="card-body">
                <canvas id="salesChart" height="100"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">สรุปยอดขายรายวัน</h3>
            </div>
            <div class="card-body table-responsive p-0" style="max-height: 300px;">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>วันที่</th>
                            <th>รายการ</th>
                            <th>ยอด</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dailySales as $day)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($day->date)->format('d/m/Y') }}</td>
                            <td>{{ $day->count }}</td>
                            <td class="text-success">฿{{ number_format($day->total, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">รายการขายทั้งหมด</h3>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>เลขที่</th>
                    <th>วันที่</th>
                    <th>สมาชิก</th>
                    <th>ยอดรวม</th>
                    <th>ส่วนลด</th>
                    <th>ยอดสุทธิ</th>
                    <th>ผู้ขาย</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sales as $sale)
                <tr>
                    <td><a href="{{ route('sales.show', $sale) }}">{{ $sale->invoice_number }}</a></td>
                    <td>{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $sale->member->name ?? 'ลูกค้าทั่วไป' }}</td>
                    <td>฿{{ number_format($sale->subtotal, 2) }}</td>
                    <td class="text-danger">-฿{{ number_format($sale->discount, 2) }}</td>
                    <td class="text-success">฿{{ number_format($sale->total, 2) }}</td>
                    <td>{{ $sale->user->name }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const dailyData = @json($dailySales);
    new Chart(document.getElementById('salesChart'), {
        type: 'bar',
        data: {
            labels: dailyData.map(d => d.date),
            datasets: [{
                label: 'ยอดขาย (บาท)',
                data: dailyData.map(d => d.total),
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: { responsive: true, scales: { y: { beginAtZero: true } } }
    });
</script>
@stop
