@extends('layouts.admin')

@section('title', 'Yemekhane')
@section('page-title', 'Yemekhane')

@section('styles')
    <style>
        .top-bar{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;}
        .month-select{padding:9px 14px;border:0.5px solid rgba(59,35,20,0.12);border-radius:2px;font-family:'DM Sans',sans-serif;font-size:13px;color:#2c1a0e;background:#fff;outline:none;cursor:pointer;}
        .tabs{display:flex;gap:4px;margin-bottom:20px;}
        .tab-btn{padding:9px 24px;background:#fff;color:rgba(59,35,20,0.4);border:0.5px solid rgba(59,35,20,0.1);border-radius:2px;font-size:12px;font-family:'DM Sans',sans-serif;cursor:pointer;letter-spacing:0.5px;}
        .tab-btn.active{background:#2c1a0e;color:#f5deb3;border-color:#2c1a0e;}
        .meal-table{width:100%;border-collapse:collapse;background:#fff;border-radius:6px;overflow:hidden;border:0.5px solid rgba(59,35,20,0.06);}
        .meal-table th{font-size:10px;color:rgba(59,35,20,0.3);letter-spacing:1.5px;text-transform:uppercase;padding:12px 20px;text-align:left;border-bottom:0.5px solid rgba(59,35,20,0.06);background:#fff;}
        .meal-table td{padding:12px 20px;border-bottom:0.5px solid rgba(59,35,20,0.04);font-size:13px;color:rgba(59,35,20,0.6);vertical-align:middle;}
        .meal-table tr:last-child td{border-bottom:none;}
        .meal-table tr.today td{background:rgba(59,35,20,0.02);}
        .day-num{font-family:'Fraunces',serif;font-size:16px;font-weight:700;color:#2c1a0e;}
        .day-name{font-size:11px;color:rgba(59,35,20,0.3);margin-top:1px;}
        .menu-text{color:rgba(59,35,20,0.65);}
        .menu-empty{color:rgba(59,35,20,0.2);font-style:italic;}
        .today-dot{display:inline-block;width:6px;height:6px;border-radius:50%;background:#2c1a0e;margin-right:6px;vertical-align:middle;}
        .btn-edit-sm{padding:4px 12px;border-radius:2px;font-size:10px;font-family:'DM Sans',sans-serif;background:rgba(59,35,20,0.06);color:rgba(59,35,20,0.5);border:none;cursor:pointer;text-decoration:none;}
        .success{background:rgba(34,197,94,0.1);color:rgba(21,128,61,0.8);padding:12px 18px;border-radius:4px;font-size:12px;margin-bottom:18px;}
        .modal-overlay{display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.4);z-index:100;align-items:center;justify-content:center;}
        .modal-box{background:#fff;border-radius:6px;padding:28px;width:480px;}
        .modal-title{font-family:'Fraunces',serif;font-size:18px;font-weight:700;color:#2c1a0e;margin-bottom:4px;}
        .modal-sub{font-size:11px;color:rgba(59,35,20,0.3);margin-bottom:20px;}
        .form-group{margin-bottom:16px;}
        .form-label{display:block;font-size:10px;color:rgba(59,35,20,0.35);letter-spacing:1.5px;text-transform:uppercase;margin-bottom:7px;}
        .form-textarea{width:100%;padding:10px 14px;border:0.5px solid rgba(59,35,20,0.12);border-radius:2px;font-family:'DM Sans',sans-serif;font-size:13px;color:#2c1a0e;background:#faf8f5;outline:none;resize:vertical;min-height:80px;line-height:1.6;}
        .form-textarea:focus{border-color:rgba(59,35,20,0.35);}
        .modal-actions{display:flex;gap:10px;margin-top:20px;}
        .btn-save{padding:10px 24px;background:#2c1a0e;color:#f5deb3;border:none;border-radius:2px;font-family:'DM Sans',sans-serif;font-size:12px;font-weight:500;letter-spacing:1px;cursor:pointer;}
        .btn-cancel{padding:10px 20px;background:transparent;color:rgba(59,35,20,0.4);border:0.5px solid rgba(59,35,20,0.12);border-radius:2px;font-family:'DM Sans',sans-serif;font-size:12px;cursor:pointer;}
    </style>
@endsection

@section('content')

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    <div class="top-bar">
        <select class="month-select" onchange="window.location.href='{{ route('admin.meals') }}?month='+this.value">
            @for($i = 1; $i <= 12; $i++)
                @php $m = '2026' . '-' . str_pad($i, 2, '0', STR_PAD_LEFT); @endphp
                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::parse($m . '-01')->locale('tr')->isoFormat('MMMM YYYY') }}
                </option>
            @endfor
        </select>
    </div>

    <div class="tabs">
        <button class="tab-btn active" id="tab-breakfast" onclick="switchTab('breakfast', this)">Kahvaltı</button>
        <button class="tab-btn" id="tab-dinner" onclick="switchTab('dinner', this)">Akşam Yemeği</button>
    </div>

    <table class="meal-table">
        <thead>
        <tr>
            <th style="width:100px;">Tarih</th>
            <th id="meal-header">Kahvaltı</th>
            <th style="width:80px;"></th>
        </tr>
        </thead>
        <tbody>
        @foreach($days as $day)
            @php
                $carbon = \Carbon\Carbon::parse($day['date']);
                $isToday = $carbon->isToday();
            @endphp
            <tr class="{{ $isToday ? 'today' : '' }}">
                <td>
                    @if($isToday)<span class="today-dot"></span>@endif
                    <div class="day-num">{{ $carbon->format('d') }}</div>
                    <div class="day-name">{{ $carbon->locale('tr')->isoFormat('ddd') }}</div>
                </td>
                <td>
                    <span class="breakfast-cell {{ $day['breakfast'] ? 'menu-text' : 'menu-empty' }}">
                        {{ $day['breakfast'] ?: 'Menü girilmemiş' }}
                    </span>
                                        <span class="dinner-cell {{ $day['dinner'] ? 'menu-text' : 'menu-empty' }}" style="display:none;">
                        {{ $day['dinner'] ?: 'Menü girilmemiş' }}
                    </span>
                    @if(isset($day['is_served']) && $day['is_served'])
                        <span style="display:inline-block;margin-top:4px;font-size:10px;color:rgba(21,128,61,0.8);background:rgba(34,197,94,0.1);padding:2px 8px;border-radius:2px;">✓ Servis edildi</span>
                    @endif
                </td>
                <td>
                    <button class="btn-edit-sm" onclick="openModal('{{ $day['date'] }}', '{{ addslashes($day['breakfast']) }}', '{{ addslashes($day['dinner']) }}', '{{ $carbon->locale('tr')->isoFormat('D MMMM dddd') }}')">Düzenle</button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{-- Düzenleme modal --}}
    <div id="edit-modal" class="modal-overlay">
        <div class="modal-box">
            <div class="modal-title" id="modal-date-title"></div>
            <div class="modal-sub">Menü bilgilerini düzenle</div>
            <form method="POST" action="{{ route('admin.meals.monthly.store') }}" id="modal-form">
                @csrf
                <input type="hidden" name="modal_date" id="modal-date-input">
                <input type="hidden" name="modal_tab" id="modal-tab-input" value="breakfast">
                <div class="form-group" id="breakfast-group">
                    <label class="form-label">Kahvaltı</label>
                    <textarea name="modal_breakfast" id="modal-breakfast" class="form-textarea" placeholder="Kahvaltı menüsü..."></textarea>
                </div>
                <div class="form-group" id="dinner-group">
                    <label class="form-label">Akşam Yemeği</label>
                    <textarea name="modal_dinner" id="modal-dinner" class="form-textarea" placeholder="Akşam yemeği menüsü..."></textarea>
                </div>
                <div class="modal-actions">
                    <button type="submit" class="btn-save">Kaydet</button>
                    <button type="button" class="btn-cancel" onclick="closeModal()">İptal</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        let currentTab = 'breakfast';

        function switchTab(tab, btn) {
            currentTab = tab;
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            document.getElementById('meal-header').textContent = tab === 'breakfast' ? 'Kahvaltı' : 'Akşam Yemeği';
            document.querySelectorAll('.breakfast-cell').forEach(el => el.style.display = tab === 'breakfast' ? '' : 'none');
            document.querySelectorAll('.dinner-cell').forEach(el => el.style.display = tab === 'dinner' ? '' : 'none');
        }

        function openModal(date, breakfast, dinner, dateLabel) {
            document.getElementById('modal-date-title').textContent = dateLabel;
            document.getElementById('modal-date-input').value = date;
            document.getElementById('modal-breakfast').value = breakfast;
            document.getElementById('modal-dinner').value = dinner;
            document.getElementById('modal-tab-input').value = currentTab;

            const breakfastGroup = document.getElementById('breakfast-group');
            const dinnerGroup = document.getElementById('dinner-group');

            if (currentTab === 'breakfast') {
                breakfastGroup.style.display = '';
                dinnerGroup.style.display = 'none';
            } else {
                breakfastGroup.style.display = 'none';
                dinnerGroup.style.display = '';
            }

            document.getElementById('edit-modal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('edit-modal').style.display = 'none';
        }

        document.addEventListener('click', function(e) {
            if (e.target === document.getElementById('edit-modal')) closeModal();
        });

        // Sayfa yüklenince sekmeyi ayarla
        const urlTab = new URLSearchParams(window.location.search).get('tab');
        if (urlTab === 'dinner') {
            document.getElementById('tab-dinner').click();
        }
    </script>
@endsection
