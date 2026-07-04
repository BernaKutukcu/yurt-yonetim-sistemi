@extends('layouts.admin')

@section('title', 'Oda Düzenle')
@section('page-title', 'Oda Düzenle')

@section('styles')
    <style>
        .form-card{background:#fff;border-radius:6px;border:0.5px solid rgba(59,35,20,0.06);padding:28px;}
        .form-grid{display:grid;grid-template-columns:1fr 1fr;gap:20px;}
        .form-group{display:flex;flex-direction:column;gap:6px;}
        label{font-size:10px;color:rgba(59,35,20,0.4);letter-spacing:1.5px;text-transform:uppercase;}
        input{height:42px;border:0.5px solid rgba(59,35,20,0.12);border-radius:2px;padding:0 14px;font-family:'DM Sans',sans-serif;font-size:13px;color:#2c1a0e;background:#faf8f4;outline:none;}
        input:focus{border-color:rgba(59,35,20,0.3);}
        .btn-row{display:flex;gap:10px;margin-top:24px;}
        .btn-save{padding:11px 28px;background:#2c1a0e;color:#f5deb3;border:none;border-radius:2px;font-family:'DM Sans',sans-serif;font-size:12px;font-weight:500;letter-spacing:1px;cursor:pointer;}
        .btn-cancel{padding:11px 28px;background:transparent;color:rgba(59,35,20,0.4);border:0.5px solid rgba(59,35,20,0.12);border-radius:2px;font-family:'DM Sans',sans-serif;font-size:12px;cursor:pointer;text-decoration:none;}
        .errors{background:rgba(239,68,68,0.06);border:0.5px solid rgba(239,68,68,0.15);border-radius:4px;padding:12px 16px;margin-bottom:20px;}
        .errors p{font-size:12px;color:rgba(185,28,28,0.7);margin-bottom:4px;}
        select{height:42px;border:0.5px solid rgba(59,35,20,0.12);border-radius:2px;padding:0 14px;font-family:'DM Sans',sans-serif;font-size:13px;color:#2c1a0e;background:#faf8f4;outline:none;width:100%;}
        select:focus{border-color:rgba(59,35,20,0.3);}
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
        <form method="POST" action="{{ route('admin.rooms.update', $room->id) }}">
            @csrf
            @method('PUT')
            <div class="form-grid">
                <div class="form-group">
                    <label>Oda Numarası</label>
                    <input type="text" name="room_number" value="{{ $room->room_number }}" maxlength="3" oninput="this.value=this.value.replace(/[^0-9]/g,'')" required>
                </div>
                <div class="form-group">
                    <label>Blok</label>
                    <select name="block" required>
                        <option value="">— Blok Seç —</option>
                        <option value="A" {{ $room->block == 'A' ? 'selected' : '' }}>A</option>
                        <option value="B" {{ $room->block == 'B' ? 'selected' : '' }}>B</option>
                        <option value="C" {{ $room->block == 'C' ? 'selected' : '' }}>C</option>
                        <option value="D" {{ $room->block == 'D' ? 'selected' : '' }}>D</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Kat</label>
                    <input type="number" name="floor" value="{{ old('floor') }}" min="0" max="7" inputmode="numeric" onkeydown="return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab'].includes(event.key) || (event.key >= '0' && event.key <= '9')" oninput="if(this.value>7)this.value=7;" placeholder="1" required>
                </div>
                <div class="form-group">
                    <label>Kapasite</label>
                    <input type="number" name="capacity" value="{{ old('capacity') }}" min="1" max="4" inputmode="numeric" onkeydown="return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab'].includes(event.key) || (event.key >= '0' && event.key <= '9')" oninput="if(this.value>4)this.value=4;" placeholder="4" required>
                </div>
            </div>
            <div class="btn-row">
                <button type="submit" class="btn-save">Güncelle</button>
                <a href="{{ route('admin.rooms') }}" class="btn-cancel">İptal</a>
            </div>
        </form>
    </div>

@endsection
