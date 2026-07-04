@extends('layouts.admin')

@section('title', 'Temizlik Görevleri')
@section('page-title', 'Temizlik Görevleri')

@section('styles')
    <style>
        .page-grid{display:grid;grid-template-columns:320px 1fr;gap:20px;align-items:start;}
        .form-card{background:#fff;border-radius:6px;border:0.5px solid rgba(59,35,20,0.06);padding:24px;}
        .form-title{font-size:9px;color:rgba(59,35,20,0.25);letter-spacing:2px;text-transform:uppercase;margin-bottom:16px;}
        .form-group{margin-bottom:16px;}
        .form-label{display:block;font-size:10px;color:rgba(59,35,20,0.35);letter-spacing:1.5px;text-transform:uppercase;margin-bottom:7px;}
        .form-input,.form-select,.form-textarea{width:100%;padding:10px 14px;border:0.5px solid rgba(59,35,20,0.12);border-radius:2px;font-family:'DM Sans',sans-serif;font-size:13px;color:#2c1a0e;background:#faf8f5;outline:none;}
        .form-textarea{resize:vertical;min-height:70px;}
        .btn-submit{width:100%;padding:11px;background:#2c1a0e;color:#f5deb3;border:none;border-radius:2px;font-family:'DM Sans',sans-serif;font-size:12px;font-weight:500;letter-spacing:1px;cursor:pointer;margin-top:4px;}
        .tasks{display:flex;flex-direction:column;gap:10px;}
        .task-card{background:#fff;border-radius:6px;border:0.5px solid rgba(59,35,20,0.06);padding:16px 20px;display:flex;align-items:center;justify-content:space-between;}
        .task-card.done{opacity:0.5;}
        .task-name{font-size:14px;font-weight:500;color:#2c1a0e;}
        .task-location{font-size:12px;color:rgba(59,35,20,0.45);margin-top:3px;}
        .task-note{font-size:11px;color:rgba(59,35,20,0.3);margin-top:3px;}
        .task-date{font-family:'Fraunces',serif;font-size:15px;font-weight:700;color:#2c1a0e;text-align:right;}
        .task-day{font-size:10px;color:rgba(59,35,20,0.3);text-align:right;margin-top:2px;}
        .badge{display:inline-block;padding:3px 10px;border-radius:2px;font-size:10px;letter-spacing:0.5px;margin-top:6px;}
        .badge.pending{background:rgba(234,179,8,0.1);color:rgba(161,98,7,0.8);}
        .badge.done{background:rgba(34,197,94,0.1);color:rgba(21,128,61,0.8);}
        .btn-delete{padding:4px 10px;border-radius:2px;font-size:10px;font-family:'DM Sans',sans-serif;cursor:pointer;border:none;background:rgba(239,68,68,0.08);color:rgba(185,28,28,0.7);margin-top:6px;}
        .today-badge{display:inline-block;padding:2px 8px;background:rgba(59,35,20,0.08);color:rgba(59,35,20,0.5);border-radius:2px;font-size:9px;letter-spacing:1px;text-transform:uppercase;margin-left:6px;}
        .empty{text-align:center;color:rgba(59,35,20,0.2);font-size:12px;padding:32px;background:#fff;border-radius:6px;}
        .success{background:rgba(34,197,94,0.1);color:rgba(21,128,61,0.8);padding:12px 18px;border-radius:4px;font-size:12px;margin-bottom:18px;}
        .count-text{font-size:13px;color:rgba(59,35,20,0.3);margin-bottom:14px;}
        .error-msg{font-size:11px;color:rgba(185,28,28,0.7);margin-top:4px;}
    </style>
@endsection

@section('content')

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    <div class="page-grid">

        <div class="form-card">
            <div class="form-title">Yeni Görev Ekle</div>
            <form method="POST" action="{{ route('admin.cleaning.store') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Personel</label>
                    <select name="staff_id" class="form-select" required>
                        <option value="">— Personel Seç —</option>
                        @foreach($cleaningStaff as $s)
                            <option value="{{ $s->id }}">{{ $s->user->name }}</option>
                        @endforeach
                    </select>
                    @error('staff_id')<div class="error-msg">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Tarih</label>
                    <input type="date" name="task_date" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Konum</label>
                    <input type="text" name="location" class="form-input" placeholder="A Blok 1. Kat, 101 No'lu Oda...">
                    @error('location')<div class="error-msg">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Not (Opsiyonel)</label>
                    <textarea name="note" class="form-textarea" placeholder="Ek açıklama..."></textarea>
                </div>
                <button type="submit" class="btn-submit">Görev Ekle</button>
            </form>
        </div>

        <div>
            @if($tasks->isEmpty())
                <div class="empty">Henüz görev eklenmemiş.</div>
            @else
                <div class="count-text">Toplam {{ $tasks->count() }} görev</div>
                <div class="tasks">
                    @foreach($tasks as $task)
                        <div class="task-card {{ $task->status == 'done' ? 'done' : '' }}">
                            <div>
                                <div class="task-name">
                                    {{ $task->staff->user->name }}
                                    @if(\Carbon\Carbon::parse($task->task_date)->isToday())
                                        <span class="today-badge">Bugün</span>
                                    @endif
                                </div>
                                <div class="task-location">{{ $task->location }}</div>
                                @if($task->note)
                                    <div class="task-note">{{ $task->note }}</div>
                                @endif
                                <span class="badge {{ $task->status }}">{{ $task->status == 'done' ? 'Tamamlandı' : 'Bekliyor' }}</span>
                                <form method="POST" action="{{ route('admin.cleaning.destroy', $task->id) }}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete" onclick="return confirm('Görevi silmek istediğinize emin misiniz?')">Sil</button>
                                </form>
                            </div>
                            <div>
                                <div class="task-date">{{ \Carbon\Carbon::parse($task->task_date)->format('d.m.Y') }}</div>
                                <div class="task-day">{{ \Carbon\Carbon::parse($task->task_date)->locale('tr')->isoFormat('dddd') }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>

@endsection
