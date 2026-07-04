<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff;
use App\Models\User;
use App\Models\DutySchedule;
use App\Models\Student;
use App\Models\EntryLog;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    // Admin - personel listesi
    public function index()
    {
        $staff = Staff::with('user')->latest()->get();
        return view('admin.staff.index', compact('staff'));
    }

    // Admin - personel ekleme formu
    public function create()
    {
        return view('admin.staff.create');
    }

    // Admin - personel kaydet
    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string',
            'email'      => 'required|email|unique:users',
            'tc_no'      => 'required|size:11|unique:staff',
            'phone'      => 'required',
            'department' => 'required|in:security,kitchen,cleaning',
            'start_date' => 'required|date',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->tc_no),
            'role'     => 'staff',
        ]);

        Staff::create([
            'user_id'    => $user->id,
            'tc_no'      => $request->tc_no,
            'phone'      => $request->phone,
            'email'      => $request->email,
            'department' => $request->department,
            'start_date' => $request->start_date,
        ]);

        return redirect('/admin/staff')->with('success', 'Personel eklendi.');
    }

    // Admin - personel sil
    public function destroy($id)
    {
        $staff = Staff::findOrFail($id);
        $staff->user->delete();
        return redirect('/admin/staff')->with('success', 'Personel silindi.');
    }

    // Admin - nöbet takvimi
    public function dutyIndex()
    {
        $duties = DutySchedule::with('staff.user')->orderBy('duty_date', 'desc')->get();
        $staff  = Staff::with('user')->get();
        return view('admin.duty.index', compact('duties', 'staff'));
    }

    // Admin - nöbet ekle
    public function dutyStore(Request $request)
    {
        $request->validate([
            'staff_id'   => 'required|exists:staff,id',
            'duty_date'  => 'required|date',
            'start_time' => 'required',
            'end_time'   => 'required',
            'location'   => 'nullable|string',
        ]);

        DutySchedule::create($request->only(['staff_id', 'duty_date', 'start_time', 'end_time', 'location']));

        return redirect('/admin/duty')->with('success', 'Nöbet eklendi.');
    }

    // Admin - nöbet sil
    public function dutyDestroy($id)
    {
        DutySchedule::findOrFail($id)->delete();
        return redirect('/admin/duty')->with('success', 'Nöbet silindi.');
    }

    // Güvenlik personeli dashboard
    public function dashboard()
    {
        $user    = auth()->user();
        $staff   = $user->staff;
        $today   = today()->toDateString();

        $todayDuty = DutySchedule::where('staff_id', $staff->id)
            ->where('duty_date', $today)
            ->first();

        $activeLogs    = collect();
        $students      = collect();
        $todayMenu     = null;
        $monthlyMenus  = collect();
        $cleaningTasks = collect();
        $monthDuties   = collect();

        if ($staff->department === 'security') {
            $activeLogs = EntryLog::with('student.user', 'student.room')
                ->whereNull('entry_time')
                ->latest()
                ->get();
            $students = Student::with('user', 'room')->get();
            $recentLogs = EntryLog::with('student.user', 'student.room')
                ->whereNotNull('entry_time')
                ->latest()
                ->take(5)
                ->get();
        } else {
            $recentLogs = collect();
        }

        if ($staff->department === 'kitchen') {
            $todayMenu = \App\Models\MealMenu::where('date', $today)->first();
            $monthlyMenus = \App\Models\MealMenu::whereYear('date', now()->year)
                ->whereMonth('date', now()->month)
                ->orderBy('date')
                ->get()
                ->keyBy('date');
        }

        if ($staff->department === 'cleaning') {
            $cleaningTasks = collect(); // artık kullanmıyoruz
            $monthDuties = DutySchedule::where('staff_id', $staff->id)
                ->whereYear('duty_date', now()->year)
                ->whereMonth('duty_date', now()->month)
                ->orderBy('duty_date')
                ->get();
        }

        return view('staff.dashboard', compact('staff', 'todayDuty', 'activeLogs', 'students', 'todayMenu', 'cleaningTasks', 'monthlyMenus', 'monthDuties', 'recentLogs'));
    }

    // Güvenlik - çıkış kaydı ekle
    public function entryLogStore(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'exit_time'  => 'required',
        ]);

        $exitTime = \Carbon\Carbon::parse($request->exit_time);

        if ($exitTime->format('H:i') < '06:30') {
            return back()->withErrors(['exit_time' => 'En erken çıkış saati 06:30\'dur.']);
        }

        EntryLog::create([
            'student_id' => $request->student_id,
            'exit_time'  => $exitTime,
            'is_late'    => false,
        ]);

        return redirect('/staff/dashboard')->with('success', 'Çıkış kaydedildi.');
    }

    // Güvenlik - giriş kaydı ekle
    public function entryLogUpdate(Request $request, $id)
    {
        $request->validate([
            'entry_time' => 'required',
        ]);

        $log       = EntryLog::findOrFail($id);
        $entryTime = \Carbon\Carbon::parse($request->entry_time);
        $isLate    = $entryTime->format('H:i') > '23:00';

        $log->update([
            'entry_time' => $entryTime,
            'is_late'    => $isLate,
        ]);

        return redirect('/staff/dashboard')->with('success', 'Giriş kaydedildi.' . ($isLate ? ' Öğrenci geç geldi!' : ''));
    }

    // Admin - personel düzenleme formu
    public function edit($id)
    {
        $staff = Staff::with('user')->findOrFail($id);
        return view('admin.staff.edit', compact('staff'));
    }

    // Admin - personel güncelle
    public function update(Request $request, $id)
    {
        $staff = Staff::findOrFail($id);

        $request->validate([
            'name'       => 'required|string',
            'phone'      => 'required',
            'department' => 'required|in:security,kitchen,cleaning',
            'start_date' => 'required|date',
            'email' => 'required|email|unique:users,email,' . $staff->user->id,
            'tc_no' => 'required|size:11|unique:staff,tc_no,' . $id,
        ]);

        $staff->user->update(['name' => $request->name, 'email' => $request->email]);
        $staff->update([
            'phone'      => $request->phone,
            'department' => $request->department,
            'tc_no'      => $request->tc_no,
            'start_date' => $request->start_date,
        ]);

        // TC değiştiyse şifreyi de güncelle
        if ($request->tc_no !== $staff->tc_no) {
            $staff->user->update(['password' => Hash::make($request->tc_no)]);
        }

        return redirect('/admin/staff')->with('success', 'Personel güncellendi.');
    }

    // Admin - nöbet düzenleme formu
    public function dutyEdit($id)
    {
        $duty  = DutySchedule::with('staff.user')->findOrFail($id);
        $staff = Staff::with('user')->get();
        return view('admin.duty.edit', compact('duty', 'staff'));
    }

    // Admin - nöbet güncelle
    public function dutyUpdate(Request $request, $id)
    {
        $request->validate([
            'staff_id'   => 'required|exists:staff,id',
            'duty_date'  => 'required|date',
            'start_time' => 'required',
            'end_time'   => 'required',
        ]);

        DutySchedule::findOrFail($id)->update($request->only(['staff_id', 'duty_date', 'start_time', 'end_time', 'location']));

        return redirect('/admin/duty')->with('success', 'Nöbet güncellendi.');
    }

    // Admin - temizlik görevleri listesi
    public function cleaningIndex()
    {
        $tasks = \App\Models\CleaningTask::with('staff.user')->orderBy('task_date', 'desc')->get();
        $cleaningStaff = Staff::with('user')->where('department', 'cleaning')->get();
        return view('admin.cleaning.index', compact('tasks', 'cleaningStaff'));
    }

