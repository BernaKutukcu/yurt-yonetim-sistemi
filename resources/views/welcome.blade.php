<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YurtYönet</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,900;1,9..144,300&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *{box-sizing:border-box;margin:0;padding:0;}
        body{min-height:100vh;background:#3b2314;display:flex;flex-direction:column;position:relative;overflow:hidden;}
        .top-border{position:absolute;top:0;left:6%;right:6%;height:1px;background:linear-gradient(90deg,transparent,rgba(245,220,180,0.3) 30%,rgba(245,220,180,0.3) 70%,transparent);}
        .glow{position:absolute;top:-60px;left:50%;transform:translateX(-50%);width:600px;height:300px;background:radial-gradient(ellipse,rgba(245,220,180,0.06) 0%,transparent 70%);pointer-events:none;}
        .glow2{position:absolute;bottom:-80px;right:-60px;width:350px;height:350px;background:radial-gradient(ellipse,rgba(200,160,100,0.05) 0%,transparent 70%);pointer-events:none;}
        nav{display:flex;align-items:center;justify-content:space-between;padding:28px 44px;position:relative;z-index:2;}
        .nav-logo{font-family:'DM Sans',sans-serif;font-size:10px;font-weight:300;color:rgba(245,220,180,0.3);letter-spacing:5px;text-transform:uppercase;text-decoration:none;}
        .nav-right{display:flex;gap:10px;}
        .btn-outline{padding:8px 20px;background:transparent;color:rgba(245,220,180,0.4);border:0.5px solid rgba(245,220,180,0.15);border-radius:2px;font-size:11px;font-family:'DM Sans',sans-serif;cursor:pointer;letter-spacing:0.5px;text-decoration:none;}
        .btn-fill{padding:8px 20px;background:rgba(245,220,180,0.9);color:#3b2314;border:none;border-radius:2px;font-size:11px;font-family:'DM Sans',sans-serif;font-weight:500;cursor:pointer;letter-spacing:0.5px;text-decoration:none;}
        .hero{flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:0 44px 20px;position:relative;z-index:2;text-align:center;}
        .eyebrow{display:flex;align-items:center;gap:14px;margin-bottom:28px;}
        .eyebrow-line{width:40px;height:0.5px;background:rgba(245,220,180,0.2);}
        .eyebrow-text{font-family:'DM Sans',sans-serif;font-size:10px;font-weight:300;color:rgba(245,220,180,0.3);letter-spacing:4px;text-transform:uppercase;}
        .title{font-family:'Fraunces',serif;font-weight:900;line-height:0.88;margin-bottom:28px;}
        .t1,.t3{font-size:clamp(60px,10vw,100px);color:#f5deb3;display:block;}
        .t2{font-size:clamp(60px,10vw,100px);color:rgba(245,222,179,0.1);display:block;font-style:italic;font-weight:300;}
        .divider{display:flex;align-items:center;gap:16px;margin-bottom:24px;}
        .div-line{width:50px;height:0.5px;background:rgba(245,220,180,0.2);}
        .div-diamond{width:5px;height:5px;background:rgba(245,220,180,0.4);transform:rotate(45deg);}
        .sub{font-family:'DM Sans',sans-serif;font-size:12px;font-weight:300;color:rgba(245,220,180,0.2);letter-spacing:3px;text-transform:uppercase;margin-bottom:36px;}
        .cta-row{display:flex;gap:12px;}
        .c1{padding:13px 34px;background:rgba(245,220,180,0.9);color:#3b2314;border:none;border-radius:2px;font-family:'DM Sans',sans-serif;font-size:11px;font-weight:500;letter-spacing:2px;text-transform:uppercase;cursor:pointer;text-decoration:none;}
        .c2{padding:13px 34px;background:transparent;color:rgba(245,220,180,0.25);border:0.5px solid rgba(245,220,180,0.12);border-radius:2px;font-family:'DM Sans',sans-serif;font-size:11px;letter-spacing:2px;text-transform:uppercase;cursor:pointer;text-decoration:none;}
        footer{padding:18px 44px;border-top:0.5px solid rgba(245,220,180,0.05);display:flex;justify-content:space-between;align-items:center;position:relative;z-index:2;}
        .fl{font-size:9px;font-family:'DM Sans',sans-serif;color:rgba(245,220,180,0.1);letter-spacing:2px;text-transform:uppercase;}
        .dots{display:flex;gap:20px;}
        .dot-item{display:flex;align-items:center;gap:5px;}
        .dot{width:3px;height:3px;background:rgba(245,220,180,0.2);transform:rotate(45deg);}
        .dot-t{font-size:9px;font-family:'DM Sans',sans-serif;color:rgba(245,220,180,0.12);letter-spacing:1.5px;text-transform:uppercase;}
    </style>
</head>
<body>
<div class="top-border"></div>
<div class="glow"></div>
<div class="glow2"></div>

<nav>
    <a href="/" class="nav-logo">YurtYönet</a>
    <div class="nav-right">
        <a href="{{ route('login') }}" class="btn-outline">Giriş Yap</a>
    </div>
</nav>

<div class="hero">
    <div class="eyebrow">
        <div class="eyebrow-line"></div>
        <span class="eyebrow-text">Dijital Yönetim Platformu</span>
        <div class="eyebrow-line"></div>
    </div>
    <div class="title">
        <span class="t1">Yurt</span>
        <span class="t2">Yönetim</span>
        <span class="t3">Sistemi</span>
    </div>
    <div class="divider">
        <div class="div-line"></div>
        <div class="div-diamond"></div>
        <div class="div-line"></div>
    </div>
    <p class="sub">Öğrenci · Oda · İzin · Ödeme</p>
    <div class="cta-row">
        <a href="{{ route('login') }}" class="c1">Giriş Yap →</a>
    </div>
</div>

<footer>
    <span class="fl">© 2026 YurtYönet</span>
    <div class="dots">
        <div class="dot-item"><div class="dot"></div><span class="dot-t">4 Rol</span></div>
        <div class="dot-item"><div class="dot"></div><span class="dot-t">11 Modül</span></div>
        <div class="dot-item"><div class="dot"></div><span class="dot-t">7/24</span></div>
    </div>
</footer>
</body>
</html>
