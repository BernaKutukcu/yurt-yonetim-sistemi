@extends('layouts.admin')

@section('title', 'Aylık Menü')
@section('page-title', 'Aylık Menü Planla')

@section('styles')
    <style>
        .month-select{display:flex;align-items:center;gap:12px;margin-bottom:24px;}
        .month-input{padding:10px 14px;border:0.5px solid rgba(59,35,20,0.12);border-radius:2px;font-family:'DM Sans',sans-serif;font-size:13px;color:#2c1a0e;background:#fff;outline:none;}
        .btn-go{padding:10px 20px;background:#2c1a0e;color:#f5deb3;border:none;border-radius:2px;font-family:'DM Sans',sans-serif;font-size:12px;cursor:pointer;}
        .days{display:flex;flex-direction:column;gap:8px;}
        .day-card{background:#fff;border-radius:6px;border:0.5px solid rgba(59,35,20,0.06);padding:16px 20px;}
        .day-header{display:flex;align-items:center;gap:12px;margin-bottom:12px;}
        .day-date{font-family:'Fraunces',serif;font-size:15px;font-weight:700;color:#2c1a0e;}
        .day-name{font-size:11px;color:rgba(59,35,20,0.3);letter-spacing:1px;}
        .day-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px;}
        .form-group{display:flex;flex-direction:column;gap:6px;}
        .form-label{font-size:9px;color:rgba(59,35,20,0.25);letter-spacing:2px;text-transform:uppercase;}
        .form-textarea{padding:8px 12px;border:0.5px solid rgba(59,35,20,0.12);border-radius:2px;font-family:'DM Sans',sans-serif;font-size:12px;color:#2c1a0e;background:#faf8f5;outline:none;resize:vertical;min-height:60px;line-height:1.5;}
        .form-textarea:focus{border-color:rgba(59,35,20,0.35);}
        .btn-save{padding:11px 28px;background:#2c1a0e;color:#f5deb3;border:none;border-radius:2px;font-family:'DM Sans',sans-serif;font-size:12px;font-weight:500;letter-spacing:1px;cursor:pointer;margin-top:20px;}
        .success{background:rgba(34,197,94,0.1);color:rgba(21,128,61,0.8);padding:12px 18px;border-radius:4px;font-size:12px;margin-bottom:18px;}
        .today-dot{width:6px;height:6px;border-radius:50%;background:#2c1a0e;display:inline-block;margin-left:6px;}
        .weekend{border-left:3px solid rgba(59,35,20,0.15);}
    </style>
@endsection

@section('content')

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    {{-- Ay seçici --}}
    <div class="month-select">
        <form method="GET" action="{{ route('admin.meals.monthly') }}" style="display:flex;gap:10px;align-items:center;">
            <input type="month" name="month" class="month-input" value="{{ $month }}">
            <button type="submit" class="btn-go">Göster</button>
        </form>
    </div>

    {{-- Günlük form --}}
    <form method="POST" action="{{ route('admin.meals.monthly.store') }}">
        @csrf
        <div class="days">
            @foreach($days as $day)
                @php
                    $carbon = \Carbon\Carbon::parse($day['date']);
                    $isWeekend = $carbon->isWeekend();
                    $isToday = $carbon->isToday();
                @endphp
                <div class="day-card {{ $isWeekend ? 'weekend' : '' }}">
                    <div class="day-header">
                        <div class="day-date">{{ $carbon->format('d') }}</div>
                        <div class="day-name">{{ $carbon->locale('tr')->isoFormat('dddd') }}</div>
                        @if($isToday)<span style="font-size:9px;background:rgba(59,35,20,0.08);color:rgba(59,35,20,0.5);padding:2px 8px;border-radius:2px;letter-spacing:1px;">BUGÜN</span>@endif
                    </div>
                    <div class="day-grid">
                        <div class="form-group">
                            <label class="form-label">Kahvaltı</label>
                            <textarea name="menus[{{ $day['date'] }}][breakfast]" class="form-textarea" placeholder="Kahvaltı menüsü...">{{ $day['breakfast'] }}</textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Akşam Yemeği</label>
                            <textarea name="menus[{{ $day['date'] }}][dinner]" class="form-textarea" placeholder="Akşam yemeği menüsü...">{{ $day['dinner'] }}</textarea>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <button type="submit" class="btn-save">Tümünü Kaydet</button>
    </form>

@endsection
