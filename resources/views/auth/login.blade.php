<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş Yap · YurtYönet</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,900;1,9..144,300&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *{box-sizing:border-box;margin:0;padding:0;}
        body{min-height:100vh;background:#3b2314;display:flex;flex-direction:column;align-items:center;justify-content:center;position:relative;}
        .top-border{position:absolute;top:0;left:6%;right:6%;height:1px;background:linear-gradient(90deg,transparent,rgba(245,220,180,0.3) 30%,rgba(245,220,180,0.3) 70%,transparent);}
        .glow{position:absolute;top:-60px;left:50%;transform:translateX(-50%);width:600px;height:300px;background:radial-gradient(ellipse,rgba(245,220,180,0.06) 0%,transparent 70%);pointer-events:none;}
        .card{width:100%;max-width:400px;position:relative;z-index:2;padding:20px;}
        .back{display:inline-flex;align-items:center;gap:6px;font-family:'DM Sans',sans-serif;font-size:10px;color:rgba(245,220,180,0.25);letter-spacing:2px;text-transform:uppercase;text-decoration:none;margin-bottom:32px;}
        .back:hover{color:rgba(245,220,180,0.5);}
        .eyebrow{display:flex;align-items:center;gap:12px;margin-bottom:16px;}
        .eyebrow-line{width:30px;height:0.5px;background:rgba(245,220,180,0.2);}
        .eyebrow-text{font-family:'DM Sans',sans-serif;font-size:9px;font-weight:300;color:rgba(245,220,180,0.3);letter-spacing:4px;text-transform:uppercase;}
        h1{font-family:'Fraunces',serif;font-size:52px;font-weight:900;color:#f5deb3;line-height:0.9;margin-bottom:32px;}
        h1 span{font-style:italic;font-weight:300;color:rgba(245,222,179,0.2);}
        .field{margin-bottom:20px;}
        .field label{display:block;font-family:'DM Sans',sans-serif;font-size:9px;font-weight:400;color:rgba(245,220,180,0.3);letter-spacing:3px;text-transform:uppercase;margin-bottom:8px;}
        .field input{width:100%;height:46px;background:rgba(245,220,180,0.05);border:0.5px solid rgba(245,220,180,0.12);border-radius:2px;padding:0 16px;font-family:'DM Sans',sans-serif;font-size:13px;color:#f5deb3;outline:none;}
        .field input::placeholder{color:rgba(245,220,180,0.2);}
        .field input:focus{border-color:rgba(245,220,180,0.3);background:rgba(245,220,180,0.08);}
        .error{font-family:'DM Sans',sans-serif;font-size:11px;color:rgba(220,100,80,0.8);margin-bottom:16px;letter-spacing:0.5px;}
        .btn{width:100%;height:46px;background:rgba(245,220,180,0.9);color:#3b2314;border:none;border-radius:2px;font-family:'DM Sans',sans-serif;font-size:11px;font-weight:500;letter-spacing:2px;text-transform:uppercase;cursor:pointer;margin-top:8px;}
        .btn:hover{background:#f5deb3;}
        .divider{display:flex;align-items:center;gap:16px;margin:24px 0 0;}
        .div-line{flex:1;height:0.5px;background:rgba(245,220,180,0.08);}
        .div-diamond{width:4px;height:4px;background:rgba(245,220,180,0.2);transform:rotate(45deg);}
        .roles{display:flex;gap:8px;justify-content:center;margin-top:16px;flex-wrap:wrap;}
        .role{font-size:9px;font-family:'DM Sans',sans-serif;color:rgba(245,220,180,0.15);letter-spacing:1.5px;text-transform:uppercase;}
        .role::after{content:'·';margin-left:8px;}
        .role:last-child::after{content:'';}
    </style>
</head>
<body>
<div class="top-border"></div>
<div class="glow"></div>

<div class="card">
    <a href="/" class="back">← Geri</a>

    <div class="eyebrow">
        <div class="eyebrow-line"></div>
        <span class="eyebrow-text">YurtYönet</span>
    </div>

    <h1>Giriş<br><span>Yap</span></h1>

    @if ($errors->any())
        <p class="error">{{ $errors->first() }}</p>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="field">
            <label>E-posta</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="ornek@yurt.com" required>
        </div>
        <div class="field">
            <label>Şifre</label>
            <input type="password" name="password" placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn">Giriş Yap →</button>
    </form>

    <div class="divider">
        <div class="div-line"></div>
        <div class="div-diamond"></div>
        <div class="div-line"></div>
    </div>
    <div class="roles">
        <span class="role">Admin</span>
        <span class="role">Personel</span>
        <span class="role">Öğrenci</span>
        <span class="role">Veli</span>
    </div>
</div>
</body>
</html>
