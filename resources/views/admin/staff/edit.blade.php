@extends('layouts.admin')

@section('title', 'Personel Düzenle')
@section('page-title', 'Personel Düzenle')

@section('styles')
    <style>
        .form-card{background:#fff;border-radius:6px;border:0.5px solid rgba(59,35,20,0.06);padding:28px;}
        .form-grid{display:grid;grid-template-columns:1fr 1fr;gap:20px;}
        .form-group{display:flex;flex-direction:column;gap:6px;}
        label{font-size:10px;color:rgba(59,35,20,0.4);letter-spacing:1.5px;text-transform:uppercase;}
        input,select{height:42px;border:0.5px solid rgba(59,35,20,0.12);border-radius:2px;padding:0 14px;font-family:'DM Sans',sans-serif;font-size:13px;color:#2c1a0e;background:#faf8f4;outline:none;}
        input:focus,select:focus{border-color:rgba(59,35,20,0.3);}
        .btn-row{display:flex;gap:10px;margin-top:24px;}
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
        <form method="POST" action="{{ route('admin.staff.update', $staff->id) }}">
            @csrf
            @method('PUT')
            <div class="form-grid">
                <div class="form-group">
                    <label>Ad Soyad</label>
                    <input type="text" name="name" value="{{ $staff->user->name }}" oninput="this.value=this.value.replace(/[^a-zA-ZğüşıöçĞÜŞİÖÇ\s]/g,'')" required>
                </div>
                <div class="form-group">
                    <label>E-posta</label>
                    <input type="email" name="email" value="{{ $staff->user->email }}" required>
                </div>
                <div class="form-group">
                    <label>TC Kimlik No</label>
                    <input type="text" name="tc_no" value="{{ $staff->tc_no }}" maxlength="11" inputmode="numeric" oninput="this.value=this.value.replace(/[^0-9]/g,'')" required>
                </div>
                <div class="form-group">
                    <label>Telefon</label>
                    <input type="tel" name="phone" value="{{ $staff->phone }}" maxlength="11" inputmode="numeric" oninput="if(!this.value.startsWith('05'))this.value='05'+this.value.replace(/[^0-9]/g,'');else this.value='05'+this.value.slice(2).replace(/[^0-9]/g,'');" required>
                </div>
                <div class="form-group">
                    <label>Departman</label>
                    <select name="department" required>
                        <option value="security" {{ $staff->department == 'security' ? 'selected' : '' }}>Güvenlik</option>
                        <option value="kitchen" {{ $staff->department == 'kitchen' ? 'selected' : '' }}>Yemekhane</option>
                        <option value="cleaning" {{ $staff->department == 'cleaning' ? 'selected' : '' }}>Temizlik</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>İşe Başlama Tarihi</label>
                    <input type="date" name="start_date" value="{{ $staff->start_date }}" required>
                </div>
            </div>
            <div class="btn-row">
                <button type="submit" class="btn-save">Güncelle</button>
                <a href="{{ route('admin.staff') }}" class="btn-cancel">İptal</a>
            </div>
        </form>
    </div>

@endsection
