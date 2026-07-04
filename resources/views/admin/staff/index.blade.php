@extends('layouts.admin')

@section('title', 'Personel')
@section('page-title', 'Personel')

@section('styles')
    <style>
        .page-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;}
        .btn-add{padding:9px 20px;background:#2c1a0e;color:#f5deb3;border:none;border-radius:2px;font-family:'DM Sans',sans-serif;font-size:12px;font-weight:500;letter-spacing:1px;cursor:pointer;text-decoration:none;}
        .table-card{background:#fff;border-radius:6px;border:0.5px solid rgba(59,35,20,0.06);overflow:hidden;}
        table{width:100%;border-collapse:collapse;}
        th{font-size:10px;color:rgba(59,35,20,0.3);letter-spacing:1.5px;text-transform:uppercase;padding:12px 22px;text-align:left;border-bottom:0.5px solid rgba(59,35,20,0.06);}
        td{font-size:13px;color:rgba(59,35,20,0.6);padding:14px 22px;border-bottom:0.5px solid rgba(59,35,20,0.04);}
        tr:last-child td{border-bottom:none;}
        .badge{display:inline-block;padding:3px 10px;border-radius:2px;font-size:10px;}
        .badge.security{background:rgba(59,130,246,0.1);color:rgba(29,78,216,0.8);}
        .badge.kitchen{background:rgba(34,197,94,0.1);color:rgba(21,128,61,0.8);}
        .badge.cleaning{background:rgba(234,179,8,0.1);color:rgba(161,98,7,0.8);}
        .btn-delete{padding:5px 12px;border-radius:2px;font-size:11px;font-family:'DM Sans',sans-serif;cursor:pointer;border:none;background:rgba(239,68,68,0.08);color:rgba(185,28,28,0.7);}
        .empty{text-align:center;color:rgba(59,35,20,0.2);font-size:12px;padding:32px;}
        .success{background:rgba(34,197,94,0.1);color:rgba(21,128,61,0.8);padding:12px 18px;border-radius:4px;font-size:12px;margin-bottom:18px;}
        .btn-edit{padding:5px 12px;border-radius:2px;font-size:11px;font-family:'DM Sans',sans-serif;text-decoration:none;background:rgba(59,35,20,0.06);color:rgba(59,35,20,0.5);}
    </style>
@endsection

@section('content')

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    <div class="page-header">
        <span style="font-size:13px;color:rgba(59,35,20,0.3);">Toplam {{ $staff->count() }} personel</span>
        <a href="{{ route('admin.staff.create') }}" class="btn-add">+ Personel Ekle</a>
    </div>

    <div class="table-card">
        <table>
            <tr>
                <th>Ad Soyad</th>
                <th>E-posta</th>
                <th>Telefon</th>
                <th>Departman</th>
                <th>Başlangıç</th>
                <th>İşlem</th>
            </tr>
            @forelse($staff as $s)
                <tr>
                    <td>{{ $s->user->name }}</td>
                    <td>{{ $s->user->email }}</td>
                    <td>{{ $s->phone }}</td>
                    <td>
                    <span class="badge {{ $s->department }}">
                        @if($s->department == 'security') Güvenlik
                        @elseif($s->department == 'kitchen') Yemekhane
                        @else Temizlik
                        @endif
                    </span>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($s->start_date)->format('d.m.Y') }}</td>
                    <td>
                        <a href="{{ route('admin.staff.edit', $s->id) }}" class="action-btn btn-edit">Düzenle</a>
                        <form method="POST" action="{{ route('admin.staff.destroy', $s->id) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete" onclick="return confirm('Silmek istediğinize emin misiniz?')">Sil</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="empty">Henüz personel eklenmemiş.</td></tr>
            @endforelse
        </table>
    </div>

@endsection
