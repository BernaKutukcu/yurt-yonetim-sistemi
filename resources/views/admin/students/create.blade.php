@extends('layouts.admin')

@section('title', 'Öğrenci Ekle')
@section('page-title', 'Öğrenci Ekle')

@section('styles')
    <style>
        .form-card{background:#fff;border-radius:6px;border:0.5px solid rgba(59,35,20,0.06);padding:28px;}
        .form-grid{display:grid;grid-template-columns:1fr 1fr;gap:20px;}
        .form-group{display:flex;flex-direction:column;gap:6px;}
        .form-group.full{grid-column:span 2;}
        label{font-size:10px;color:rgba(59,35,20,0.4);letter-spacing:1.5px;text-transform:uppercase;}
        input,select{height:42px;border:0.5px solid rgba(59,35,20,0.12);border-radius:2px;padding:0 14px;font-family:'DM Sans',sans-serif;font-size:13px;color:#2c1a0e;background:#faf8f4;outline:none;}
        input:focus,select:focus{border-color:rgba(59,35,20,0.3);}
        .section-title{font-size:10px;color:rgba(59,35,20,0.3);letter-spacing:2px;text-transform:uppercase;margin:24px 0 16px;padding-bottom:8px;border-bottom:0.5px solid rgba(59,35,20,0.06);grid-column:span 2;}
        .btn-row{display:flex;gap:10px;margin-top:24px;}
        .btn-save{padding:11px 28px;background:#2c1a0e;color:#f5deb3;border:none;border-radius:2px;font-family:'DM Sans',sans-serif;font-size:12px;font-weight:500;letter-spacing:1px;cursor:pointer;}
        .btn-cancel{padding:11px 28px;background:transparent;color:rgba(59,35,20,0.4);border:0.5px solid rgba(59,35,20,0.12);border-radius:2px;font-family:'DM Sans',sans-serif;font-size:12px;cursor:pointer;text-decoration:none;}
        .errors{background:rgba(239,68,68,0.06);border:0.5px solid rgba(239,68,68,0.15);border-radius:4px;padding:12px 16px;margin-bottom:20px;}
        .errors p{font-size:12px;color:rgba(185,28,28,0.7);margin-bottom:4px;}
        .errors p:last-child{margin-bottom:0;}
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
        <form method="POST" action="{{ route('admin.students.store') }}">
            @csrf
            <div class="form-grid">

                <div class="section-title">Hesap Bilgileri</div>

                <div class="form-group">
                    <label>Ad Soyad</label>
                    <input type="text" name="name" value="{{ old('name') }}" required>
                </div>
                <div class="form-group">
                    <label>E-posta</label>
                    <input type="email" name="email" value="{{ old('email') }}" required>
                </div>

                <div class="section-title">Kişisel Bilgiler</div>

                <div class="form-group">
                    <label>TC Kimlik No</label>
                    <input type="text" name="tc_no" value="{{ old('tc_no') }}" maxlength="11" minlength="11" inputmode="numeric" placeholder="11 haneli TC no" oninput="this.value=this.value.replace(/[^0-9]/g,'')" required>
                </div>
                <div class="form-group">
                    <label>Telefon</label>
                    <input type="tel" name="phone" value="{{ old('phone', '05') }}" maxlength="11" inputmode="numeric" oninput="if(!this.value.startsWith('05'))this.value='05'+this.value.replace(/[^0-9]/g,'');else this.value='05'+this.value.slice(2).replace(/[^0-9]/g,'');" required>
                </div>
                <div class="form-group">
                    <label>Doğum Tarihi</label>
                    <input type="date" name="birth_date" value="{{ old('birth_date') }}" min="1900-01-01" max="{{ date('Y-m-d') }}" required>
                </div>
                <div class="form-group">
                    <label>Şehir</label>
                    <input type="text" name="city" value="{{ old('city') }}" required>
                </div>
                <div class="form-group full">
                    <label>Adres</label>
                    <input type="text" name="address" value="{{ old('address') }}" required>
                </div>
                <div class="form-group">
                    <label>Bölüm</label>
                    <input type="text" name="department" value="{{ old('department') }}" oninput="this.value=this.value.replace(/[^a-zA-ZğüşıöçĞÜŞİÖÇ\s]/g,'')" required>
                </div>
                <div class="form-group">
                    <label>IBAN</label>
                    <input type="text" name="iban" value="{{ old('iban', 'TR') }}" maxlength="26" style="text-transform:uppercase;" placeholder="TR00 0000 0000 0000 0000 0000 00" oninput="if(!this.value.startsWith('TR'))this.value='TR'+this.value.replace(/[^0-9]/g,'');else this.value='TR'+this.value.slice(2).replace(/[^0-9]/g,'');" required>
                </div>

                <div class="section-title">Veli Bilgileri</div>

                <div class="form-group">
                    <label>Anne Adı</label>
                    <input type="text" name="mother_name" value="{{ old('mother_name') }}" required>
                </div>
                <div class="form-group">
                    <label>Baba Adı</label>
                    <input type="text" name="father_name" value="{{ old('father_name') }}" required>
                </div>
                <div class="form-group">
                    <label>Veli Telefonu</label>
                    <input type="tel" name="parent_phone" value="{{ old('parent_phone', '05') }}" maxlength="11" inputmode="numeric" oninput="if(!this.value.startsWith('05'))this.value='05'+this.value.replace(/[^0-9]/g,'');else this.value='05'+this.value.slice(2).replace(/[^0-9]/g,'');" required>
                </div>

                <div class="section-title">Yurt Bilgileri</div>

                <div class="form-group">
                    <label>Kayıt Tarihi</label>
                    <input type="date" name="registration_date" value="{{ old('registration_date') }}" min="2000-01-01" max="{{ date('Y-m-d', strtotime('+1 year')) }}" required>
                </div>
                <div class="form-group">
                    <label>Oda (Opsiyonel)</label>
                    <select name="room_id" id="room_id" onchange="document.getElementById('bed_number').disabled = !this.value;">
                        <option value="">— Oda Seç —</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}">{{ $room->block }} Blok - {{ $room->room_number }} (Kat {{ $room->floor }})</option>
                        @endforeach
                    </select>
                    @error('room_id')<div class="error-msg" style="font-size:11px;color:rgba(185,28,28,0.7);margin-top:5px;">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label>Yatak No (Opsiyonel)</label>
                    <input type="number" name="bed_number" id="bed_number" value="{{ old('bed_number') }}" min="1" max="99" inputmode="numeric" onkeydown="return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab'].includes(event.key) || (event.key >= '0' && event.key <= '9')" disabled>
                    @error('bed_number')<div class="error-msg" style="font-size:11px;color:rgba(185,28,28,0.7);margin-top:5px;">{{ $message }}</div>@enderror
                </div>

            </div>

            <div class="btn-row">
                <button type="submit" class="btn-save">Kaydet</button>
                <a href="{{ route('admin.students') }}" class="btn-cancel">İptal</a>
            </div>
        </form>
    </div>

@endsection
