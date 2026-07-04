<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
</head>
<body>
<h1>Veli Paneli</h1>
<p>Hoş geldiniz, {{ Auth::user()->name }}!</p>
<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">Çıkış Yap</button>
</form>
</body>
</html>
