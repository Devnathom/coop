@extends('adminlte::page')

@section('title', 'แก้ไขสินค้า')

@section('content_header')
    <h1>แก้ไขสินค้า</h1>
@stop

@section('content')
<div class="card">
    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>รหัสสินค้า</label>
                        <input type="text" class="form-control" value="{{ $product->code }}" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="barcode">บาร์โค้ด</label>
                        <input type="text" name="barcode" id="barcode" class="form-control @error('barcode') is-invalid @enderror" value="{{ old('barcode', $product->barcode) }}">
                        @error('barcode')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">ชื่อสินค้า <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $product->name) }}" required>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="category_id">หมวดหมู่ <span class="text-danger">*</span></label>
                        <select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                            <option value="">-- เลือกหมวดหมู่ --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="cost_price">ราคาต้นทุน <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="cost_price" id="cost_price" class="form-control @error('cost_price') is-invalid @enderror" value="{{ old('cost_price', $product->cost_price) }}" step="0.01" min="0" required>
                            <div class="input-group-append">
                                <span class="input-group-text">บาท</span>
                            </div>
                        </div>
                        @error('cost_price')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="selling_price">ราคาขาย <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="selling_price" id="selling_price" class="form-control @error('selling_price') is-invalid @enderror" value="{{ old('selling_price', $product->selling_price) }}" step="0.01" min="0" required>
                            <div class="input-group-append">
                                <span class="input-group-text">บาท</span>
                            </div>
                        </div>
                        @error('selling_price')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="unit">หน่วย <span class="text-danger">*</span></label>
                        <input type="text" name="unit" id="unit" class="form-control @error('unit') is-invalid @enderror" value="{{ old('unit', $product->unit) }}" required>
                        @error('unit')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>จำนวนสต๊อกปัจจุบัน</label>
                        <input type="text" class="form-control" value="{{ $product->stock_quantity }} {{ $product->unit }}" readonly>
                        <small class="text-muted">ไปที่ "คลังสินค้า" เพื่อปรับปรุงสต๊อก</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="min_stock">จำนวนขั้นต่ำ (แจ้งเตือน) <span class="text-danger">*</span></label>
                        <input type="number" name="min_stock" id="min_stock" class="form-control @error('min_stock') is-invalid @enderror" value="{{ old('min_stock', $product->min_stock) }}" min="0" required>
                        @error('min_stock')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="image">รูปภาพ</label>
                        <div class="custom-file">
                            <input type="file" name="image" class="custom-file-input @error('image') is-invalid @enderror" id="image" accept="image/*">
                            <label class="custom-file-label" for="image">{{ $product->image ? basename($product->image) : 'เลือกไฟล์...' }}</label>
                        </div>
                        @error('image')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="custom-control custom-switch">
                    <input type="checkbox" name="is_active" class="custom-control-input" id="is_active" {{ $product->is_active ? 'checked' : '' }}>
                    <label class="custom-control-label" for="is_active">เปิดใช้งาน</label>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> บันทึก</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left mr-1"></i> กลับ</a>
        </div>
    </form>
</div>
@stop

@section('js')
<script>
    document.querySelector('.custom-file-input').addEventListener('change', function(e) {
        var fileName = document.getElementById("image").files[0].name;
        var nextSibling = e.target.nextElementSibling;
        nextSibling.innerText = fileName;
    });
</script>
@stop
