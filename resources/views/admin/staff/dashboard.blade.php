@extends('layouts.staff')

@section('title', 'Personel Paneli')
@section('page-title', 'Merhaba, ' . Auth::user()->name)

@section('styles')
    <style>
        .duty-card{background:#2c1a0e;border-radius:6px;padding:20px 22px;margin-bottom:24px;}
        .duty-title{font-size:9px;color:rgba(245,220,180,0.3);letter-spacing:2px;text-transform:uppercase;margin-bottom:10px;}
        .duty-info{font-family:'Fraunces',serif;font-size:18px;font-weight:700;color:#f5deb3;}
        .duty-sub{font-size:12px;color:rgba(245,220,180,0.4);margin-top:4px;}
        .no-duty{font-size:12px;color:rgba(245,220,180,0.2);text-align:center;padding:10px 0;}
        .grid2{display:grid;grid-template-columns:1fr 1fr;gap:14px;}
        .panel{background:#fff;border-radius:6px;border:0.5px solid rgba(59,35,20,0.06);padding:20px 22px;}
        .panel-title{font-size:9px;color:rgba(59,35,20,0.25);letter-spacing:2px;text-transform:uppercase;margin-bottom:14px;}
        .form-group{margin-bottom:14px;}
        .form-label{display:block;font-size:10px;color:rgba(59,35,20,0.35);letter-spacing:1.5px;text-transform:uppercase;margin-bottom:6px;}
        .form-select,.form-input{width:100%;padding:9px 12px;border:0.5px solid rgba(59,35,20,0.12);border-radius:2px;font-family:'DM Sans',sans-serif;font-size:13px;color:#2c1a0e;background:#faf8f5;outline:none;}
        .btn-submit{padding:9px 20px;background:#2c1a0e;color:#f5deb3;border:none;border-radius:2px;font-family:'DM Sans',sans-serif;font-size:12px;font-weight:500;letter-spacing:1px;cursor:pointer;margin-top:4px;}
        .log-row{display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:0.5px solid rgba(59,35,20,0.05);}
        .log-row:last-child{border-bottom:none;}
        .log-name{font-size:13px;color:#2c1a0e;font-weight:500;}
        .log-room{font-size:11px;color:rgba(59,35,20,0.3);margin-top:2px;}
        .log-exit{font-size:11px;color:rgba(59,35,20,0.4);margin-top:2px;}
        .entry-form{display:flex;gap:8px;margin-top:8px;}
        .entry-input{flex:1;padding:6px 10px;border:0.5px solid rgba(59,35,20,0.12);border-radius:2px;font-family:'DM Sans',sans-serif;font-size:12px;color:#2c1a0e;background:#faf8f5;outline:none;}
        .btn-entry{padding:6px 14px;background:#2c1a0e;color:#f5deb3;border:none;border-radius:2px;font-family:'DM Sans',sans-serif;font-size:11px;cursor:pointer;white-space:nowrap;}
        .task-row{display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:0.5px solid rgba(59,35,20,0.05);}
        .task-row:last-child{border-bottom:none;}
        .task-location{font-size:13px;color:#2c1a0e;font-weight:500;}
        .task-note{font-size:11px;color:rgba(59,35,20,0.35);margin-top:2px;}
        .btn-done{padding:5px 12px;background:rgba(34,197,94,0.1);color:rgba(21,128,61,0.8);border:none;border-radius:2px;font-family:'DM Sans',sans-serif;font-size:11px;cursor:pointer;}
        .badge-done{display:inline-block;padding:3px 10px;background:rgba(34,197,94,0.1);color:rgba(21,128,61,0.8);border-radius:2px;font-size:10px;}
        .empty{text-align:center;color:rgba(59,35,20,0.2);font-size:12px;padding:20px 0;}
        .success{background:rgba(34,197,94,0.1);color:rgba(21,128,61,0.8);padding:12px 18px;border-radius:4px;font-size:12px;margin-bottom:18px;}
        .error-msg{font-size:11px;color:rgba(185,28,28,0.7);margin-top:4px;}
        .hint{font-size:11px;color:rgba(59,35,20,0.25);margin-top:4px;}
    </style>
@endsection

@section('content')

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    {{-- Bugünkü nöbet --}}
    <div class="duty-card">
        <div class="duty-title">Bugünkü Nöbet</div>
        @if($todayDuty)
            <div class="duty-info">{{ substr($todayDuty->start_time, 0, 5) }} – {{ substr($todayDuty->end_time, 0, 5) }}</div>
            <div class="duty-sub">{{ $todayDuty->location ?? 'Konum belirtilmemiş' }}</div>
        @else
            <div class="no-duty">Bugün için nöbet kaydı yok.</div>
        @endif
    </div>

    {{-- Güvenlik personeli --}}
    @if($staff->department === 'security')
        <div class="grid2">
            <div class="panel">
                <div class="panel-title">Yeni Çıkış Kaydı</div>
                <form method="POST" action="{{ route('staff.entry-logs.store') }}">
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
                        <div class="hint">En erken çıkış: 06:30</div>
                        @error('exit_time')<div class="error-msg">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn-submit">Kaydet</button>
                </form>
            </div>
            <div class="panel">
                <div class="panel-title">Dışarıdaki Öğrenciler ({{ $activeLogs->count() }})</div>
                @if($activeLogs->isEmpty())
                    <div class="empty">Dışarıda öğrenci yok.</div>
                @else
                    @foreach($activeLogs as $log)
                        <div class="log-row">
                            <div>
                                <div class="log-name">{{ $log->student->user->name }}</div>
                                <div class="log-room">{{ $log->student->room ? $log->student->room->room_number . ' No\'lu Oda' : '' }}</div>
                                <div class="log-exit">Çıkış: {{ \Carbon\Carbon::parse($log->exit_time)->format('H:i') }}</div>
                            </div>
                            <form method="POST" action="{{ route('staff.entry-logs.update', $log->id) }}">
                                @csrf
                                @method('PATCH')
                                <div class="entry-form">
                                    <input type="time" name="entry_time" class="entry-input" required>
                                    <button type="submit" class="btn-entry">Giriş</button>
                                </div>
                            </form>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        {{-- Yemekhane personeli --}}
    @elseif($staff->department === 'kitchen')
        <div style="background:#fff;border-radius:6px;border:0.5px solid rgba(59,35,20,0.06);overflow:auto;">
            <div style="padding:16px 20px;border-bottom:0.5px solid rgba(59,35,20,0.06);font-size:11px;color:rgba(59,35,20,0.4);letter-spacing:2px;text-transform:uppercase;">
                {{ now()->locale('tr')->isoFormat('MMMM YYYY') }} Menüsü
            </div>
            @php
                $weeks = [];
                $currentWeek = [];
                $daysInMonth = cal_days_in_month(CAL_GREGORIAN, now()->month, now()->year);
                for ($i = 1; $i <= $daysInMonth; $i++) {
                    $date = now()->format('Y-m') . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);
                    $carbon = \Carbon\Carbon::parse($date);
                    $currentWeek[] = ['date' => $date, 'carbon' => $carbon];
                    if ($carbon->dayOfWeek === 0 || $i === $daysInMonth) {
                        $weeks[] = $currentWeek;
                        $currentWeek = [];
                    }
                }
            @endphp

            @foreach($weeks as $week)
                <table style="width:100%;border-collapse:collapse;">
                    <tr>
                        @foreach($week as $day)
                            <td style="width:{{ 100/count($week) }}%;padding:10px 12px;border:0.5px solid rgba(59,35,20,0.06);vertical-align:top;min-width:120px;">
                                <div style="font-family:'Fraunces',serif;font-size:15px;font-weight:700;color:{{ $day['carbon']->isToday() ? '#2c1a0e' : 'rgba(59,35,20,0.4)' }};">
                                    {{ $day['carbon']->format('d') }}
                                    @if($day['carbon']->isToday())
                                        <span style="font-family:'DM Sans',sans-serif;font-size:9px;background:rgba(59,35,20,0.08);padding:2px 6px;border-radius:2px;letter-spacing:1px;font-weight:400;">BUGÜN</span>
                                    @endif
                                </div>
                                <div style="font-size:10px;color:rgba(59,35,20,0.3);margin-bottom:8px;">{{ $day['carbon']->locale('tr')->isoFormat('ddd') }}</div>
                                @if(isset($monthlyMenus[$day['date']]))
                                    <div style="font-size:9px;color:rgba(59,35,20,0.25);letter-spacing:1px;text-transform:uppercase;margin-bottom:3px;">Kahvaltı</div>
                                    <div style="font-size:11px;color:rgba(59,35,20,0.6);line-height:1.5;margin-bottom:8px;">{{ $monthlyMenus[$day['date']]->breakfast }}</div>
                                    <div style="font-size:9px;color:rgba(59,35,20,0.25);letter-spacing:1px;text-transform:uppercase;margin-bottom:3px;">Akşam</div>
                                    <div style="font-size:11px;color:rgba(59,35,20,0.6);line-height:1.5;">{{ $monthlyMenus[$day['date']]->dinner }}</div>
                                @else
                                    <div style="font-size:11px;color:rgba(59,35,20,0.15);font-style:italic;">Menü girilmemiş</div>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                </table>
            @endforeach
        </div>

        {{-- Temizlik personeli --}}
    @elseif($staff->department === 'cleaning')
        <div class="panel">
            <div class="panel-title">Bugünkü Görevlerim ({{ $cleaningTasks->count() }})</div>
            @if($cleaningTasks->isEmpty())
                <div class="empty">Bugün için görev yok.</div>
            @else
                @foreach($cleaningTasks as $task)
                    <div class="task-row">
                        <div>
                            <div class="task-location">{{ $task->location }}</div>
                            @if($task->note)
                                <div class="task-note">{{ $task->note }}</div>
                            @endif
                        </div>
                        @if($task->status === 'done')
                            <span class="badge-done">Tamamlandı</span>
                        @else
                            <form method="POST" action="{{ route('staff.cleaning.done', $task->id) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn-done">Tamamlandı</button>
                            </form>
                        @endif
                    </div>
                @endforeach
            @endif
        </div>
    @endif

@endsection
