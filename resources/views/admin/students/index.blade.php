@extends('layouts.admin')

@section('title', 'Öğrenciler')
@section('page-title', 'Öğrenciler')

@section('styles')
    <style>
        .page-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;}
        .btn-add{padding:9px 20px;background:#2c1a0e;color:#f5deb3;border:none;border-radius:2px;font-family:'DM Sans',sans-serif;font-size:12px;font-weight:500;letter-spacing:1px;cursor:pointer;text-decoration:none;}
        .filters{display:flex;gap:8px;margin-bottom:20px;}
        .filter-btn{padding:7px 16px;background:#fff;color:rgba(59,35,20,0.4);border:0.5px solid rgba(59,35,20,0.1);border-radius:2px;font-size:11px;font-family:'DM Sans',sans-serif;cursor:pointer;text-decoration:none;}
        .filter-btn.active{background:#2c1a0e;color:#f5deb3;border-color:#2c1a0e;}
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
    </style>
@endsection

@section('content')

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    <div class="page-header">
        <span style="font-size:13px;color:rgba(59,35,20,0.3);">Toplam {{ $students->count() }} öğrenci</span>
        <a href="{{ route('admin.students.create') }}" class="btn-add">+ Öğrenci Ekle</a>
    </div>

    <div class="filters">
        <a href="{{ route('admin.students') }}" class="filter-btn {{ !request('block') ? 'active' : '' }}">Tümü</a>
        <a href="{{ route('admin.students', ['block' => 'A']) }}" class="filter-btn {{ request('block') == 'A' ? 'active' : '' }}">A Blok</a>
        <a href="{{ route('admin.students', ['block' => 'B']) }}" class="filter-btn {{ request('block') == 'B' ? 'active' : '' }}">B Blok</a>
        <a href="{{ route('admin.students', ['block' => 'C']) }}" class="filter-btn {{ request('block') == 'C' ? 'active' : '' }}">C Blok</a>
        <a href="{{ route('admin.students', ['block' => 'D']) }}" class="filter-btn {{ request('block') == 'D' ? 'active' : '' }}">D Blok</a>
    </div>

    <div class="table-card">
        <table>
            <tr>
                <th>Ad Soyad</th>
                <th>E-posta</th>
                <th>TC No</th>
                <th>Bölüm</th>
                <th>Blok</th>
                <th>Oda</th>
                <th>İşlem</th>
            </tr>
            @forelse($students as $student)
                <tr>
                    <td>{{ $student->user->name }}</td>
                    <td>{{ $student->user->email }}</td>
                    <td>{{ $student->tc_no }}</td>
                    <td>{{ $student->department }}</td>
                    <td>{{ $student->room ? $student->room->block : '—' }}</td>
                    <td>{{ $student->room ? $student->room->room_number : '—' }}</td>
                    <td>
                        <a href="{{ route('admin.students.edit', $student->id) }}" class="action-btn btn-edit">Düzenle</a>
                        <form method="POST" action="{{ route('admin.students.destroy', $student->id) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn btn-delete" onclick="return confirm('Silmek istediğinize emin misiniz?')">Sil</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="empty">Henüz öğrenci eklenmemiş.</td></tr>
            @endforelse
        </table>
    </div>

@endsection
