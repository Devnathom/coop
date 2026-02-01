@extends('adminlte::page')

@section('title', 'แก้ไขหมวดหมู่')

@section('content_header')
    <h1>แก้ไขหมวดหมู่</h1>
@stop

@section('content')
<div class="card">
    <form action="{{ route('categories.update', $category) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="form-group">
                <label for="name">ชื่อหมวดหมู่ <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $category->name) }}" required>
                @error('name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="description">รายละเอียด</label>
                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $category->description) }}</textarea>
                @error('description')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <div class="custom-control custom-switch">
                    <input type="checkbox" name="is_active" class="custom-control-input" id="is_active" {{ $category->is_active ? 'checked' : '' }}>
                    <label class="custom-control-label" for="is_active">เปิดใช้งาน</label>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> บันทึก</button>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left mr-1"></i> กลับ</a>
        </div>
    </form>
</div>
@stop
