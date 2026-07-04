@extends('layouts.admin')

@section('title', 'Ödeme Ekle')
@section('page-title', 'Ödeme Ekle')

@section('styles')
    <style>
        .form-card{background:#fff;border-radius:6px;border:0.5px solid rgba(59,35,20,0.06);padding:28px;}
        .form-grid{display:grid;grid-template-columns:1fr 1fr;gap:20px;}
        .form-group{display:flex;flex-direction:column;gap:6px;}
        label{font-size:10px;color:rgba(59,35,20,0.4);letter-spacing:1.5px;text-transform:uppercase;}
        input,select{height:42px;border:0.5px solid rgba(59,35,20,0.12);border-radius:2px;padding:0 14px;font-family:'DM Sans',sans-serif;font-size:13px;color:#2c1a0e;background:#faf8f4;outline:none;}
        input:focus,select:focus{border-color:rgba(59,35,20,0.3);}
        .btn-row{display:flex;gap:10px;margin-top:24px;}
        .btn-save{padding:11px 28px;background:#2c1a0e;color:#f5deb3;border:none;border-radius:2px;font-family:'DM Sans',sans-serif;font-size:12px;font-weight:500;letter-spacing:1px;cursor:pointer;}
        .btn-cancel{padding:11px 28px;background:transparent;color:rgba(59,35,20,0.4);border:0.5px solid rgba(59,35,20,0.12);border-radius:2px;font-family:'DM Sans',sans-serif;font-size:12px;cursor:pointer;text-decoration:none;}
        .errors{background:rgba(239,68,68,0.06);border:0.5px solid rgba(239,68,68,0.15);border-radius:4px;padding:12px 16px;margin-bottom:20px;}
        .errors p{font-size:12px;color:rgba(185,28,28,0.7);margin-bottom:4px;}
    </style>
@endsection

@section('content')

    @if($errors->any())
        <div class="errors">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="form-card">
        <form method="POST" action="{{ route('admin.payments.store') }}">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label>Öğrenci</label>
                    <select name="student_id" required>
                        <option value="">— Öğrenci Seç —</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}">{{ $student->user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Tutar (₺)</label>
                    <input type="number" name="amount" value="{{ old('amount') }}" min="0" step="0.01" placeholder="1500.00" required>
                </div>
                <div class="form-group">
                    <label>Son Ödeme Tarihi</label>
                    <select name="due_date" id="due_date_month" onchange="setDueDate(this.value)" required>
                        <option value="">— Ay Seç —</option>
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}">{{ \Carbon\Carbon::create(null, $i)->locale('tr')->isoFormat('MMMM') }}</option>
                        @endfor
                    </select>
                    <input type="hidden" name="due_date" id="due_date" value="{{ old('due_date') }}">
                    <div style="font-size:11px;color:rgba(59,35,20,0.25);margin-top:5px;" id="due_date_preview"></div>
                </div>
                <div class="form-group">
                    <label>Durum</label>
                    <select name="status">
                        <option value="unpaid">Ödenmedi</option>
                        <option value="paid">Ödendi</option>
                        <option value="late">Gecikti</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Ödeme Tarihi (Opsiyonel)</label>
                    <input type="date" name="payment_date" value="{{ old('payment_date') }}">
                </div>
            </div>
            <div class="btn-row">
                <button type="submit" class="btn-save">Kaydet</button>
                <a href="{{ route('admin.payments') }}" class="btn-cancel">İptal</a>
            </div>
        </form>
    </div>

@endsection
@section('scripts')
    <script>
        function setDueDate(month) {
            if (!month) return;
            const year = new Date().getFullYear();
            const date = year + '-' + String(month).padStart(2, '0') + '-30';
            document.getElementById('due_date').value = date;
            document.getElementById('due_date_preview').textContent = '30.' + String(month).padStart(2, '0') + '.' + year;
        }
    </script>
@endsection
