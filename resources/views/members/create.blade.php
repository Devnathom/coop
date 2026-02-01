@extends('adminlte::page')

@section('title', 'เพิ่มสมาชิก')

@section('content_header')
    <h1>เพิ่มสมาชิกใหม่</h1>
@stop

@section('content')
<div class="card">
    <form action="{{ route('members.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="member_code">รหัสสมาชิก <span class="text-danger">*</span></label>
                        <input type="text" name="member_code" id="member_code" class="form-control @error('member_code') is-invalid @enderror" value="{{ old('member_code', $memberCode) }}" required>
                        @error('member_code')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="name">ชื่อ-นามสกุล <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="class_level">ระดับชั้น</label>
                        <select name="class_level" id="class_level" class="form-control @error('class_level') is-invalid @enderror">
                            <option value="">-- เลือกระดับชั้น --</option>
                            @foreach(\App\Models\Member::$classLevels as $key => $value)
                                <option value="{{ $key }}" {{ old('class_level') == $key ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                        @error('class_level')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="room">ห้อง</label>
                        <input type="text" name="room" id="room" class="form-control @error('room') is-invalid @enderror" value="{{ old('room') }}" placeholder="เช่น 1, 2">
                        @error('room')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">อีเมล</label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="share_amount">จำนวนหุ้น (บาท)</label>
                        <input type="number" name="share_amount" id="share_amount" class="form-control @error('share_amount') is-invalid @enderror" value="{{ old('share_amount', 0) }}" min="0" step="0.01">
                        @error('share_amount')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> บันทึก</button>
            <a href="{{ route('members.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left mr-1"></i> กลับ</a>
        </div>
    </form>
</div>
@stop
