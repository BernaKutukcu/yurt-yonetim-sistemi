@extends('layouts.student')

@section('title', 'Ödemelerim')
@section('page-title', 'Ödemelerim')

@section('styles')
    <style>
        .summary{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:24px;}
        .card{background:#fff;border-radius:6px;border:0.5px solid rgba(59,35,20,0.06);padding:20px 22px;}
        .card-label{font-size:9px;color:rgba(59,35,20,0.25);letter-spacing:2px;text-transform:uppercase;margin-bottom:8px;}
        .card-value{font-family:'Fraunces',serif;font-size:22px;font-weight:700;color:#2c1a0e;}
        .card-sub{font-size:11px;color:rgba(59,35,20,0.3);margin-top:4px;}
        .payments{display:flex;flex-direction:column;gap:10px;}
        .payment-card{background:#fff;border-radius:6px;border:0.5px solid rgba(59,35,20,0.06);padding:18px 22px;display:flex;align-items:center;justify-content:space-between;}
        .payment-amount{font-family:'Fraunces',serif;font-size:18px;font-weight:700;color:#2c1a0e;}
        .payment-date{font-size:11px;color:rgba(59,35,20,0.3);margin-top:4px;}
        .badge{display:inline-block;padding:3px 10px;border-radius:2px;font-size:10px;letter-spacing:0.5px;}
        .badge.paid{background:rgba(34,197,94,0.1);color:rgba(21,128,61,0.8);}
        .badge.unpaid{background:rgba(239,68,68,0.08);color:rgba(185,28,28,0.7);}
        .badge.late{background:rgba(234,179,8,0.1);color:rgba(161,98,7,0.8);}
        .empty{text-align:center;color:rgba(59,35,20,0.2);font-size:12px;padding:32px;background:#fff;border-radius:6px;}
        .count-text{font-size:13px;color:rgba(59,35,20,0.3);margin-bottom:14px;}
    </style>
@endsection

@section('content')

    {{-- Özet --}}
    <div class="summary">
        <div class="card">
            <div class="card-label">Toplam Ödeme</div>
            <div class="card-value">{{ $payments->count() }}</div>
            <div class="card-sub">kayıt</div>
        </div>
        <div class="card">
            <div class="card-label">Bekleyen</div>
            <div class="card-value">{{ $payments->where('status', 'unpaid')->count() }}</div>
            <div class="card-sub">ödenmemiş</div>
        </div>
    </div>

    {{-- Ödeme listesi --}}
    @if($payments->isEmpty())
        <div class="empty">Henüz ödeme kaydınız yok.</div>
    @else
        <div class="count-text">Toplam {{ $payments->count() }} kayıt</div>
        <div class="payments">
            @foreach($payments as $payment)
                <div class="payment-card">
                    <div>
                        <div class="payment-amount">{{ number_format($payment->amount, 0, ',', '.') }} ₺</div>
                        <div class="payment-date">Son ödeme: {{ \Carbon\Carbon::parse($payment->due_date)->format('d.m.Y') }}</div>
                        @if($payment->payment_date)
                            <div class="payment-date">Ödendi: {{ \Carbon\Carbon::parse($payment->payment_date)->format('d.m.Y') }}</div>
                        @endif
                    </div>
                    <span class="badge {{ $payment->status }}">
                        @if($payment->status === 'paid') Ödendi
                        @elseif($payment->status === 'unpaid') Bekliyor
                        @else Gecikmiş
                        @endif
                    </span>
                </div>
            @endforeach
        </div>
    @endif

@endsection
