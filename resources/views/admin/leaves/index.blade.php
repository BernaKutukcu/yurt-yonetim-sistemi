@extends('layouts.admin')

@section('title', 'İzinler')
@section('page-title', 'İzin Talepleri')

@section('styles')
    <style>
        .table-card{background:#fff;border-radius:6px;border:0.5px solid rgba(59,35,20,0.06);overflow:hidden;}
        table{width:100%;border-collapse:collapse;}
        th{font-size:10px;color:rgba(59,35,20,0.3);letter-spacing:1.5px;text-transform:uppercase;padding:12px 22px;text-align:left;border-bottom:0.5px solid rgba(59,35,20,0.06);}
        td{font-size:13px;color:rgba(59,35,20,0.6);padding:14px 22px;border-bottom:0.5px solid rgba(59,35,20,0.04);}
        tr:last-child td{border-bottom:none;}
        .badge{display:inline-block;padding:3px 10px;border-radius:2px;font-size:10px;letter-spacing:0.5px;}
        .badge.green{background:rgba(34,197,94,0.1);color:rgba(21,128,61,0.8);}
        .badge.yellow{background:rgba(234,179,8,0.1);color:rgba(161,98,7,0.8);}
        .badge.red{background:rgba(239,68,68,0.1);color:rgba(185,28,28,0.8);}
        .action-btn{padding:5px 12px;border-radius:2px;font-size:11px;font-family:'DM Sans',sans-serif;cursor:pointer;border:none;margin-right:4px;}
        .btn-approve{background:rgba(34,197,94,0.1);color:rgba(21,128,61,0.8);}
        .btn-reject{background:rgba(239,68,68,0.08);color:rgba(185,28,28,0.7);}
        .btn-delete{background:rgba(59,35,20,0.06);color:rgba(59,35,20,0.4);}
        .empty{text-align:center;color:rgba(59,35,20,0.2);font-size:12px;padding:32px;}
        .success{background:rgba(34,197,94,0.1);color:rgba(21,128,61,0.8);padding:12px 18px;border-radius:4px;font-size:12px;margin-bottom:18px;}
        .filters{display:flex;gap:8px;margin-bottom:20px;}
        .filter-btn{padding:7px 16px;background:#fff;color:rgba(59,35,20,0.4);border:0.5px solid rgba(59,35,20,0.1);border-radius:2px;font-size:11px;font-family:'DM Sans',sans-serif;cursor:pointer;text-decoration:none;}
        .filter-btn.active{background:#2c1a0e;color:#f5deb3;border-color:#2c1a0e;}
    </style>
@endsection

@section('content')

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    <div class="filters">
        <a href="{{ route('admin.leaves') }}" class="filter-btn {{ !request('status') ? 'active' : '' }}">Tümü</a>
        <a href="{{ route('admin.leaves', ['status' => 'pending']) }}" class="filter-btn {{ request('status') == 'pending' ? 'active' : '' }}">Bekleyenler</a>
        <a href="{{ route('admin.leaves', ['status' => 'approved']) }}" class="filter-btn {{ request('status') == 'approved' ? 'active' : '' }}">Onaylananlar</a>
        <a href="{{ route('admin.leaves', ['status' => 'rejected']) }}" class="filter-btn {{ request('status') == 'rejected' ? 'active' : '' }}">Reddedilenler</a>
    </div>

    <div class="table-card">
        <table>
            <tr>
                <th>Öğrenci</th>
                <th>Başlangıç</th>
                <th>Bitiş</th>
                <th>Adres</th>
                <th>Durum</th>
                <th>İşlem</th>
            </tr>
            @forelse($leaves as $leave)
                <tr>
                    <td>{{ $leave->student->user->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($leave->start_date)->format('d.m.Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($leave->end_date)->format('d.m.Y') }}</td>
                    <td>{{ $leave->address }}</td>
                    <td>
                        @if($leave->status == 'pending')
                            <span class="badge yellow">Bekliyor</span>
                        @elseif($leave->status == 'approved')
                            <span class="badge green">Onaylandı</span>
                        @else
                            <span class="badge red">Reddedildi</span>
                        @endif
                    </td>
                    <td>
                        @if($leave->status == 'pending')
                            <form method="POST" action="{{ route('admin.leaves.approve', $leave->id) }}" style="display:inline;">
                                @csrf
                                <button type="submit" class="action-btn btn-approve">Onayla</button>
                            </form>
                            <form method="POST" action="{{ route('admin.leaves.reject', $leave->id) }}" style="display:inline;">
                                @csrf
                                <button type="submit" class="action-btn btn-reject">Reddet</button>
                            </form>
                        @endif
                        <form method="POST" action="{{ route('admin.leaves.destroy', $leave->id) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn btn-delete" onclick="return confirm('Silmek istediğinize emin misiniz?')">Sil</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="empty">Henüz izin talebi yok.</td></tr>
            @endforelse
        </table>
    </div>

@endsection
