@extends('layouts.student')

@section('title', 'İzinlerim')
@section('page-title', 'İzinlerim')

@section('styles')
    <style>
        .page-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;}
        .count-text{font-size:13px;color:rgba(59,35,20,0.3);}
        .btn-add{padding:9px 20px;background:#2c1a0e;color:#f5deb3;border:none;border-radius:2px;font-family:'DM Sans',sans-serif;font-size:12px;font-weight:500;letter-spacing:1px;cursor:pointer;text-decoration:none;}
        .form-card{background:#fff;border-radius:6px;border:0.5px solid rgba(59,35,20,0.06);padding:24px 26px;margin-bottom:24px;}
        .form-title{font-size:9px;color:rgba(59,35,20,0.25);letter-spacing:2px;text-transform:uppercase;margin-bottom:16px;}
        .form-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;}
        .form-group{display:flex;flex-direction:column;gap:6px;}
        .form-group.full{grid-column:span 2;}
        .form-label{font-size:10px;color:rgba(59,35,20,0.35);letter-spacing:1.5px;text-transform:uppercase;}
        .form-input,.form-textarea{padding:10px 14px;border:0.5px solid rgba(59,35,20,0.12);border-radius:2px;font-family:'DM Sans',sans-serif;font-size:13px;color:#2c1a0e;background:#faf8f5;outline:none;}
        .form-input:focus,.form-textarea:focus{border-color:rgba(59,35,20,0.35);}
        .form-textarea{resize:vertical;min-height:80px;}
        .btn-submit{padding:10px 24px;background:#2c1a0e;color:#f5deb3;border:none;border-radius:2px;font-family:'DM Sans',sans-serif;font-size:12px;font-weight:500;letter-spacing:1px;cursor:pointer;margin-top:16px;}
        .leaves{display:flex;flex-direction:column;gap:10px;}
        .leave-card{background:#fff;border-radius:6px;border:0.5px solid rgba(59,35,20,0.06);padding:18px 22px;display:flex;align-items:center;justify-content:space-between;}
        .leave-dates{font-family:'Fraunces',serif;font-size:15px;font-weight:700;color:#2c1a0e;}
        .leave-reason{font-size:12px;color:rgba(59,35,20,0.4);margin-top:4px;}
        .badge{display:inline-block;padding:3px 10px;border-radius:2px;font-size:10px;letter-spacing:0.5px;}
        .badge.approved{background:rgba(34,197,94,0.1);color:rgba(21,128,61,0.8);}
        .badge.pending{background:rgba(234,179,8,0.1);color:rgba(161,98,7,0.8);}
        .badge.rejected{background:rgba(239,68,68,0.08);color:rgba(185,28,28,0.7);}
        .empty{text-align:center;color:rgba(59,35,20,0.2);font-size:12px;padding:32px;background:#fff;border-radius:6px;}
        .success{background:rgba(34,197,94,0.1);color:rgba(21,128,61,0.8);padding:12px 18px;border-radius:4px;font-size:12px;margin-bottom:18px;}
        .error-msg{font-size:11px;color:rgba(185,28,28,0.7);margin-top:4px;}
        .btn-delete{padding:4px 12px;border-radius:2px;font-size:11px;font-family:'DM Sans',sans-serif;cursor:pointer;border:none;background:rgba(239,68,68,0.08);color:rgba(185,28,28,0.7);}
    </style>
@endsection

@section('content')

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    {{-- Yeni izin formu --}}
    <div class="form-card">
        <div class="form-title">Yeni İzin Talebi</div>
        <form method="POST" action="{{ route('student.leaves.store') }}">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Başlangıç Tarihi</label>
                    <input type="date" name="start_date" class="form-input" value="{{ old('start_date') }}" required>
                    @error('start_date')<div class="error-msg">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Bitiş Tarihi</label>
                    <input type="date" name="end_date" class="form-input" value="{{ old('end_date') }}" required>
                    @error('end_date')<div class="error-msg">{{ $message }}</div>@enderror
                </div>
                <div class="form-group full">
                    <label class="form-label">İzin Adresi</label>
                    <input type="text" name="address" class="form-input" value="{{ old('address') }}" placeholder="Gideceğiniz adres..." required>
                    @error('address')<div class="error-msg">{{ $message }}</div>@enderror
                </div>
                <div class="form-group full">
                    <label class="form-label">Telefon</label>
                    <input type="tel" name="phone" class="form-input" value="{{ old('phone') }}" placeholder="05xxxxxxxxx" maxlength="11" inputmode="numeric" oninput="this.value=this.value.replace(/[^0-9]/g,'')" required>
                    @error('phone')<div class="error-msg">{{ $message }}</div>@enderror
                </div>
                <div class="form-group full">
                    <label class="form-label">Açıklama</label>
                    <textarea name="reason" class="form-textarea" placeholder="İzin nedeninizi yazın..." required>{{ old('reason') }}</textarea>
                    @error('reason')<div class="error-msg">{{ $message }}</div>@enderror
                </div>
            </div>
            <button type="submit" class="btn-submit">Talep Gönder</button>
        </form>
    </div>

    {{-- İzin listesi --}}
    <div class="page-header">
        <span class="count-text">Toplam {{ $leaves->count() }} talep</span>
    </div>

    @if($leaves->isEmpty())
        <div class="empty">Henüz izin talebiniz yok.</div>
    @else
        <div class="leaves">
            @foreach($leaves as $leave)
                <div class="leave-card">
                    <div>
                        <div class="leave-dates">{{ \Carbon\Carbon::parse($leave->start_date)->format('d.m.Y') }} – {{ \Carbon\Carbon::parse($leave->end_date)->format('d.m.Y') }}</div>
                        <div class="leave-reason">{{ $leave->description }}</div>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;">
        <span class="badge {{ $leave->status }}">
            @if($leave->status === 'approved') Onaylandı
            @elseif($leave->status === 'pending') Bekliyor
            @else Reddedildi
            @endif
        </span>
                        @if($leave->status === 'pending')
                            <form method="POST" action="{{ route('student.leaves.destroy', $leave->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete" onclick="return confirm('İzin talebini iptal etmek istediğinize emin misiniz?')">İptal</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif

@endsection
