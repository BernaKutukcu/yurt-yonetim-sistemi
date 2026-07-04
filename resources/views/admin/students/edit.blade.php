@extends('layouts.admin')

@section('title', 'Öğrenci Düzenle')
@section('page-title', 'Öğrenci Düzenle')

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
        <form method="POST" action="{{ route('admin.students.update', $student->id) }}">
            @csrf
            @method('PUT')
            <div class="form-grid">

                <div class="section-title">Hesap Bilgileri</div>

                <div class="form-group">
                    <label>Ad Soyad</label>
                    <input type="text" name="name" value="{{ $student->user->name }}" required>
                </div>
                <div class="form-group">
                    <label>E-posta</label>
                    <input type="email" name="email" value="{{ $student->user->email }}" required>
                </div>

                <div class="section-title">Kişisel Bilgiler</div>

                <div class="form-group">
                    <label>TC Kimlik No</label>
                    <input type="text" name="tc_no" value="{{ $student->tc_no }}" maxlength="11" minlength="11" inputmode="numeric" oninput="this.value=this.value.replace(/[^0-9]/g,'')" required>
                </div>
                <div class="form-group">
                    <label>Telefon</label>
                    <input type="tel" name="phone" value="{{ $student->phone }}" maxlength="11" inputmode="numeric" oninput="this.value=this.value.replace(/[^0-9]/g,'')" required>
                </div>
                <div class="form-group">
                    <label>Doğum Tarihi</label>
                    <input type="date" name="birth_date" value="{{ $student->birth_date }}" required>
                </div>
                <div class="form-group">
                    <label>Şehir</label>
                    <input type="text" name="city" value="{{ $student->city }}" required>
                </div>
                <div class="form-group full">
                    <label>Adres</label>
                    <input type="text" name="address" value="{{ $student->address }}" required>
                </div>
                <div class="form-group">
                    <label>Bölüm</label>
                    <input type="text" name="department" value="{{ $student->department }}" required>
                </div>
                <div class="form-group">
                    <label>IBAN</label>
                    <input type="text" name="iban" value="{{ $student->iban }}" maxlength="34" style="text-transform:uppercase;" required>
                </div>

                <div class="section-title">Veli Bilgileri</div>

                <div class="form-group">
                    <label>Anne Adı</label>
                    <input type="text" name="mother_name" value="{{ $student->mother_name }}" required>
                </div>
                <div class="form-group">
                    <label>Baba Adı</label>
                    <input type="text" name="father_name" value="{{ $student->father_name }}" required>
                </div>
                <div class="form-group">
                    <label>Veli Telefonu</label>
                    <input type="tel" name="parent_phone" value="{{ $student->parent_phone }}" maxlength="11" inputmode="numeric" oninput="this.value=this.value.replace(/[^0-9]/g,'')" required>
                </div>

                <div class="section-title">Yurt Bilgileri</div>

                <div class="form-group">
                    <label>Kayıt Tarihi</label>
                    <input type="date" name="registration_date" value="{{ $student->registration_date }}" required>
                </div>
                <div class="form-group">
                    <label>Oda</label>
                    <select name="room_id" id="edit_room_id" onchange="document.getElementById('edit_bed_number').disabled = !this.value;">
                        <option value="">— Oda Seç —</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}" {{ $student->room_id == $room->id ? 'selected' : '' }}>
                                {{ $room->room_number }} ({{ $room->block }} - Kat {{ $room->floor }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Yatak No</label>
                    <input type="number" name="bed_number" id="edit_bed_number" value="{{ $student->bed_number }}" min="1" max="99" inputmode="numeric" onkeydown="return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab'].includes(event.key) || (event.key >= '0' && event.key <= '9')" {{ !$student->room_id ? 'disabled' : '' }}>
                </div>

            </div>

            <div class="btn-row">
                <button type="submit" class="btn-save">Güncelle</button>
                <a href="{{ route('admin.students') }}" class="btn-cancel">İptal</a>
            </div>
        </form>
    </div>

@endsection
