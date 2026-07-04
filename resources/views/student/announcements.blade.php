@extends('layouts.student')

@section('title', 'Duyurular')
@section('page-title', 'Duyurular')

@section('styles')
    <style>
        .announcements{display:flex;flex-direction:column;gap:12px;}
        .announcement-card{background:#fff;border-radius:6px;border:0.5px solid rgba(59,35,20,0.06);padding:20px 24px;}
        .ann-title{font-family:'Fraunces',serif;font-size:18px;font-weight:700;color:#2c1a0e;}
        .ann-meta{display:flex;align-items:center;gap:10px;margin-top:4px;margin-bottom:12px;}
        .ann-date{font-size:11px;color:rgba(59,35,20,0.3);}
        .ann-author{font-size:11px;color:rgba(59,35,20,0.3);}
        .ann-content{font-size:13px;color:rgba(59,35,20,0.6);line-height:1.7;}
        .badge{display:inline-block;padding:3px 10px;border-radius:2px;font-size:10px;letter-spacing:0.5px;margin-top:12px;}
        .badge.all{background:rgba(59,35,20,0.06);color:rgba(59,35,20,0.5);}
        .badge.student{background:rgba(34,197,94,0.1);color:rgba(21,128,61,0.8);}
        .empty{text-align:center;color:rgba(59,35,20,0.2);font-size:12px;padding:40px;background:#fff;border-radius:6px;}
        .count-text{font-size:13px;color:rgba(59,35,20,0.3);margin-bottom:16px;}
    </style>
@endsection

@section('content')

    @if($announcements->isEmpty())
        <div class="empty">Henüz duyuru yok.</div>
    @else
        <div class="count-text">Toplam {{ $announcements->count() }} duyuru</div>
        <div class="announcements">
            @foreach($announcements as $ann)
                <div class="announcement-card">
                    <div class="ann-title">{{ $ann->title }}</div>
                    <div class="ann-meta">
                        <span class="ann-date">{{ \Carbon\Carbon::parse($ann->published_at)->format('d.m.Y') }}</span>
                        <span class="ann-author">· {{ $ann->user->name }}</span>
                    </div>
                    <div class="ann-content">{{ $ann->content }}</div>
                    @if($ann->target === 'all')
                        <span class="badge all">Herkese</span>
                    @else
                        <span class="badge student">Öğrencilere</span>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

@endsection
