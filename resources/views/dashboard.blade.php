@extends('adminlte::page')

@section('title', 'แดชบอร์ด')

@section('content_header')
    <h1>แดชบอร์ด</h1>
@stop

@section('content')
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ number_format($totalMembers) }}</h3>
                <p>สมาชิกทั้งหมด</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="{{ route('members.index') }}" class="small-box-footer">ดูรายละเอียด <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>฿{{ number_format($todaySales, 2) }}</h3>
                <p>ยอดขายวันนี้ ({{ $todaySalesCount }} รายการ)</p>
            </div>
            <div class="icon">
                <i class="fas fa-cash-register"></i>
            </div>
            <a href="{{ route('sales.index') }}" class="small-box-footer">ดูรายละเอียด <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ number_format($totalProducts) }}</h3>
                <p>สินค้าทั้งหมด</p>
            </div>
            <div class="icon">
                <i class="fas fa-box"></i>
            </div>
            <a href="{{ route('products.index') }}" class="small-box-footer">ดูรายละเอียด <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ number_format($lowStockProducts) }}</h3>
                <p>สินค้าใกล้หมด</p>
            </div>
            <div class="icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <a href="{{ route('stocks.low') }}" class="small-box-footer">ดูรายละเอียด <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-line mr-2"></i>กราฟยอดขาย 7 วันย้อนหลัง</h3>
            </div>
            <div class="card-body">
                <canvas id="salesChart" height="100"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-primary">
                <h3 class="card-title"><i class="fas fa-calendar-alt mr-2"></i>สรุปยอดเดือนนี้</h3>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>ยอดขายรวม</span>
                    <span class="text-success font-weight-bold">฿{{ number_format($monthSales, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span>จำนวนรายการขาย</span>
                    <span class="font-weight-bold">{{ number_format($monthSalesCount) }} รายการ</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-history mr-2"></i>รายการขายล่าสุด</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>เลขที่ใบเสร็จ</th>
                            <th>สมาชิก</th>
                            <th>ยอดรวม</th>
                            <th>ผู้ขาย</th>
                            <th>เวลา</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentSales as $sale)
                        <tr>
                            <td><a href="{{ route('sales.show', $sale) }}">{{ $sale->invoice_number }}</a></td>
                            <td>{{ $sale->member->name ?? 'ลูกค้าทั่วไป' }}</td>
                            <td class="text-success">฿{{ number_format($sale->total, 2) }}</td>
                            <td>{{ $sale->user->name }}</td>
                            <td>{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">ไม่มีรายการขาย</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-warning">
                <h3 class="card-title"><i class="fas fa-exclamation-triangle mr-2"></i>สินค้าใกล้หมด</h3>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($lowStockItems as $product)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $product->name }}</strong>
                            <br><small class="text-muted">{{ $product->category->name }}</small>
                        </div>
                        <span class="badge badge-{{ $product->stock_quantity == 0 ? 'danger' : 'warning' }} badge-pill">
                            {{ $product->stock_quantity }} {{ $product->unit }}
                        </span>
                    </li>
                    @empty
                    <li class="list-group-item text-center text-muted">ไม่มีสินค้าใกล้หมด</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    fetch('{{ route("api.sales-chart") }}')
        .then(response => response.json())
        .then(data => {
            const ctx = document.getElementById('salesChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.map(item => item.date),
                    datasets: [{
                        label: 'ยอดขาย (บาท)',
                        data: data.map(item => item.total),
                        borderColor: 'rgb(75, 192, 192)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        tension: 0.1,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'top' }
                    },
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
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
</script>
@stop
