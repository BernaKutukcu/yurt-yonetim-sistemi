@extends('layouts.admin')

@section('title', 'Nöbet Takvimi')
@section('page-title', 'Nöbet Takvimi')

@section('styles')
    <style>
        .page-grid{display:grid;grid-template-columns:320px 1fr;gap:20px;align-items:start;}
        .form-card{background:#fff;border-radius:6px;border:0.5px solid rgba(59,35,20,0.06);padding:24px;}
        .form-title{font-size:9px;color:rgba(59,35,20,0.25);letter-spacing:2px;text-transform:uppercase;margin-bottom:16px;}
        .form-group{margin-bottom:16px;}
        .form-label{display:block;font-size:10px;color:rgba(59,35,20,0.35);letter-spacing:1.5px;text-transform:uppercase;margin-bottom:7px;}
        .form-input,.form-select{width:100%;padding:10px 14px;border:0.5px solid rgba(59,35,20,0.12);border-radius:2px;font-family:'DM Sans',sans-serif;font-size:13px;color:#2c1a0e;background:#faf8f5;outline:none;}
        .form-input:focus,.form-select:focus{border-color:rgba(59,35,20,0.35);}
        .btn-submit{width:100%;padding:11px;background:#2c1a0e;color:#f5deb3;border:none;border-radius:2px;font-family:'DM Sans',sans-serif;font-size:12px;font-weight:500;letter-spacing:1px;cursor:pointer;margin-top:4px;}
        .duties{display:flex;flex-direction:column;gap:10px;}
        .duty-card{background:#fff;border-radius:6px;border:0.5px solid rgba(59,35,20,0.06);padding:16px 20px;display:flex;align-items:center;justify-content:space-between;}
        .duty-card.today{border-left:3px solid rgba(59,35,20,0.4);}
        .duty-name{font-size:14px;font-weight:500;color:#2c1a0e;}
        .duty-location{font-size:11px;color:rgba(59,35,20,0.35);margin-top:2px;}
        .duty-time{font-size:12px;color:rgba(59,35,20,0.5);margin-top:4px;}
        .duty-date{font-family:'Fraunces',serif;font-size:15px;font-weight:700;color:#2c1a0e;text-align:right;}
        .duty-day{font-size:10px;color:rgba(59,35,20,0.3);text-align:right;margin-top:2px;}
        .btn-delete{padding:4px 10px;border-radius:2px;font-size:10px;font-family:'DM Sans',sans-serif;cursor:pointer;border:none;background:rgba(239,68,68,0.08);color:rgba(185,28,28,0.7);}
        .btn-edit{padding:4px 10px;border-radius:2px;font-size:10px;font-family:'DM Sans',sans-serif;background:rgba(59,35,20,0.06);color:rgba(59,35,20,0.5);text-decoration:none;}
        .today-badge{display:inline-block;padding:2px 8px;background:rgba(59,35,20,0.08);color:rgba(59,35,20,0.5);border-radius:2px;font-size:9px;letter-spacing:1px;text-transform:uppercase;}
        .empty{text-align:center;color:rgba(59,35,20,0.2);font-size:12px;padding:32px;background:#fff;border-radius:6px;}
        .success{background:rgba(34,197,94,0.1);color:rgba(21,128,61,0.8);padding:12px 18px;border-radius:4px;font-size:12px;margin-bottom:18px;}
        .count-text{font-size:13px;color:rgba(59,35,20,0.3);margin-bottom:14px;}
        .error-msg{font-size:11px;color:rgba(185,28,28,0.7);margin-top:4px;}
        .action-btns{display:flex;gap:6px;margin-top:8px;}
    </style>
@endsection

@section('content')

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    <div class="page-grid">

        {{-- Nöbet ekleme formu --}}
        <div class="form-card">
            <div class="form-title">Yeni Nöbet Ekle</div>
            <form method="POST" action="{{ route('admin.duty.store') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Departman</label>
                    <select id="dept_select" class="form-select" onchange="filterStaff(this.value)">
                        <option value="">— Departman Seç —</option>
                        <option value="security">Güvenlik</option>
                        <option value="kitchen">Yemekhane</option>
                        <option value="cleaning">Temizlik</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Personel</label>
                    <select name="staff_id" id="staff_select" class="form-select" required disabled>
                        <option value="">— Önce Departman Seç —</option>
                        @foreach($staff as $s)
                            <option value="{{ $s->id }}" data-dept="{{ $s->department }}">{{ $s->user->name }}</option>
                        @endforeach
                    </select>
                    @error('staff_id')<div class="error-msg">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Tarih</label>
                    <input type="date" name="duty_date" class="form-input" required>
                    @error('duty_date')<div class="error-msg">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Başlangıç Saati</label>
                    <input type="time" name="start_time" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Bitiş Saati</label>
                    <input type="time" name="end_time" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Konum (Opsiyonel)</label>
                    <input type="text" name="location" class="form-input" placeholder="A Blok kapı, B Blok kapı...">
                </div>
                <button type="submit" class="btn-submit">Nöbet Ekle</button>
            </form>
        </div>

        {{-- Nöbet listesi --}}
        <div>
            @if($duties->isEmpty())
                <div class="empty">Henüz nöbet eklenmemiş.</div>
            @else
                <div class="count-text">Toplam {{ $duties->count() }} nöbet</div>
                <div class="duties">
                    @foreach($duties as $duty)
                        <div class="duty-card {{ \Carbon\Carbon::parse($duty->duty_date)->isToday() ? 'today' : '' }}">
                            <div>
                                <div class="duty-name">
                                    {{ $duty->staff->user->name }}
                                    @if(\Carbon\Carbon::parse($duty->duty_date)->isToday())
                                        <span class="today-badge">Bugün</span>
                                    @endif
                                    @if($duty->is_done)
                                        <span style="font-size:9px;color:rgba(21,128,61,0.8);background:rgba(34,197,94,0.1);padding:2px 8px;border-radius:2px;margin-left:4px;">✓ Tamamlandı</span>
                                    @endif
                                </div>
                                @if($duty->location)
                                    <div class="duty-location">{{ $duty->location }}</div>
                                @endif
                                <div class="duty-time">{{ substr($duty->start_time, 0, 5) }} – {{ substr($duty->end_time, 0, 5) }}</div>
                                <div class="action-btns">
                                    <a href="{{ route('admin.duty.edit', $duty->id) }}" class="btn-edit">Düzenle</a>
                                    <form method="POST" action="{{ route('admin.duty.destroy', $duty->id) }}" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-delete" onclick="return confirm('Nöbeti silmek istediğinize emin misiniz?')">Sil</button>
                                    </form>
                                </div>
                            </div>
                            <div>
                                <div class="duty-date">{{ \Carbon\Carbon::parse($duty->duty_date)->format('d.m.Y') }}</div>
                                <div class="duty-day">{{ \Carbon\Carbon::parse($duty->duty_date)->locale('tr')->isoFormat('dddd') }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>

@endsection

@section('scripts')
    <script>
        const allStaff = @json($staff->map(fn($s) => ['id' => $s->id, 'name' => $s->user->name, 'dept' => $s->department]));

        function filterStaff(dept) {
            const select = document.getElementById('staff_select');
            if (!dept) {
                select.disabled = true;
                select.innerHTML = '<option value="">— Önce Departman Seç —</option>';
                return;
            }
            const filtered = allStaff.filter(s => s.dept === dept);
            select.disabled = false;
            select.innerHTML = '<option value="">— Personel Seç —</option>';
            filtered.forEach(s => {
                select.innerHTML += `<option value="${s.id}">${s.name}</option>`;
            });
        }
    </script>
@endsection
