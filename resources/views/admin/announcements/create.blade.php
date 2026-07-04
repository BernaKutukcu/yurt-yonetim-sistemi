@extends('layouts.admin')

@section('title', 'Duyuru Ekle')
@section('page-title', 'Duyuru Ekle')

@section('styles')
    <style>
        .form-card{background:#fff;border-radius:6px;border:0.5px solid rgba(59,35,20,0.06);padding:28px;}
        .form-group{display:flex;flex-direction:column;gap:6px;margin-bottom:20px;}
        label{font-size:10px;color:rgba(59,35,20,0.4);letter-spacing:1.5px;text-transform:uppercase;}
        input,select,textarea{border:0.5px solid rgba(59,35,20,0.12);border-radius:2px;padding:10px 14px;font-family:'DM Sans',sans-serif;font-size:13px;color:#2c1a0e;background:#faf8f4;outline:none;width:100%;}
        input,select{height:42px;}
        textarea{height:140px;resize:vertical;line-height:1.6;}
        input:focus,select:focus,textarea:focus{border-color:rgba(59,35,20,0.3);}
        .form-row{display:grid;grid-template-columns:1fr 1fr;gap:20px;}
        .btn-row{display:flex;gap:10px;margin-top:8px;}
        .btn-save{padding:11px 28px;background:#2c1a0e;color:#f5deb3;border:none;border-radius:2px;font-family:'DM Sans',sans-serif;font-size:12px;font-weight:500;letter-spacing:1px;cursor:pointer;}
        .btn-cancel{padding:11px 28px;background:transparent;color:rgba(59,35,20,0.4);border:0.5px solid rgba(59,35,20,0.12);border-radius:2px;font-family:'DM Sans',sans-serif;font-size:12px;cursor:pointer;text-decoration:none;}
        .errors{background:rgba(239,68,68,0.06);border:0.5px solid rgba(239,68,68,0.15);border-radius:4px;padding:12px 16px;margin-bottom:20px;}
        .errors p{font-size:12px;color:rgba(185,28,28,0.7);margin-bottom:4px;}
    </style>
@endsection

@section('content')

    @if($errors->any())
        <div class="errors">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="form-card">
        <form method="POST" action="{{ route('admin.announcements.store') }}">
            @csrf
            <div class="form-group">
                <label>Başlık</label>
                <input type="text" name="title" value="{{ old('title') }}" required>
            </div>
            <div class="form-group">
                <label>İçerik</label>
                <textarea name="content" required>{{ old('content') }}</textarea>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Hedef Kitle</label>
                    <select name="target" required>
                        <option value="all">Herkese</option>
                        <option value="student">Öğrencilere</option>
                        <option value="staff">Personele</option>
                        <option value="parent">Velilere</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Yayın Tarihi</label>
                    <input type="date" name="published_at" value="{{ old('published_at', date('Y-m-d')) }}" required>
                </div>
            </div>
            <div class="btn-row">
                <button type="submit" class="btn-save">Yayınla</button>
                <a href="{{ route('admin.announcements') }}" class="btn-cancel">İptal</a>
            </div>
        </form>
    </div>

@endsection
