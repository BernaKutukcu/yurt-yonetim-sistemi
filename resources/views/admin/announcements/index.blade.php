@extends('layouts.admin')

@section('title', 'Duyurular')
@section('page-title', 'Duyurular')

@section('styles')
    <style>
        .page-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;}
        .btn-add{padding:9px 20px;background:#2c1a0e;color:#f5deb3;border:none;border-radius:2px;font-family:'DM Sans',sans-serif;font-size:12px;font-weight:500;letter-spacing:1px;cursor:pointer;text-decoration:none;}
        .announcements{display:flex;flex-direction:column;gap:12px;}
        .announcement-card{background:#fff;border-radius:6px;border:0.5px solid rgba(59,35,20,0.06);padding:20px 24px;}
        .ann-header{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:10px;}
        .ann-title{font-family:'Fraunces',serif;font-size:18px;font-weight:700;color:#2c1a0e;}
        .ann-meta{display:flex;align-items:center;gap:10px;margin-top:4px;}
        .ann-date{font-size:11px;color:rgba(59,35,20,0.3);}
        .ann-author{font-size:11px;color:rgba(59,35,20,0.3);}
        .ann-content{font-size:13px;color:rgba(59,35,20,0.6);line-height:1.7;margin-bottom:12px;}
        .ann-footer{display:flex;align-items:center;justify-content:space-between;}
        .badge{display:inline-block;padding:3px 10px;border-radius:2px;font-size:10px;letter-spacing:0.5px;}
        .badge.all{background:rgba(59,35,20,0.06);color:rgba(59,35,20,0.5);}
        .badge.student{background:rgba(34,197,94,0.1);color:rgba(21,128,61,0.8);}
        .badge.staff{background:rgba(234,179,8,0.1);color:rgba(161,98,7,0.8);}
        .badge.parent{background:rgba(99,102,241,0.1);color:rgba(67,56,202,0.8);}
        .btn-delete{padding:5px 12px;border-radius:2px;font-size:11px;font-family:'DM Sans',sans-serif;cursor:pointer;border:none;background:rgba(239,68,68,0.08);color:rgba(185,28,28,0.7);}
        .empty{text-align:center;color:rgba(59,35,20,0.2);font-size:12px;padding:32px;background:#fff;border-radius:6px;}
        .success{background:rgba(34,197,94,0.1);color:rgba(21,128,61,0.8);padding:12px 18px;border-radius:4px;font-size:12px;margin-bottom:18px;}
    </style>
@endsection

@section('content')

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    <div class="page-header">
        <span style="font-size:13px;color:rgba(59,35,20,0.3);">Toplam {{ $announcements->count() }} duyuru</span>
        <a href="{{ route('admin.announcements.create') }}" class="btn-add">+ Duyuru Ekle</a>
    </div>

    @if($announcements->isEmpty())
        <div class="empty">Henüz duyuru eklenmemiş.</div>
    @else
        <div class="announcements">
            @foreach($announcements as $ann)
                <div class="announcement-card">
                    <div class="ann-header">
                        <div>
                            <div class="ann-title">{{ $ann->title }}</div>
                            <div class="ann-meta">
                                <span class="ann-date">{{ \Carbon\Carbon::parse($ann->published_at)->format('d.m.Y') }}</span>
                                <span class="ann-author">· {{ $ann->user->name }}</span>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('admin.announcements.destroy', $ann->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete" onclick="return confirm('Silmek istediğinize emin misiniz?')">Sil</button>
                        </form>
                    </div>
                    <div class="ann-content">{{ $ann->content }}</div>
                    <div class="ann-footer">
                        @if($ann->target == 'all')
                            <span class="badge all">Herkese</span>
                        @elseif($ann->target == 'student')
                            <span class="badge student">Öğrencilere</span>
                        @elseif($ann->target == 'staff')
                            <span class="badge staff">Personele</span>
                        @elseif($ann->target == 'parent')
                            <span class="badge parent">Velilere</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif

@endsection
