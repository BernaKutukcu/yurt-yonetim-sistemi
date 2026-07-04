<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') · YurtYönet</title>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,700;1,9..144,300&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *{box-sizing:border-box;margin:0;padding:0;}
        body{display:flex;min-height:100vh;font-family:'DM Sans',sans-serif;background:#f5f0e8;}
        .sidebar{width:230px;background:#2c1a0e;display:flex;flex-direction:column;min-height:100vh;position:fixed;top:0;left:0;}
        .sb-logo{padding:26px 22px;border-bottom:0.5px solid rgba(245,220,180,0.06);}
        .sb-logo-text{font-family:'Fraunces',serif;font-size:20px;font-weight:700;color:#f5deb3;}
        .sb-logo-sub{font-size:10px;color:rgba(245,220,180,0.2);letter-spacing:2px;text-transform:uppercase;margin-top:3px;}
        .sb-menu{flex:1;padding:12px 0;}
        .sb-section{font-size:9px;color:rgba(245,220,180,0.2);letter-spacing:2px;text-transform:uppercase;padding:14px 22px 7px;}
        .sb-item{display:flex;align-items:center;gap:12px;padding:11px 22px;cursor:pointer;border-left:2px solid transparent;text-decoration:none;}
        .sb-item.active{background:rgba(245,220,180,0.06);border-left-color:rgba(245,220,180,0.4);}
        .sb-item:hover{background:rgba(245,220,180,0.04);}
        .sb-label{font-size:13px;color:rgba(245,220,180,0.35);}
        .sb-item.active .sb-label{color:rgba(245,220,180,0.75);}
        .sb-icon{width:16px;height:16px;flex-shrink:0;}
        .sb-item svg{color:rgba(245,220,180,0.35);}
        .sb-item.active svg{color:rgba(245,220,180,0.75);}
        .sb-bottom{padding:18px 22px;border-top:0.5px solid rgba(245,220,180,0.06);}
        .sb-user{display:flex;align-items:center;gap:10px;position:relative;cursor:pointer;}
        .sb-avatar{width:32px;height:32px;border-radius:50%;background:rgba(245,220,180,0.1);display:flex;align-items:center;justify-content:center;font-size:13px;color:rgba(245,220,180,0.5);font-weight:500;flex-shrink:0;}
        .sb-uname{font-size:13px;color:rgba(245,220,180,0.4);}
        .sb-urole{font-size:10px;color:rgba(245,220,180,0.2);letter-spacing:1px;text-transform:uppercase;}
        .logout-menu{display:none;position:absolute;bottom:50px;left:0;right:0;background:#1a0e06;border:0.5px solid rgba(245,220,180,0.1);border-radius:4px;padding:4px 0;}
        .logout-menu button{width:100%;background:transparent;border:none;padding:10px 16px;text-align:left;font-family:'DM Sans',sans-serif;font-size:12px;color:rgba(245,220,180,0.4);cursor:pointer;letter-spacing:0.5px;}
        .logout-menu button:hover{color:rgba(245,220,180,0.7);}
        .main{margin-left:230px;flex:1;display:flex;flex-direction:column;}
        .topbar{display:flex;align-items:center;justify-content:space-between;padding:20px 30px;background:#f5f0e8;border-bottom:0.5px solid rgba(59,35,20,0.08);position:sticky;top:0;z-index:10;}
        .topbar-title{font-family:'Fraunces',serif;font-size:26px;font-weight:700;color:#2c1a0e;}
        .topbar-date{font-size:12px;color:rgba(59,35,20,0.3);}
        .content{padding:26px 30px;}
    </style>
    <script>
        setTimeout(function() {
            const flash = document.querySelector('.success, .errors');
            if (flash) flash.style.display = 'none';
        }, 3000);
    </script>
    @yield('styles')
</head>
<body>
<div class="sidebar">
    <div class="sb-logo">
        <div class="sb-logo-text">YurtYönet</div>
        <div class="sb-logo-sub">Admin Paneli</div>
    </div>
    <div class="sb-menu">
        <div class="sb-section">Genel</div>
        <a href="/admin/dashboard" class="sb-item {{ request()->is('admin/dashboard') ? 'active' : '' }}">
            <svg class="sb-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
            <span class="sb-label">Anasayfa</span>
        </a>
        <div class="sb-section">Yönetim</div>
        <a href="{{ route('admin.students') }}" class="sb-item {{ request()->is('admin/students*') ? 'active' : '' }}">
            <svg class="sb-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
            <span class="sb-label">Öğrenciler</span>
        </a>
        <a href="{{ route('admin.rooms') }}" class="sb-item {{ request()->is('admin/rooms*') ? 'active' : '' }}">
            <svg class="sb-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="5" width="18" height="16" rx="1"/><path d="M8 5V3M16 5V3M3 10h18"/></svg>
            <span class="sb-label">Odalar</span>
        </a>
        <a href="{{ route('admin.leaves') }}" class="sb-item {{ request()->is('admin/leaves*') ? 'active' : '' }}">
            <svg class="sb-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg>
            <span class="sb-label">İzinler</span>
        </a>
        <a href="{{ route('admin.payments') }}" class="sb-item {{ request()->is('admin/payments*') ? 'active' : '' }}">
            <svg class="sb-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg>
            <span class="sb-label">Ödemeler</span>
        </a>
        <a href="{{ route('admin.meals') }}" class="sb-item {{ request()->is('admin/meals*') ? 'active' : '' }}">
            <svg class="sb-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 6h18M3 12h18M3 18h12"/></svg>
            <span class="sb-label">Yemekhane</span>
        </a>
        <a href="{{ route('admin.announcements') }}" class="sb-item {{ request()->is('admin/announcements*') ? 'active' : '' }}">
            <svg class="sb-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0"/></svg>
            <span class="sb-label">Duyurular</span>
        </a>
        <a href="{{ route('admin.entry-logs') }}" class="sb-item {{ request()->is('admin/entry-logs*') ? 'active' : '' }}">
            <svg class="sb-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/></svg>
            <span class="sb-label">Giriş-Çıkış</span>
        </a>
        <a href="{{ route('admin.tickets') }}" class="sb-item {{ request()->is('admin/tickets*') ? 'active' : '' }}">
            <svg class="sb-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 9v4M12 17h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
            <span class="sb-label">Arıza & Şikayet</span>
        </a>
        <div class="sb-section">Sistem</div>
        <a href="{{ route('admin.staff') }}" class="sb-item {{ request()->is('admin/staff*') ? 'active' : '' }}">
            <svg class="sb-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
            <span class="sb-label">Personel</span>
        </a>
        <a href="{{ route('admin.duty') }}" class="sb-item {{ request()->is('admin/duty*') ? 'active' : '' }}">
            <svg class="sb-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
            <span class="sb-label">Nöbet Takvimi</span>
        </a>
        <a href="{{ route('admin.settings') }}" class="sb-item {{ request()->is('admin/settings*') ? 'active' : '' }}">
            <svg class="sb-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/></svg>
            <span class="sb-label">Ayarlar</span>
        </a>
    </div>
    <div class="sb-bottom">
        <div class="sb-user" onclick="toggleLogout()">
            <div class="sb-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
            <div>
                <div class="sb-uname">{{ Auth::user()->name }}</div>
                <div class="sb-urole">Yönetici</div>
            </div>
            <div class="logout-menu" id="logout-menu">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">Çıkış Yap →</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="main">
    <div class="topbar">
        <div class="topbar-title">@yield('page-title')</div>
        <div class="topbar-date">{{ now()->locale('tr')->isoFormat('D MMMM Y') }}</div>
    </div>
    <div class="content">
        @yield('content')
    </div>
</div>

<script>
    function toggleLogout() {
        const menu = document.getElementById('logout-menu');
        menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
    }
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.sb-user')) {
            document.getElementById('logout-menu').style.display = 'none';
        }
    });
</script>
@yield('scripts')
</body>
</html>
