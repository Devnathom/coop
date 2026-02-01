@extends('adminlte::page')

@section('title', 'รับสินค้าเข้า/ปรับปรุงสต๊อก')

@section('content_header')
    <h1>รับสินค้าเข้า/ปรับปรุงสต๊อก</h1>
@stop

@section('content')
<div class="card">
    <form action="{{ route('stocks.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="product_id">สินค้า <span class="text-danger">*</span></label>
                        <select name="product_id" id="product_id" class="form-control @error('product_id') is-invalid @enderror" required>
                            <option value="">-- เลือกสินค้า --</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" data-stock="{{ $product->stock_quantity }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->code }} - {{ $product->name }} (คงเหลือ: {{ $product->stock_quantity }} {{ $product->unit }})
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="type">ประเภท <span class="text-danger">*</span></label>
                        <select name="type" id="type" class="form-control @error('type') is-invalid @enderror" required>
                            <option value="in" {{ old('type') == 'in' ? 'selected' : '' }}>รับเข้า</option>
                            <option value="out" {{ old('type') == 'out' ? 'selected' : '' }}>จ่ายออก</option>
                            <option value="adjust" {{ old('type') == 'adjust' ? 'selected' : '' }}>ปรับปรุง (กำหนดจำนวนใหม่)</option>
                        </select>
                        @error('type')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="quantity">จำนวน <span class="text-danger">*</span></label>
                        <input type="number" name="quantity" id="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity') }}" min="1" required>
                        @error('quantity')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small id="quantity-help" class="text-muted"></small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="cost_price">ราคาต้นทุน (บาท)</label>
                        <input type="number" name="cost_price" id="cost_price" class="form-control @error('cost_price') is-invalid @enderror" value="{{ old('cost_price') }}" step="0.01" min="0">
                        @error('cost_price')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>สต๊อกคงเหลือปัจจุบัน</label>
                        <input type="text" id="current-stock" class="form-control" readonly>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="note">หมายเหตุ</label>
                <textarea name="note" id="note" class="form-control @error('note') is-invalid @enderror" rows="2">{{ old('note') }}</textarea>
                @error('note')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> บันทึก</button>
            <a href="{{ route('stocks.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left mr-1"></i> กลับ</a>
        </div>
    </form>
</div>
@stop

@section('js')
<script>
    const productSelect = document.getElementById('product_id');
    const typeSelect = document.getElementById('type');
    const quantityHelp = document.getElementById('quantity-help');
    const currentStock = document.getElementById('current-stock');

    function updateHelp() {
        const selected = productSelect.options[productSelect.selectedIndex];
        const stock = selected.dataset.stock || 0;
        const type = typeSelect.value;

        currentStock.value = stock;

        if (type === 'in') {
            quantityHelp.textContent = 'ระบุจำนวนที่ต้องการเพิ่ม';
        } else if (type === 'out') {
            quantityHelp.textContent = 'ระบุจำนวนที่ต้องการลด (ไม่เกิน ' + stock + ')';
        } else {
            quantityHelp.textContent = 'ระบุจำนวนสต๊อกใหม่ที่ต้องการ';
        }
    }

    productSelect.addEventListener('change', updateHelp);
    typeSelect.addEventListener('change', updateHelp);
    updateHelp();
</script>
@stop
