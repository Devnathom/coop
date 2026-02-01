@extends('adminlte::page')

@section('title', 'รายงานสินค้า')

@section('content_header')
    <h1><i class="fas fa-chart-bar mr-2"></i>รายงานสินค้า</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <form action="{{ route('reports.products') }}" method="GET" class="form-inline">
            <label class="mr-2">ช่วงเวลา:</label>
            <input type="date" name="date_from" class="form-control mr-2" value="{{ $dateFrom }}">
            <span class="mr-2">ถึง</span>
            <input type="date" name="date_to" class="form-control mr-2" value="{{ $dateTo }}">
            <button type="submit" class="btn btn-info"><i class="fas fa-search"></i> ค้นหา</button>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary">
                <h3 class="card-title"><i class="fas fa-trophy mr-2"></i>สินค้าขายดี Top 20</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>สินค้า</th>
                            <th>จำนวนขาย</th>
                            <th>ยอดขาย</th>
                            <th>กำไร</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topProducts as $index => $item)
                        <tr>
                            <td>
                                @if($index < 3)
                                    <span class="badge badge-{{ $index == 0 ? 'warning' : ($index == 1 ? 'secondary' : 'danger') }}">{{ $index + 1 }}</span>
                                @else
                                    {{ $index + 1 }}
                                @endif
                            </td>
                            <td>
                                <strong>{{ $item->product->name }}</strong>
                                <br><small class="text-muted">{{ $item->product->code }}</small>
                            </td>
                            <td>{{ number_format($item->total_quantity) }}</td>
                            <td class="text-success">฿{{ number_format($item->total_sales, 2) }}</td>
                            <td class="text-info">฿{{ number_format($item->total_profit, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-info">
                <h3 class="card-title"><i class="fas fa-tags mr-2"></i>ยอดขายตามหมวดหมู่</h3>
            </div>
            <div class="card-body">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const categoryData = @json($categoryStats);
    const colors = ['#007bff', '#28a745', '#ffc107', '#dc3545', '#17a2b8', '#6c757d', '#6610f2', '#e83e8c'];
    
    new Chart(document.getElementById('categoryChart'), {
        type: 'doughnut',
        data: {
            labels: categoryData.map(c => c.product?.category?.name || 'ไม่ระบุ'),
            datasets: [{
                data: categoryData.map(c => c.total_sales),
                backgroundColor: colors
            }]
        },
        options: { responsive: true }
    });
</script>
@stop
