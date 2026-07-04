@extends('layouts.admin')

@section('title', 'Nöbet Düzenle')
@section('page-title', 'Nöbet Düzenle')

@section('styles')
    <style>
        .form-card{background:#fff;border-radius:6px;border:0.5px solid rgba(59,35,20,0.06);padding:28px;max-width:560px;}
        .form-group{margin-bottom:16px;}
        .form-label{display:block;font-size:10px;color:rgba(59,35,20,0.35);letter-spacing:1.5px;text-transform:uppercase;margin-bottom:7px;}
        .form-input,.form-select{width:100%;padding:10px 14px;border:0.5px solid rgba(59,35,20,0.12);border-radius:2px;font-family:'DM Sans',sans-serif;font-size:13px;color:#2c1a0e;background:#faf8f5;outline:none;}
        .form-input:focus,.form-select:focus{border-color:rgba(59,35,20,0.35);}
        .btn-row{display:flex;gap:10px;margin-top:24px;}
        .btn-save{padding:11px 28px;background:#2c1a0e;color:#f5deb3;border:none;border-radius:2px;font-family:'DM Sans',sans-serif;font-size:12px;font-weight:500;letter-spacing:1px;cursor:pointer;}
        .btn-cancel{padding:11px 28px;background:transparent;color:rgba(59,35,20,0.4);border:0.5px solid rgba(59,35,20,0.12);border-radius:2px;font-family:'DM Sans',sans-serif;font-size:12px;cursor:pointer;text-decoration:none;}
    </style>
@endsection

@section('content')

    <div class="form-card">
        <form method="POST" action="{{ route('admin.duty.update', $duty->id) }}">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="form-label">Personel</label>
                <select name="staff_id" class="form-select" required>
                    @foreach($staff as $s)
                        <option value="{{ $s->id }}" {{ $duty->staff_id == $s->id ? 'selected' : '' }}>{{ $s->user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Tarih</label>
                <input type="date" name="duty_date" class="form-input" value="{{ $duty->duty_date }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Başlangıç Saati</label>
                <input type="time" name="start_time" class="form-input" value="{{ substr($duty->start_time, 0, 5) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Bitiş Saati</label>
                <input type="time" name="end_time" class="form-input" value="{{ substr($duty->end_time, 0, 5) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Konum (Opsiyonel)</label>
                <input type="text" name="location" class="form-input" value="{{ $duty->location }}">
            </div>
            <div class="btn-row">
                <button type="submit" class="btn-save">Güncelle</button>
                <a href="{{ route('admin.duty') }}" class="btn-cancel">İptal</a>
            </div>
        </form>
    </div>

@endsection
