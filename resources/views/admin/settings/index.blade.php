@extends('layouts.admin')

@section('title', 'Ayarlar')
@section('page-title', 'Ayarlar')

@section('styles')
    <style>
        .profile-card{background:#2c1a0e;border-radius:6px;padding:32px 28px;text-align:center;margin-bottom:20px;}
        .profile-avatar{width:72px;height:72px;border-radius:50%;background:rgba(245,220,180,0.1);display:flex;align-items:center;justify-content:center;font-family:'Fraunces',serif;font-size:28px;font-weight:700;color:#f5deb3;margin:0 auto 16px;}
        .profile-name{font-family:'Fraunces',serif;font-size:22px;font-weight:700;color:#f5deb3;}
        .profile-role{font-size:10px;color:rgba(245,220,180,0.3);letter-spacing:2px;text-transform:uppercase;margin-top:4px;}
        .profile-email{font-size:12px;color:rgba(245,220,180,0.3);margin-top:6px;}
        .profile-phone{font-size:12px;color:rgba(245,220,180,0.3);margin-top:2px;}
        .profile-edit-btn{margin-top:16px;padding:8px 20px;background:rgba(245,220,180,0.08);color:rgba(245,220,180,0.5);border:0.5px solid rgba(245,220,180,0.1);border-radius:2px;font-family:'DM Sans',sans-serif;font-size:11px;cursor:pointer;letter-spacing:1px;}
        .accordion{display:flex;flex-direction:column;gap:8px;}
        .acc-item{background:#fff;border-radius:6px;border:0.5px solid rgba(59,35,20,0.06);overflow:hidden;}
        .acc-header{display:flex;align-items:center;justify-content:space-between;padding:16px 22px;cursor:pointer;user-select:none;}
        .acc-title{font-size:12px;color:rgba(59,35,20,0.6);font-weight:500;}
        .acc-icon{font-size:18px;color:rgba(59,35,20,0.3);transition:transform 0.2s;}
        .acc-item.open .acc-icon{transform:rotate(180deg);}
        .acc-body{display:none;padding:0 22px 20px;}
        .acc-item.open .acc-body{display:block;}
        .form-group{margin-bottom:16px;}
        .form-label{display:block;font-size:10px;color:rgba(59,35,20,0.35);letter-spacing:1.5px;text-transform:uppercase;margin-bottom:7px;}
        .form-input{width:100%;padding:10px 14px;border:0.5px solid rgba(59,35,20,0.12);border-radius:2px;font-family:'DM Sans',sans-serif;font-size:13px;color:#2c1a0e;background:#faf8f5;outline:none;}
        .form-input:focus{border-color:rgba(59,35,20,0.35);}
        .form-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;}
        .btn-submit{padding:10px 24px;background:#2c1a0e;color:#f5deb3;border:none;border-radius:2px;font-family:'DM Sans',sans-serif;font-size:12px;font-weight:500;letter-spacing:1px;cursor:pointer;margin-top:4px;}
        .form-hint{font-size:11px;color:rgba(59,35,20,0.25);margin-top:4px;}
        .error-msg{font-size:11px;color:rgba(185,28,28,0.7);margin-top:4px;}
        .success{background:rgba(34,197,94,0.1);color:rgba(21,128,61,0.8);padding:12px 18px;border-radius:4px;font-size:12px;margin-bottom:18px;}
        .modal-overlay{display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.4);z-index:100;align-items:center;justify-content:center;}
        .modal-box{background:#fff;border-radius:6px;padding:28px;width:420px;}
        .modal-title{font-size:9px;color:rgba(59,35,20,0.25);letter-spacing:2px;text-transform:uppercase;margin-bottom:18px;}
        .modal-actions{display:flex;gap:10px;margin-top:20px;}
        .btn-cancel{padding:10px 20px;background:transparent;color:rgba(59,35,20,0.4);border:0.5px solid rgba(59,35,20,0.12);border-radius:2px;font-family:'DM Sans',sans-serif;font-size:12px;cursor:pointer;}
    </style>
@endsection

@section('content')

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    {{-- Profil kartı --}}
    <div class="profile-card">
        <div class="profile-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
        <div class="profile-name">{{ Auth::user()->name }}</div>
        <div class="profile-role">Yurt Yöneticisi</div>
        <div class="profile-email">{{ Auth::user()->email }}</div>
        @if(Auth::user()->phone)
            <div class="profile-phone">{{ Auth::user()->phone }}</div>
        @endif
        <button class="profile-edit-btn" onclick="document.getElementById('profile-modal').style.display='flex'">Düzenle</button>
    </div>

    {{-- Accordion --}}
    <div class="accordion">

        {{-- Yurt Bilgileri --}}
        <div class="acc-item" id="acc-dorm">
            <div class="acc-header" onclick="toggleAcc('dorm')">
                <span class="acc-title">Yurt Bilgileri</span>
                <span class="acc-icon">⌄</span>
            </div>
            <div class="acc-body">
                <form method="POST" action="{{ route('admin.settings.update') }}">
                    @csrf
                    <input type="hidden" name="exit_time" value="{{ session('exit_time', '06:30') }}">
                    <input type="hidden" name="entry_time" value="{{ session('entry_time', '23:00') }}">
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Yurt Adı</label>
                            <input type="text" name="dorm_name" class="form-input" value="{{ session('dorm_name', '') }}" oninput="this.value=this.value.replace(/[^a-zA-ZğüşıöçĞÜŞİÖÇ\s]/g,'')">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Telefon</label>
                            <input type="tel" name="dorm_phone" class="form-input" value="{{ session('dorm_phone', '') }}" maxlength="11" inputmode="numeric" oninput="if(!this.value.startsWith('0'))this.value='0'+this.value.replace(/[^0-9]/g,'');else this.value='0'+this.value.slice(1).replace(/[^0-9]/g,'');" placeholder="0312 000 00 00">
                        </div>
                        <div class="form-group" style="grid-column:span 2;">
                            <label class="form-label">Adres</label>
                            <input type="text" name="dorm_address" class="form-input" value="{{ session('dorm_address', '') }}" placeholder="Yurt adresi...">
                        </div>
                    </div>
                    <button type="submit" class="btn-submit">Kaydet</button>
                </form>
            </div>
        </div>

        {{-- Giriş-Çıkış Saatleri --}}
        <div class="acc-item" id="acc-hours">
            <div class="acc-header" onclick="toggleAcc('hours')">
                <span class="acc-title">Giriş-Çıkış Saatleri</span>
                <span class="acc-icon">⌄</span>
            </div>
            <div class="acc-body">
                <form method="POST" action="{{ route('admin.settings.update') }}">
                    @csrf
                    <input type="hidden" name="dorm_name" value="{{ session('dorm_name', '') }}">
                    <input type="hidden" name="dorm_address" value="{{ session('dorm_address', '') }}">
                    <input type="hidden" name="dorm_phone" value="{{ session('dorm_phone', '') }}">
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">En Erken Çıkış</label>
                            <input type="time" name="exit_time" class="form-input" value="{{ session('exit_time', '06:30') }}">
                            <div class="form-hint">Öğrenciler bu saatten önce çıkış yapamaz.</div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Son Giriş</label>
                            <input type="time" name="entry_time" class="form-input" value="{{ session('entry_time', '23:00') }}">
                            <div class="form-hint">Bu saatten sonra giriş yapanlar geç sayılır.</div>
                        </div>
                    </div>
                    <button type="submit" class="btn-submit">Kaydet</button>
                </form>
            </div>
        </div>

        {{-- Şifre Değiştir --}}
        <div class="acc-item" id="acc-password">
            <div class="acc-header" onclick="toggleAcc('password')">
                <span class="acc-title">Şifre Değiştir</span>
                <span class="acc-icon">⌄</span>
            </div>
            <div class="acc-body">
                <form method="POST" action="{{ route('admin.settings.password') }}">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Mevcut Şifre</label>
                        <input type="password" name="current_password" class="form-input">
                        @error('current_password')<div class="error-msg">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Yeni Şifre</label>
                        <input type="password" name="new_password" class="form-input">
                        @error('new_password')<div class="error-msg">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Yeni Şifre Tekrar</label>
                        <input type="password" name="new_password_confirmation" class="form-input">
                    </div>
                    <button type="submit" class="btn-submit">Şifreyi Güncelle</button>
                </form>
            </div>
        </div>

    </div>

    {{-- Profil modal --}}
    <div id="profile-modal" class="modal-overlay">
        <div class="modal-box">
            <div class="modal-title">Profil Düzenle</div>
            <form method="POST" action="{{ route('admin.settings.profile') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Ad Soyad</label>
                    <input type="text" name="name" class="form-input" value="{{ Auth::user()->name }}" oninput="this.value=this.value.replace(/[^a-zA-ZğüşıöçĞÜŞİÖÇ\s]/g,'')" required>
                    @error('name')<div class="error-msg">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">E-posta</label>
                    <input type="email" name="email" class="form-input" value="{{ Auth::user()->email }}" required>
                    @error('email')<div class="error-msg">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Telefon</label>
                    <input type="tel" name="phone" class="form-input" value="{{ Auth::user()->phone }}" maxlength="11" inputmode="numeric" oninput="if(!this.value.startsWith('05'))this.value='05'+this.value.replace(/[^0-9]/g,'');else this.value='05'+this.value.slice(2).replace(/[^0-9]/g,'');" placeholder="05xxxxxxxxx">
                </div>
                <div class="modal-actions">
                    <button type="submit" class="btn-submit">Kaydet</button>
                    <button type="button" class="btn-cancel" onclick="document.getElementById('profile-modal').style.display='none'">İptal</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        function toggleAcc(id) {
            const item = document.getElementById('acc-' + id);
            item.classList.toggle('open');
        }
    </script>
@endsection
