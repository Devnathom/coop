@extends('adminlte::page')

@section('title', 'แก้ไขสมาชิก')

@section('content_header')
    <h1>แก้ไขข้อมูลสมาชิก</h1>
@stop

@section('content')
<div class="card">
    <form action="{{ route('members.update', $member) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>รหัสสมาชิก</label>
                        <input type="text" class="form-control" value="{{ $member->member_code }}" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">ชื่อ-นามสกุล <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $member->name) }}" required>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="id_card">เลขบัตรประชาชน</label>
                        <input type="text" name="id_card" id="id_card" class="form-control @error('id_card') is-invalid @enderror" value="{{ old('id_card', $member->id_card) }}" maxlength="13">
                        @error('id_card')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="phone">เบอร์โทรศัพท์</label>
                        <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $member->phone) }}">
                        @error('phone')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">อีเมล</label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $member->email) }}">
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="share_amount">จำนวนหุ้น (บาท)</label>
                        <input type="number" name="share_amount" id="share_amount" class="form-control @error('share_amount') is-invalid @enderror" value="{{ old('share_amount', $member->share_amount) }}" min="0" step="0.01">
                        @error('share_amount')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="address">ที่อยู่</label>
                <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror" rows="3">{{ old('address', $member->address) }}</textarea>
                @error('address')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <div class="custom-control custom-switch">
                    <input type="checkbox" name="is_active" class="custom-control-input" id="is_active" {{ $member->is_active ? 'checked' : '' }}>
                    <label class="custom-control-label" for="is_active">เปิดใช้งาน</label>
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
