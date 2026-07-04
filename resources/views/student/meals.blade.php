@extends('layouts.student')

@section('title', 'Yemekhane')
@section('page-title', 'Yemekhane')

@section('styles')
    <style>
        .menu-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:14px;}
        .menu-card{background:#fff;border-radius:6px;border:0.5px solid rgba(59,35,20,0.06);overflow:hidden;}
        .menu-date{background:#2c1a0e;padding:14px 20px;display:flex;align-items:center;justify-content:space-between;}
        .menu-date-text{font-family:'Fraunces',serif;font-size:16px;font-weight:700;color:#f5deb3;}
        .menu-day{font-size:10px;color:rgba(245,220,180,0.3);letter-spacing:2px;text-transform:uppercase;margin-top:2px;}
        .today-badge{font-size:9px;background:rgba(245,220,180,0.15);color:rgba(245,220,180,0.6);padding:3px 8px;border-radius:2px;letter-spacing:1px;}
        .menu-body{padding:16px 20px;display:flex;flex-direction:column;gap:14px;}
        .meal-label{font-size:9px;color:rgba(59,35,20,0.25);letter-spacing:2px;text-transform:uppercase;margin-bottom:5px;}
        .meal-text{font-size:13px;color:rgba(59,35,20,0.65);line-height:1.6;}
        .divider{height:0.5px;background:rgba(59,35,20,0.05);}
        .empty{text-align:center;color:rgba(59,35,20,0.2);font-size:12px;padding:40px;background:#fff;border-radius:6px;}
        .count-text{font-size:13px;color:rgba(59,35,20,0.3);margin-bottom:16px;}
    </style>
@endsection

@section('content')

    @if($menus->isEmpty())
        <div class="empty">Henüz menü eklenmemiş.</div>
    @else
        <div class="count-text">Toplam {{ $menus->count() }} gün kayıtlı</div>
        <div class="menu-grid">
            @foreach($menus as $menu)
                <div class="menu-card">
                    <div class="menu-date">
                        <div>
                            <div class="menu-date-text">{{ \Carbon\Carbon::parse($menu->date)->format('d.m.Y') }}</div>
                            <div class="menu-day">{{ \Carbon\Carbon::parse($menu->date)->locale('tr')->isoFormat('dddd') }}</div>
                        </div>
                        @if(\Carbon\Carbon::parse($menu->date)->isToday())
                            <span class="today-badge">BUGÜN</span>
                        @endif
                    </div>
                    <div class="menu-body">
                        <div>
                            <div class="meal-label">Kahvaltı</div>
                            <div class="meal-text">{{ $menu->breakfast }}</div>
                        </div>
                        <div class="divider"></div>
                        <div>
                            <div class="meal-label">Akşam Yemeği</div>
                            <div class="meal-text">{{ $menu->dinner }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

@endsection
