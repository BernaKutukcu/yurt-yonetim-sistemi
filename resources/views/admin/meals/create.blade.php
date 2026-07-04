@extends('layouts.admin')

@section('title', 'Menü Ekle')
@section('page-title', 'Yeni Menü')

@section('styles')
    <style>
        .form-card{background:#fff;border-radius:6px;border:0.5px solid rgba(59,35,20,0.06);padding:28px 30px;max-width:560px;}
        .form-group{margin-bottom:20px;}
        .form-label{display:block;font-size:10px;color:rgba(59,35,20,0.35);letter-spacing:1.5px;text-transform:uppercase;margin-bottom:8px;}
        .form-input,.form-textarea{width:100%;padding:10px 14px;border:0.5px solid rgba(59,35,20,0.12);border-radius:2px;font-family:'DM Sans',sans-serif;font-size:13px;color:#2c1a0e;background:#faf8f5;outline:none;}
        .form-input:focus,.form-textarea:focus{border-color:rgba(59,35,20,0.35);background:#fff;}
        .form-textarea{resize:vertical;min-height:90px;line-height:1.6;}
        .form-hint{font-size:11px;color:rgba(59,35,20,0.25);margin-top:5px;}
        .form-actions{display:flex;align-items:center;gap:12px;margin-top:24px;}
        .btn-submit{padding:10px 24px;background:#2c1a0e;color:#f5deb3;border:none;border-radius:2px;font-family:'DM Sans',sans-serif;font-size:12px;font-weight:500;letter-spacing:1px;cursor:pointer;}
        .btn-cancel{padding:10px 20px;background:transparent;color:rgba(59,35,20,0.4);border:0.5px solid rgba(59,35,20,0.12);border-radius:2px;font-family:'DM Sans',sans-serif;font-size:12px;cursor:pointer;text-decoration:none;}
        .error-msg{font-size:11px;color:rgba(185,28,28,0.7);margin-top:5px;}
    </style>
@endsection

@section('content')

    <div class="form-card">
        <form method="POST" action="{{ route('admin.meals.store') }}">
            @csrf

            <div class="form-group">
                <label class="form-label">Tarih</label>
                <input type="date" name="date" class="form-input" value="{{ old('date') }}" required>
                <div class="form-hint">Aynı tarihe tekrar eklersen üzerine yazar.</div>
                @error('date')<div class="error-msg">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Kahvaltı</label>
                <textarea name="breakfast" class="form-textarea" placeholder="Örn: Peynir, zeytin, domates, salatalık, yumurta, çay" required>{{ old('breakfast') }}</textarea>
                @error('breakfast')<div class="error-msg">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Akşam Yemeği</label>
                <textarea name="dinner" class="form-textarea" placeholder="Örn: Mercimek çorbası, tavuk ızgara, pilav, ayran" required>{{ old('dinner') }}</textarea>
                @error('dinner')<div class="error-msg">{{ $message }}</div>@enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">Kaydet</button>
                <a href="{{ route('admin.meals') }}" class="btn-cancel">İptal</a>
            </div>
        </form>
    </div>

@endsection
