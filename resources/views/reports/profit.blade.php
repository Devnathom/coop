@extends('adminlte::page')

@section('title', 'รายงานกำไร')

@section('content_header')
    <h1><i class="fas fa-money-bill-wave mr-2"></i>รายงานกำไร</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <form action="{{ route('reports.profit') }}" method="GET" class="form-inline">
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
                <h3>฿{{ number_format($summary['total_revenue'], 2) }}</h3>
                <p>รายได้รวม</p>
            </div>
            <div class="icon"><i class="fas fa-chart-line"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>฿{{ number_format($summary['total_cost'], 2) }}</h3>
                <p>ต้นทุนรวม</p>
            </div>
            <div class="icon"><i class="fas fa-minus-circle"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>฿{{ number_format($summary['total_profit'], 2) }}</h3>
                <p>กำไรสุทธิ</p>
            </div>
            <div class="icon"><i class="fas fa-plus-circle"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>{{ number_format($summary['profit_margin'], 2) }}%</h3>
                <p>อัตรากำไร</p>
            </div>
            <div class="icon"><i class="fas fa-percent"></i></div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">กราฟรายได้ ต้นทุน และกำไร</h3>
    </div>
    <div class="card-body">
        <canvas id="profitChart" height="100"></canvas>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">รายละเอียดกำไรรายวัน</h3>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>วันที่</th>
                    <th>รายได้</th>
                    <th>ต้นทุน</th>
                    <th>กำไร</th>
                    <th>อัตรากำไร</th>
                </tr>
            </thead>
            <tbody>
                @foreach($profitData as $day)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($day->date)->format('d/m/Y') }}</td>
                    <td>฿{{ number_format($day->revenue, 2) }}</td>
                    <td class="text-danger">฿{{ number_format($day->cost, 2) }}</td>
                    <td class="text-success font-weight-bold">฿{{ number_format($day->profit, 2) }}</td>
                    <td>{{ $day->revenue > 0 ? number_format(($day->profit / $day->revenue) * 100, 2) : 0 }}%</td>
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
    const profitData = @json($profitData);
    new Chart(document.getElementById('profitChart'), {
        type: 'line',
        data: {
            labels: profitData.map(d => d.date),
            datasets: [
                { label: 'รายได้', data: profitData.map(d => d.revenue), borderColor: '#17a2b8', fill: false },
                { label: 'ต้นทุน', data: profitData.map(d => d.cost), borderColor: '#dc3545', fill: false },
                { label: 'กำไร', data: profitData.map(d => d.profit), borderColor: '#28a745', fill: false }
            ]
        },
        options: { responsive: true, scales: { y: { beginAtZero: true } } }
    });
</script>
@stop
