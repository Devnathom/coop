@extends('adminlte::page')

@section('title', 'สร้างปีบัญชี')

@section('content_header')
    <h1>สร้างปีบัญชีใหม่</h1>
@stop

@section('content')
<div class="card">
    <form action="{{ route('fiscal-years.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label for="name">ชื่อปีบัญชี <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', 'ปีบัญชี ' . date('Y')) }}" required>
                @error('name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="start_date">วันที่เริ่มต้น <span class="text-danger">*</span></label>
                        <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', date('Y') . '-01-01') }}" required>
                        @error('start_date')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="end_date">วันที่สิ้นสุด <span class="text-danger">*</span></label>
                        <input type="date" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', date('Y') . '-12-31') }}" required>
                        @error('end_date')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> บันทึก</button>
            <a href="{{ route('fiscal-years.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left mr-1"></i> กลับ</a>
        </div>
    </form>
</div>
@stop
