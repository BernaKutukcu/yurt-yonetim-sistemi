@extends('layouts.student')

@section('title', 'Arıza & Şikayet')
@section('page-title', 'Arıza & Şikayet')

@section('styles')
    <style>
        .form-card{background:#fff;border-radius:6px;border:0.5px solid rgba(59,35,20,0.06);padding:24px 26px;margin-bottom:24px;}
        .form-title{font-size:9px;color:rgba(59,35,20,0.25);letter-spacing:2px;text-transform:uppercase;margin-bottom:16px;}
        .form-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;}
        .form-group{display:flex;flex-direction:column;gap:6px;}
        .form-group.full{grid-column:span 2;}
        .form-label{font-size:10px;color:rgba(59,35,20,0.35);letter-spacing:1.5px;text-transform:uppercase;}
        .form-input,.form-select,.form-textarea{padding:10px 14px;border:0.5px solid rgba(59,35,20,0.12);border-radius:2px;font-family:'DM Sans',sans-serif;font-size:13px;color:#2c1a0e;background:#faf8f5;outline:none;width:100%;}
        .form-input:focus,.form-select:focus,.form-textarea:focus{border-color:rgba(59,35,20,0.35);}
        .form-textarea{resize:vertical;min-height:90px;line-height:1.6;}
        .btn-submit{padding:10px 24px;background:#2c1a0e;color:#f5deb3;border:none;border-radius:2px;font-family:'DM Sans',sans-serif;font-size:12px;font-weight:500;letter-spacing:1px;cursor:pointer;margin-top:16px;}
        .tickets{display:flex;flex-direction:column;gap:10px;}
        .ticket-card{background:#fff;border-radius:6px;border:0.5px solid rgba(59,35,20,0.06);padding:18px 22px;}
        .ticket-card.ariza{border-left:3px solid rgba(234,179,8,0.4);}
        .ticket-card.sikayet{border-left:3px solid rgba(239,68,68,0.4);}
        .ticket-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;}
        .ticket-title{font-size:14px;font-weight:500;color:#2c1a0e;}
        .ticket-desc{font-size:12px;color:rgba(59,35,20,0.45);line-height:1.6;margin-top:6px;}
        .ticket-note{font-size:12px;color:rgba(59,35,20,0.4);background:rgba(59,35,20,0.03);border-radius:4px;padding:8px 12px;margin-top:10px;}
        .ticket-note-label{font-size:9px;color:rgba(59,35,20,0.25);letter-spacing:2px;text-transform:uppercase;margin-bottom:4px;}
        .ticket-meta{display:flex;gap:8px;align-items:center;margin-top:6px;}
        .badge{display:inline-block;padding:3px 10px;border-radius:2px;font-size:10px;letter-spacing:0.5px;}
        .badge.ariza{background:rgba(234,179,8,0.1);color:rgba(161,98,7,0.8);}
        .badge.sikayet{background:rgba(239,68,68,0.08);color:rgba(185,28,28,0.7);}
        .badge.bekliyor{background:rgba(59,35,20,0.06);color:rgba(59,35,20,0.4);}
        .badge.inceleniyor{background:rgba(234,179,8,0.1);color:rgba(161,98,7,0.8);}
        .badge.cozuldu{background:rgba(34,197,94,0.1);color:rgba(21,128,61,0.8);}
        .empty{text-align:center;color:rgba(59,35,20,0.2);font-size:12px;padding:32px;background:#fff;border-radius:6px;}
        .success{background:rgba(34,197,94,0.1);color:rgba(21,128,61,0.8);padding:12px 18px;border-radius:4px;font-size:12px;margin-bottom:18px;}
        .error-msg{font-size:11px;color:rgba(185,28,28,0.7);margin-top:4px;}
        .count-text{font-size:13px;color:rgba(59,35,20,0.3);margin-bottom:14px;}
        .ticket-date{font-size:11px;color:rgba(59,35,20,0.25);}
    </style>
@endsection

@section('content')

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    {{-- Yeni talep formu --}}
    <div class="form-card">
        <div class="form-title">Yeni Talep</div>
        <form method="POST" action="{{ route('student.tickets.store') }}">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Talep Türü</label>
                    <select name="type" class="form-select" required>
                        <option value="">— Seç —</option>
                        <option value="ariza" {{ old('type') == 'ariza' ? 'selected' : '' }}>🔧 Arıza Bildirimi</option>
                        <option value="sikayet" {{ old('type') == 'sikayet' ? 'selected' : '' }}>📢 Şikayet</option>
                    </select>
                    @error('type')<div class="error-msg">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Konu</label>
                    <input type="text" name="title" class="form-input" value="{{ old('title') }}" placeholder="Kısaca açıklayın..." required>
                    @error('title')<div class="error-msg">{{ $message }}</div>@enderror
                </div>
                <div class="form-group full">
                    <label class="form-label">Açıklama</label>
                    <textarea name="description" class="form-textarea" placeholder="Detaylı açıklama yazın..." required>{{ old('description') }}</textarea>
                    @error('description')<div class="error-msg">{{ $message }}</div>@enderror
                </div>
            </div>
            <button type="submit" class="btn-submit">Gönder</button>
        </form>
    </div>

    {{-- Talep listesi --}}
    @if($tickets->isEmpty())
        <div class="empty">Henüz talebiniz yok.</div>
    @else
        <div class="count-text">Toplam {{ $tickets->count() }} talep</div>
        <div class="tickets">
            @foreach($tickets as $ticket)
                <div class="ticket-card {{ $ticket->type }}">
                    <div class="ticket-header">
                        <div class="ticket-title">{{ $ticket->title }}</div>
                        <span class="badge {{ $ticket->status }}">
                            @if($ticket->status === 'bekliyor') Bekliyor
                            @elseif($ticket->status === 'inceleniyor') İnceleniyor
                            @else Çözüldü
                            @endif
                        </span>
                    </div>
                    <div class="ticket-meta">
                        <span class="badge {{ $ticket->type }}">{{ $ticket->type === 'ariza' ? 'Arıza' : 'Şikayet' }}</span>
                        <span class="ticket-date">{{ \Carbon\Carbon::parse($ticket->created_at)->format('d.m.Y H:i') }}</span>
                    </div>
                    <div class="ticket-desc">{{ $ticket->description }}</div>
                    @if($ticket->admin_note)
                        <div class="ticket-note">
                            <div class="ticket-note-label">Yönetici Notu</div>
                            {{ $ticket->admin_note }}
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

@endsection
