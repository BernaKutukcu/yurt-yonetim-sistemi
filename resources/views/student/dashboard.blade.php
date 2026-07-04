@extends('layouts.student')

@section('title', 'Anasayfa')
@section('page-title', 'Merhaba, ' . Auth::user()->name)

@section('styles')
    <style>
        .cards{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:14px;margin-bottom:24px;}
        .card{background:#fff;border-radius:6px;border:0.5px solid rgba(59,35,20,0.06);padding:20px 22px;}
        .card-label{font-size:9px;color:rgba(59,35,20,0.25);letter-spacing:2px;text-transform:uppercase;margin-bottom:8px;}
        .card-value{font-family:'Fraunces',serif;font-size:22px;font-weight:700;color:#2c1a0e;}
        .card-sub{font-size:11px;color:rgba(59,35,20,0.3);margin-top:4px;}
        .grid2{display:grid;grid-template-columns:1fr 1fr;gap:14px;}
        .panel{background:#fff;border-radius:6px;border:0.5px solid rgba(59,35,20,0.06);padding:20px 22px;}
        .panel-title{font-size:9px;color:rgba(59,35,20,0.25);letter-spacing:2px;text-transform:uppercase;margin-bottom:14px;}
        .info-row{display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:0.5px solid rgba(59,35,20,0.05);}
        .info-row:last-child{border-bottom:none;}
        .info-key{font-size:12px;color:rgba(59,35,20,0.35);}
        .info-val{font-size:12px;color:#2c1a0e;font-weight:500;}
        .badge{display:inline-block;padding:3px 10px;border-radius:2px;font-size:10px;letter-spacing:0.5px;}
        .badge.paid{background:rgba(34,197,94,0.1);color:rgba(21,128,61,0.8);}
        .badge.unpaid{background:rgba(239,68,68,0.08);color:rgba(185,28,28,0.7);}
        .badge.approved{background:rgba(34,197,94,0.1);color:rgba(21,128,61,0.8);}
        .badge.pending{background:rgba(234,179,8,0.1);color:rgba(161,98,7,0.8);}
        .badge.rejected{background:rgba(239,68,68,0.08);color:rgba(185,28,28,0.7);}
        .empty-text{font-size:12px;color:rgba(59,35,20,0.2);text-align:center;padding:20px 0;}
        .meal-panel{background:#2c1a0e;border-radius:6px;padding:20px 22px;margin-bottom:24px;}
        .meal-panel-title{font-size:9px;color:rgba(245,220,180,0.3);letter-spacing:2px;text-transform:uppercase;margin-bottom:14px;}
        .meal-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;}
        .meal-label{font-size:9px;color:rgba(245,220,180,0.3);letter-spacing:2px;text-transform:uppercase;margin-bottom:6px;}
        .meal-text{font-size:13px;color:rgba(245,220,180,0.75);line-height:1.6;}
        .no-meal{font-size:12px;color:rgba(245,220,180,0.2);text-align:center;padding:10px 0;}
    </style>
@endsection

@section('content')

    {{-- Özet kartlar --}}
    <div class="cards">
        <div class="card">
            <div class="card-label">Oda</div>
            <div class="card-value">{{ $student->room ? $student->room->room_number : '—' }}</div>
            <div class="card-sub">{{ $student->bed_number ? $student->bed_number . '. yatak' : '' }}</div>
        </div>
        <div class="card">
            <div class="card-label">Bekleyen Ödeme</div>
            <div class="card-value">{{ $unpaidCount }}</div>
            <div class="card-sub">adet ödenmemiş</div>
        </div>
        <div class="card">
            <div class="card-label">İzin Talebi</div>
            <div class="card-value">{{ $leaveCount }}</div>
            <div class="card-sub">toplam talep</div>
        </div>
        <div class="card">
            <div class="card-label">Kayıt Tarihi</div>
            <div class="card-value" style="font-size:16px;">{{ \Carbon\Carbon::parse($student->registration_date)->format('d.m.Y') }}</div>
            <div class="card-sub">{{ $student->department }}</div>
        </div>
    </div>

    {{-- Bugünün menüsü --}}
    <div class="meal-panel">
        <div class="meal-panel-title">Bugünün Menüsü · {{ now()->locale('tr')->isoFormat('D MMMM') }}</div>
        @if($todayMenu)
            <div class="meal-grid">
                <div>
                    <div class="meal-label">Kahvaltı</div>
                    <div class="meal-text">{{ $todayMenu->breakfast }}</div>
                </div>
                <div>
                    <div class="meal-label">Akşam Yemeği</div>
                    <div class="meal-text">{{ $todayMenu->dinner }}</div>
                </div>
            </div>
        @else
            <div class="no-meal">Bugün için menü girilmemiş.</div>
        @endif
    </div>

    {{-- Alt grid --}}
    <div class="grid2">
        {{-- Son ödemeler --}}
        <div class="panel">
            <div class="panel-title">Son Ödemeler</div>
            @forelse($payments as $payment)
                <div class="info-row">
                    <div>
                        <div class="info-key">{{ \Carbon\Carbon::parse($payment->due_date)->format('d.m.Y') }}</div>
                        <div style="font-size:11px;color:rgba(59,35,20,0.25);margin-top:2px;">{{ number_format($payment->amount, 0, ',', '.') }} ₺</div>
                    </div>
                    <span class="badge {{ $payment->status }}">{{ $payment->status === 'paid' ? 'Ödendi' : 'Bekliyor' }}</span>
                </div>
            @empty
                <div class="empty-text">Ödeme kaydı yok.</div>
            @endforelse
        </div>

        {{-- Son izinler --}}
        <div class="panel">
            <div class="panel-title">Son İzinler</div>
            @forelse($leaves as $leave)
                <div class="info-row">
                    <div>
                        <div class="info-key">{{ \Carbon\Carbon::parse($leave->start_date)->format('d.m.Y') }} – {{ \Carbon\Carbon::parse($leave->end_date)->format('d.m.Y') }}</div>
                        <div style="font-size:11px;color:rgba(59,35,20,0.25);margin-top:2px;">{{ $leave->reason }}</div>
                    </div>
                    <span class="badge {{ $leave->status }}">
                        @if($leave->status === 'approved') Onaylandı
                        @elseif($leave->status === 'pending') Bekliyor
                        @else Reddedildi
                        @endif
                    </span>
                </div>
            @empty
                <div class="empty-text">İzin talebi yok.</div>
            @endforelse
        </div>
    </div>

@endsection
