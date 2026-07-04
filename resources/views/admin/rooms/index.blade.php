@extends('layouts.admin')

@section('title', 'Odalar')
@section('page-title', 'Odalar')

@section('styles')
    <style>
        .page-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;}
        .btn-add{padding:9px 20px;background:#2c1a0e;color:#f5deb3;border:none;border-radius:2px;font-family:'DM Sans',sans-serif;font-size:12px;font-weight:500;letter-spacing:1px;cursor:pointer;text-decoration:none;}
        .table-card{background:#fff;border-radius:6px;border:0.5px solid rgba(59,35,20,0.06);overflow:hidden;}
        table{width:100%;border-collapse:collapse;}
        th{font-size:10px;color:rgba(59,35,20,0.3);letter-spacing:1.5px;text-transform:uppercase;padding:12px 22px;text-align:left;border-bottom:0.5px solid rgba(59,35,20,0.06);}
        td{font-size:13px;color:rgba(59,35,20,0.6);padding:14px 22px;border-bottom:0.5px solid rgba(59,35,20,0.04);}
        tr:last-child td{border-bottom:none;}
        .action-btn{padding:5px 12px;border-radius:2px;font-size:11px;font-family:'DM Sans',sans-serif;cursor:pointer;text-decoration:none;margin-right:6px;}
        .btn-edit{background:rgba(59,35,20,0.06);color:rgba(59,35,20,0.5);border:none;}
        .btn-delete{background:rgba(239,68,68,0.08);color:rgba(185,28,28,0.7);border:none;cursor:pointer;}
        .empty{text-align:center;color:rgba(59,35,20,0.2);font-size:12px;padding:32px;}
        .success{background:rgba(34,197,94,0.1);color:rgba(21,128,61,0.8);padding:12px 18px;border-radius:4px;font-size:12px;margin-bottom:18px;}
        .doluluk{display:flex;align-items:center;gap:8px;}
        .doluluk-bar{height:4px;background:rgba(59,35,20,0.08);border-radius:2px;flex:1;max-width:80px;}
        .doluluk-fill{height:100%;border-radius:2px;background:#2c1a0e;}
    </style>
@endsection

@section('content')

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    <div class="page-header">
        <span style="font-size:13px;color:rgba(59,35,20,0.3);">Toplam {{ $rooms->count() }} oda</span>
        <a href="{{ route('admin.rooms.create') }}" class="btn-add">+ Oda Ekle</a>
    </div>

    <div class="table-card">
        <table>
            <tr>
                <th>Oda No</th>
                <th>Blok</th>
                <th>Kat</th>
                <th>Kapasite</th>
                <th>Doluluk</th>
                <th>İşlem</th>
            </tr>
            @forelse($rooms as $room)
                <tr>
                    <td>{{ $room->room_number }}</td>
                    <td>{{ $room->block }}</td>
                    <td>{{ $room->floor }}</td>
                    <td>{{ $room->capacity }} kişilik</td>
                    <td>
                        <div class="doluluk">
                            <span>{{ $room->students_count }}/{{ $room->capacity }}</span>
                            <div class="doluluk-bar">
                                <div class="doluluk-fill" style="width:{{ $room->capacity > 0 ? ($room->students_count / $room->capacity * 100) : 0 }}%"></div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <a href="{{ route('admin.rooms.edit', $room->id) }}" class="action-btn btn-edit">Düzenle</a>
                        <form method="POST" action="{{ route('admin.rooms.destroy', $room->id) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn btn-delete" onclick="return confirm('Silmek istediğinize emin misiniz?')">Sil</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="empty">Henüz oda eklenmemiş.</td></tr>
            @endforelse
        </table>
    </div>

@endsection