// Admin - temizlik görevi ekle
    public function cleaningStore(Request $request)
    {
        $request->validate([
            'staff_id'  => 'required|exists:staff,id',
            'task_date' => 'required|date',
            'location'  => 'required|string',
            'note'      => 'nullable|string',
        ]);

        \App\Models\CleaningTask::create([
            'staff_id'  => $request->staff_id,
            'task_date' => $request->task_date,
            'location'  => $request->location,
            'note'      => $request->note,
            'status'    => 'pending',
        ]);

        return redirect('/admin/cleaning')->with('success', 'Görev eklendi.');
    }

// Admin - temizlik görevi sil
    public function cleaningDestroy($id)
    {
        \App\Models\CleaningTask::findOrFail($id)->delete();
        return redirect('/admin/cleaning')->with('success', 'Görev silindi.');
    }

// Temizlik personeli - görevi tamamlandı işaretle
    public function cleaningDone($id)
    {
        $task = \App\Models\CleaningTask::findOrFail($id);
        $task->update(['status' => 'done']);
        return redirect('/staff/dashboard')->with('success', 'Görev tamamlandı olarak işaretlendi.');
    }

    // Yemekhane - yemek servis edildi işaretle
    public function mealServe($id)
    {
        $menu = \App\Models\MealMenu::findOrFail($id);
        $menu->update(['is_served' => !$menu->is_served]); // tıklayınca açık/kapalı geçiş yapar
        return redirect('/staff/dashboard')->with('success', 'Güncellendi.');
    }

    // Personel - nöbeti tamamlandı işaretle
    public function dutyDone($id)
    {
        $duty = DutySchedule::findOrFail($id);
        $duty->update(['is_done' => !$duty->is_done]);
        return redirect('/staff/dashboard')->with('success', 'Güncellendi.');
    }
}
