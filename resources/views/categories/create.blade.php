@extends('adminlte::page')

@section('title', 'เพิ่มหมวดหมู่')

@section('content_header')
    <h1>เพิ่มหมวดหมู่ใหม่</h1>
@stop

@section('content')
<div class="card">
    <form action="{{ route('categories.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label for="name">ชื่อหมวดหมู่ <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                @error('name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="description">รายละเอียด</label>
                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description') }}</textarea>
                @error('description')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> บันทึก</button>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left mr-1"></i> กลับ</a>
        </div>
    </form>
</div>
@stop
