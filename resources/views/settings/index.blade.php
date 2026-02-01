@extends('adminlte::page')

@section('title', 'ตั้งค่าระบบ')

@section('content_header')
    <h1><i class="fas fa-cogs mr-2"></i>ตั้งค่าระบบ</h1>
@stop

@section('content')
<form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-md-6">
            <!-- ข้อมูลร้านค้า -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-store mr-2"></i>ข้อมูลร้านค้า</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="store_name">ชื่อร้าน/สหกรณ์ <span class="text-danger">*</span></label>
                        <input type="text" name="store_name" id="store_name" class="form-control @error('store_name') is-invalid @enderror" value="{{ old('store_name', $settings['store_name']) }}" required>
                        @error('store_name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="store_address">ที่อยู่</label>
                        <textarea name="store_address" id="store_address" class="form-control @error('store_address') is-invalid @enderror" rows="3">{{ old('store_address', $settings['store_address']) }}</textarea>
                        @error('store_address')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="store_phone">เบอร์โทรศัพท์</label>
                                <input type="text" name="store_phone" id="store_phone" class="form-control" value="{{ old('store_phone', $settings['store_phone']) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="store_email">อีเมล</label>
                                <input type="email" name="store_email" id="store_email" class="form-control" value="{{ old('store_email', $settings['store_email']) }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="tax_id">เลขประจำตัวผู้เสียภาษี</label>
                        <input type="text" name="tax_id" id="tax_id" class="form-control" value="{{ old('tax_id', $settings['tax_id']) }}">
                    </div>
                    <div class="form-group">
                        <label for="store_logo">โลโก้ร้าน</label>
                        @if($settings['store_logo'])
                            <div class="mb-2">
                                <img src="{{ Storage::url($settings['store_logo']) }}" alt="Logo" style="max-height: 80px;">
                                <button type="button" class="btn btn-sm btn-danger ml-2" id="remove-logo">
                                    <i class="fas fa-times"></i> ลบ
                                </button>
                            </div>
                        @endif
                        <input type="file" name="store_logo" id="store_logo" class="form-control-file" accept="image/*">
                        <small class="text-muted">รองรับ: JPG, PNG, GIF (ไม่เกิน 2MB)</small>
                    </div>
                </div>
            </div>

            <!-- ตั้งค่าใบเสร็จ -->
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-receipt mr-2"></i>ตั้งค่าใบเสร็จ</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="receipt_header">ข้อความหัวใบเสร็จ</label>
                        <textarea name="receipt_header" id="receipt_header" class="form-control" rows="2">{{ old('receipt_header', $settings['receipt_header']) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="receipt_footer">ข้อความท้ายใบเสร็จ</label>
                        <textarea name="receipt_footer" id="receipt_footer" class="form-control" rows="2">{{ old('receipt_footer', $settings['receipt_footer']) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="currency_symbol">สัญลักษณ์สกุลเงิน <span class="text-danger">*</span></label>
                        <input type="text" name="currency_symbol" id="currency_symbol" class="form-control" value="{{ old('currency_symbol', $settings['currency_symbol']) }}" style="width: 80px;" required>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <!-- ตั้งค่าสมาชิกและหุ้น -->
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-users mr-2"></i>ตั้งค่าสมาชิกและหุ้น</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="share_value">มูลค่าหุ้นละ (บาท) <span class="text-danger">*</span></label>
                                <input type="number" name="share_value" id="share_value" class="form-control" value="{{ old('share_value', $settings['share_value']) }}" min="1" step="0.01" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="min_shares">หุ้นขั้นต่ำ <span class="text-danger">*</span></label>
                                <input type="number" name="min_shares" id="min_shares" class="form-control" value="{{ old('min_shares', $settings['min_shares']) }}" min="1" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="max_shares">หุ้นสูงสุด <span class="text-danger">*</span></label>
                                <input type="number" name="max_shares" id="max_shares" class="form-control" value="{{ old('max_shares', $settings['max_shares']) }}" min="1" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="dividend_calculation">วิธีคำนวณปันผล <span class="text-danger">*</span></label>
                        <select name="dividend_calculation" id="dividend_calculation" class="form-control" required>
                            <option value="share_based" {{ $settings['dividend_calculation'] == 'share_based' ? 'selected' : '' }}>ตามทุนเรือนหุ้น</option>
                            <option value="purchase_based" {{ $settings['dividend_calculation'] == 'purchase_based' ? 'selected' : '' }}>ตามยอดซื้อสะสม</option>
                            <option value="mixed" {{ $settings['dividend_calculation'] == 'mixed' ? 'selected' : '' }}>ผสม (หุ้น + ยอดซื้อ)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="fiscal_year_start">เริ่มต้นปีบัญชี <span class="text-danger">*</span></label>
                        <input type="text" name="fiscal_year_start" id="fiscal_year_start" class="form-control" value="{{ old('fiscal_year_start', $settings['fiscal_year_start']) }}" placeholder="MM-DD เช่น 01-01" required>
                        <small class="text-muted">รูปแบบ: เดือน-วัน (เช่น 01-01 = 1 มกราคม)</small>
                    </div>
                </div>
            </div>

            <!-- ตั้งค่าสต๊อก -->
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-boxes mr-2"></i>ตั้งค่าสต๊อก</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="low_stock_alert">แจ้งเตือนสินค้าใกล้หมดเมื่อต่ำกว่า <span class="text-danger">*</span></label>
                        <div class="input-group" style="width: 150px;">
                            <input type="number" name="low_stock_alert" id="low_stock_alert" class="form-control" value="{{ old('low_stock_alert', $settings['low_stock_alert']) }}" min="1" required>
                            <div class="input-group-append">
                                <span class="input-group-text">ชิ้น</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ตั้งค่าสำรองข้อมูล -->
            <div class="card card-secondary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-database mr-2"></i>สำรองข้อมูล</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="backup_enabled" name="backup_enabled" value="1" {{ $settings['backup_enabled'] == '1' ? 'checked' : '' }}>
                            <label class="custom-control-label" for="backup_enabled">เปิดใช้งานการสำรองข้อมูลอัตโนมัติ</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="backup_frequency">ความถี่ในการสำรอง</label>
                        <select name="backup_frequency" id="backup_frequency" class="form-control" style="width: 150px;">
                            <option value="daily" {{ $settings['backup_frequency'] == 'daily' ? 'selected' : '' }}>ทุกวัน</option>
                            <option value="weekly" {{ $settings['backup_frequency'] == 'weekly' ? 'selected' : '' }}>ทุกสัปดาห์</option>
                            <option value="monthly" {{ $settings['backup_frequency'] == 'monthly' ? 'selected' : '' }}>ทุกเดือน</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-footer">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-save mr-2"></i>บันทึกการตั้งค่า
            </button>
        </div>
    </div>
</form>
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

    document.getElementById('remove-logo')?.addEventListener('click', function() {
        Swal.fire({
            title: 'ลบโลโก้?',
            text: 'ต้องการลบโลโก้ร้านใช่หรือไม่?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'ลบ',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('{{ route("settings.remove-logo") }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                }).then(() => location.reload());
            }
        });
    });
</script>
@stop
