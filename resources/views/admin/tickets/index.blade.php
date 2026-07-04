@extends('layouts.admin')

@section('title', 'Arıza & Şikayet')
@section('page-title', 'Arıza & Şikayet')

@section('styles')
    <style>
        .filters{display:flex;gap:8px;margin-bottom:20px;}
        .filter-btn{padding:7px 16px;background:#fff;color:rgba(59,35,20,0.4);border:0.5px solid rgba(59,35,20,0.1);border-radius:2px;font-size:11px;font-family:'DM Sans',sans-serif;cursor:pointer;text-decoration:none;}
        .filter-btn.active{background:#2c1a0e;color:#f5deb3;border-color:#2c1a0e;}
        .tickets{display:flex;flex-direction:column;gap:10px;}
        .ticket-card{background:#fff;border-radius:6px;border:0.5px solid rgba(59,35,20,0.06);padding:18px 22px;}
        .ticket-card.ariza{border-left:3px solid rgba(234,179,8,0.4);}
        .ticket-card.sikayet{border-left:3px solid rgba(239,68,68,0.4);}
        .ticket-top{display:flex;align-items:flex-start;justify-content:space-between;gap:16px;}
        .ticket-title{font-size:14px;font-weight:500;color:#2c1a0e;}
        .ticket-student{font-size:11px;color:rgba(59,35,20,0.35);margin-top:3px;}
        .ticket-desc{font-size:12px;color:rgba(59,35,20,0.45);line-height:1.6;margin-top:10px;}
        .ticket-meta{display:flex;gap:8px;align-items:center;margin-top:8px;}
        .ticket-date{font-size:11px;color:rgba(59,35,20,0.25);}
        .badge{display:inline-block;padding:3px 10px;border-radius:2px;font-size:10px;letter-spacing:0.5px;}
        .badge.ariza{background:rgba(234,179,8,0.1);color:rgba(161,98,7,0.8);}
        .badge.sikayet{background:rgba(239,68,68,0.08);color:rgba(185,28,28,0.7);}
        .status-btns{display:flex;gap:6px;flex-shrink:0;}
        .status-btn{padding:5px 14px;border-radius:2px;font-size:11px;font-family:'DM Sans',sans-serif;cursor:pointer;border:0.5px solid rgba(59,35,20,0.1);background:transparent;color:rgba(59,35,20,0.35);}
        .status-btn.active-inceleniyor{background:rgba(234,179,8,0.1);color:rgba(161,98,7,0.8);border-color:rgba(234,179,8,0.2);}
        .status-btn.active-cozuldu{background:rgba(34,197,94,0.1);color:rgba(21,128,61,0.8);border-color:rgba(34,197,94,0.2);}
        .admin-note-form{margin-top:10px;display:flex;gap:8px;}
        .admin-note-input{flex:1;padding:7px 12px;border:0.5px solid rgba(59,35,20,0.12);border-radius:2px;font-family:'DM Sans',sans-serif;font-size:12px;color:#2c1a0e;background:#faf8f5;outline:none;}
        .admin-note-btn{padding:7px 14px;background:#2c1a0e;color:#f5deb3;border:none;border-radius:2px;font-family:'DM Sans',sans-serif;font-size:11px;cursor:pointer;white-space:nowrap;}
        .admin-note-text{font-size:12px;color:rgba(59,35,20,0.4);background:rgba(59,35,20,0.03);border-radius:4px;padding:8px 12px;margin-top:10px;}
        .admin-note-label{font-size:9px;color:rgba(59,35,20,0.25);letter-spacing:2px;text-transform:uppercase;margin-bottom:4px;}
        .empty{text-align:center;color:rgba(59,35,20,0.2);font-size:12px;padding:32px;background:#fff;border-radius:6px;}
        .success{background:rgba(34,197,94,0.1);color:rgba(21,128,61,0.8);padding:12px 18px;border-radius:4px;font-size:12px;margin-bottom:18px;}
        .count-text{font-size:13px;color:rgba(59,35,20,0.3);margin-bottom:14px;}
    </style>
@endsection

@section('content')

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    <div class="filters">
        <a href="{{ route('admin.tickets') }}" class="filter-btn {{ !request('type') ? 'active' : '' }}">Tümü</a>
        <a href="{{ route('admin.tickets', ['type' => 'ariza']) }}" class="filter-btn {{ request('type') == 'ariza' ? 'active' : '' }}">Arızalar</a>
        <a href="{{ route('admin.tickets', ['type' => 'sikayet']) }}" class="filter-btn {{ request('type') == 'sikayet' ? 'active' : '' }}">Şikayetler</a>
    </div>

    @if($tickets->isEmpty())
        <div class="empty">Henüz talep yok.</div>
    @else
        <div class="count-text">Toplam {{ $tickets->count() }} talep</div>
        <div class="tickets">
            @foreach($tickets as $ticket)
                <div class="ticket-card {{ $ticket->type }}">
                    <div class="ticket-top">
                        <div>
                            <div class="ticket-title">{{ $ticket->title }}</div>
                            <div class="ticket-student">{{ $ticket->student->user->name }} · {{ $ticket->student->room ? $ticket->student->room->room_number . ' No\'lu Oda' : 'Oda yok' }}</div>
                        </div>
                        <div class="status-btns">
                            <form method="POST" action="{{ route('admin.tickets.update', $ticket->id) }}" style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="inceleniyor">
                                <input type="hidden" name="admin_note" value="{{ $ticket->admin_note }}">
                                <button type="submit" class="status-btn {{ $ticket->status == 'inceleniyor' ? 'active-inceleniyor' : '' }}">İnceleniyor</button>
                            </form>
                            <form method="POST" action="{{ route('admin.tickets.update', $ticket->id) }}" style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="cozuldu">
                                <input type="hidden" name="admin_note" value="{{ $ticket->admin_note }}">
                                <button type="submit" class="status-btn {{ $ticket->status == 'cozuldu' ? 'active-cozuldu' : '' }}">Çözüldü</button>
                            </form>
                        </div>
                    </div>
                    <div class="ticket-meta">
                        <span class="badge {{ $ticket->type }}">{{ $ticket->type === 'ariza' ? 'Arıza' : 'Şikayet' }}</span>
                        <span class="ticket-date">{{ \Carbon\Carbon::parse($ticket->created_at)->format('d.m.Y H:i') }}</span>
                    </div>
                    <div class="ticket-desc">{{ $ticket->description }}</div>

                    @if($ticket->admin_note)
                        <div class="admin-note-text">
                            <div class="admin-note-label">Not</div>
                            {{ $ticket->admin_note }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.tickets.update', $ticket->id) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="{{ $ticket->status }}">
                        <div class="admin-note-form">
                            <input type="text" name="admin_note" class="admin-note-input" value="{{ $ticket->admin_note }}" placeholder="Not ekle...">
                            <button type="submit" class="admin-note-btn">Kaydet</button>
                        </div>
                    </form>
                </div>
            @endforeach
        </div>
    @endif

@endsection
