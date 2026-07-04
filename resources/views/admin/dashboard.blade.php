@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Anasayfa')

@section('styles')
    <style>
        .stats{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:26px;}
        .stat-card{background:#fff;border-radius:6px;padding:20px;border:0.5px solid rgba(59,35,20,0.06);}
        .stat-card.accent{background:#2c1a0e;}
        .stat-label{font-size:10px;color:rgba(59,35,20,0.3);letter-spacing:1.5px;text-transform:uppercase;margin-bottom:10px;}
        .stat-card.accent .stat-label{color:rgba(245,220,180,0.3);}
        .stat-num{font-family:'Fraunces',serif;font-size:36px;font-weight:700;color:#2c1a0e;line-height:1;}
        .stat-card.accent .stat-num{color:#f5deb3;}
        .stat-sub{font-size:10px;color:rgba(59,35,20,0.25);margin-top:5px;}
        .stat-card.accent .stat-sub{color:rgba(245,220,180,0.2);}
        .table-card{background:#fff;border-radius:6px;border:0.5px solid rgba(59,35,20,0.06);overflow:hidden;}
        .table-header{display:flex;align-items:center;justify-content:space-between;padding:18px 22px;border-bottom:0.5px solid rgba(59,35,20,0.06);}
        .table-title{font-size:11px;color:rgba(59,35,20,0.4);letter-spacing:2px;text-transform:uppercase;}
        .toggle-btn{padding:6px 14px;background:transparent;color:rgba(59,35,20,0.3);border:0.5px solid rgba(59,35,20,0.1);border-radius:2px;font-size:11px;font-family:'DM Sans',sans-serif;cursor:pointer;}
        table{width:100%;border-collapse:collapse;}
        th{font-size:10px;color:rgba(59,35,20,0.3);letter-spacing:1.5px;text-transform:uppercase;padding:12px 22px;text-align:left;border-bottom:0.5px solid rgba(59,35,20,0.06);}
        td{font-size:13px;color:rgba(59,35,20,0.6);padding:14px 22px;border-bottom:0.5px solid rgba(59,35,20,0.04);}
        tr:last-child td{border-bottom:none;}
        tr.hidden-row{display:none;}
        .badge{display:inline-block;padding:3px 10px;border-radius:2px;font-size:10px;}
        .badge.green{background:rgba(34,197,94,0.1);color:rgba(21,128,61,0.8);}
        .badge.yellow{background:rgba(234,179,8,0.1);color:rgba(161,98,7,0.8);}
        .badge.red{background:rgba(239,68,68,0.1);color:rgba(185,28,28,0.8);}
    </style>
@endsection

@section('content')
    <div class="stats">
        <div class="stat-card accent">
            <div class="stat-label">Toplam Öğrenci</div>
            <div class="stat-num">{{ $studentCount }}</div>
            <div class="stat-sub">Aktif kayıt</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Dolu Oda</div>
            <div class="stat-num">{{ $roomCount }}</div>
            <div class="stat-sub">Toplam oda</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Bekleyen İzin</div>
            <div class="stat-num">{{ $pendingLeaves }}</div>
            <div class="stat-sub">Onay bekliyor</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Gecikmiş Ödeme</div>
            <div class="stat-num">{{ $latePayments }}</div>
            <div class="stat-sub">Bu ay</div>
        </div>
    </div>

    <div class="table-card">
        <div class="table-header">
            <span class="table-title">Son İzin Talepleri</span>
            <button class="toggle-btn" onclick="toggleRows()">Tümünü Göster</button>
        </div>
        <table>
            <tr><th>Öğrenci</th><th>Başlangıç</th><th>Bitiş</th><th>Durum</th></tr>
            @forelse($recentLeaves as $leave)
                <tr>
                    <td>{{ $leave->student->user->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($leave->start_date)->format('d.m.Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($leave->end_date)->format('d.m.Y') }}</td>
                    <td>
                        @if($leave->status === 'approved')
                            <span class="badge green">Onaylandı</span>
                        @elseif($leave->status === 'pending')
                            <span class="badge yellow">Bekliyor</span>
                        @else
                            <span class="badge red">Reddedildi</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" style="text-align:center;color:rgba(59,35,20,0.2);font-size:12px;padding:24px;">Henüz izin talebi yok.</td></tr>
            @endforelse
        </table>
    </div>
@endsection

@section('scripts')
    <script>
        let expanded = false;
        function toggleRows() {
            expanded = !expanded;
            document.querySelectorAll('.hidden-row').forEach(r => r.style.display = expanded ? 'table-row' : 'none');
            document.querySelector('.toggle-btn').textContent = expanded ? 'Daha Az Göster' : 'Tümünü Göster';
        }
    </script>
@endsection
