@extends('layouts.admin')

@section('title', 'Giriş-Çıkış')
@section('page-title', 'Giriş-Çıkış Takibi')

@section('styles')
    <style>
        .page-grid{display:grid;grid-template-columns:340px 1fr;gap:20px;align-items:start;}
        .form-card{background:#fff;border-radius:6px;border:0.5px solid rgba(59,35,20,0.06);padding:24px;}
        .form-title{font-size:9px;color:rgba(59,35,20,0.25);letter-spacing:2px;text-transform:uppercase;margin-bottom:16px;}
        .form-group{margin-bottom:16px;}
        .form-label{display:block;font-size:10px;color:rgba(59,35,20,0.35);letter-spacing:1.5px;text-transform:uppercase;margin-bottom:7px;}
        .form-input,.form-select{width:100%;padding:10px 14px;border:0.5px solid rgba(59,35,20,0.12);border-radius:2px;font-family:'DM Sans',sans-serif;font-size:13px;color:#2c1a0e;background:#faf8f5;outline:none;}
        .form-input:focus,.form-select:focus{border-color:rgba(59,35,20,0.35);}
        .form-hint{font-size:11px;color:rgba(59,35,20,0.25);margin-top:5px;}
        .btn-submit{width:100%;padding:11px;background:#2c1a0e;color:#f5deb3;border:none;border-radius:2px;font-family:'DM Sans',sans-serif;font-size:12px;font-weight:500;letter-spacing:1px;cursor:pointer;margin-top:4px;}
        .logs{display:flex;flex-direction:column;gap:10px;}
        .log-card{background:#fff;border-radius:6px;border:0.5px solid rgba(59,35,20,0.06);padding:16px 20px;}
        .log-card.late{border-left:3px solid rgba(239,68,68,0.4);}
        .log-name{font-size:14px;font-weight:500;color:#2c1a0e;}
        .log-room{font-size:11px;color:rgba(59,35,20,0.3);margin-top:2px;}
        .log-times{display:flex;gap:20px;margin-top:10px;}
        .log-time-item{}
        .log-time-label{font-size:9px;color:rgba(59,35,20,0.25);letter-spacing:2px;text-transform:uppercase;margin-bottom:3px;}
        .log-time-val{font-size:13px;color:#2c1a0e;}
        .badge-late{display:inline-block;padding:3px 10px;border-radius:2px;font-size:10px;background:rgba(239,68,68,0.08);color:rgba(185,28,28,0.7);margin-left:8px;}
        .badge-ok{display:inline-block;padding:3px 10px;border-radius:2px;font-size:10px;background:rgba(34,197,94,0.1);color:rgba(21,128,61,0.8);margin-left:8px;}
        .entry-form{display:flex;gap:8px;margin-top:10px;padding-top:10px;border-top:0.5px solid rgba(59,35,20,0.05);}
        .entry-input{flex:1;padding:7px 12px;border:0.5px solid rgba(59,35,20,0.12);border-radius:2px;font-family:'DM Sans',sans-serif;font-size:12px;color:#2c1a0e;background:#faf8f5;outline:none;}
        .btn-entry{padding:7px 16px;background:#2c1a0e;color:#f5deb3;border:none;border-radius:2px;font-family:'DM Sans',sans-serif;font-size:11px;cursor:pointer;white-space:nowrap;}
        .success{background:rgba(34,197,94,0.1);color:rgba(21,128,61,0.8);padding:12px 18px;border-radius:4px;font-size:12px;margin-bottom:18px;}
        .error-msg{font-size:11px;color:rgba(185,28,28,0.7);margin-top:5px;}
        .empty{text-align:center;color:rgba(59,35,20,0.2);font-size:12px;padding:32px;background:#fff;border-radius:6px;}
        .count-text{font-size:13px;color:rgba(59,35,20,0.3);margin-bottom:14px;}
    </style>
@endsection

@section('content')

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    <div class="page-grid">

        {{-- Çıkış kaydı formu --}}
        <div class="form-card">
            <div class="form-title">Yeni Çıkış Kaydı</div>
            <form method="POST" action="{{ route('admin.entry-logs.store') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Öğrenci</label>
                    <select name="student_id" class="form-select" required>
                        <option value="">— Öğrenci Seç —</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}">{{ $student->user->name }} ({{ $student->room ? $student->room->room_number : 'Oda yok' }})</option>
                        @endforeach
                    </select>
                    @error('student_id')<div class="error-msg">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Çıkış Saati</label>
                    <input type="datetime-local" name="exit_time" class="form-input" required>
                    <div class="form-hint">En erken çıkış: 06:30</div>
                    @error('exit_time')<div class="error-msg">{{ $message }}</div>@enderror
                </div>
                <button type="submit" class="btn-submit">Çıkış Kaydı Oluştur</button>
            </form>
        </div>

        {{-- Log listesi --}}
        <div>
            @if($logs->isEmpty())
                <div class="empty">Henüz kayıt yok.</div>
            @else
                <div class="count-text">Toplam {{ $logs->count() }} kayıt</div>
                <div class="logs">
                    @foreach($logs as $log)
                        <div class="log-card {{ $log->is_late ? 'late' : '' }}">
                            <div style="display:flex;align-items:center;justify-content:space-between;">
                                <div>
                                    <div class="log-name">
                                        {{ $log->student->user->name }}
                                        @if($log->is_late)
                                            <span class="badge-late">Geç Geldi</span>
                                        @elseif($log->entry_time)
                                            <span class="badge-ok">Döndü</span>
                                        @endif
                                    </div>
                                    <div class="log-room">{{ $log->student->room ? $log->student->room->room_number . ' No\'lu Oda' : 'Oda atanmamış' }}</div>
                                </div>
                            </div>
                            <div class="log-times">
                                <div class="log-time-item">
                                    <div class="log-time-label">Çıkış</div>
                                    <div class="log-time-val">{{ $log->exit_time ? \Carbon\Carbon::parse($log->exit_time)->format('d.m.Y H:i') : '—' }}</div>
                                </div>
                                <div class="log-time-item">
                                    <div class="log-time-label">Giriş</div>
                                    <div class="log-time-val">{{ $log->entry_time ? \Carbon\Carbon::parse($log->entry_time)->format('d.m.Y H:i') : 'Henüz dönmedi' }}</div>
                                </div>
                            </div>
                            @if(!$log->entry_time)
                                <form method="POST" action="{{ route('admin.entry-logs.entry', $log->id) }}">
                                    @csrf
                                    @method('PATCH')
                                    <div class="entry-form">
                                        <input type="datetime-local" name="entry_time" class="entry-input" required>
                                        <button type="submit" class="btn-entry">Girişi Kaydet</button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>

@endsection
