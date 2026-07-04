<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EntryLog;
use App\Models\Student;
use Carbon\Carbon;

class EntryLogController extends Controller
{
    // Giriş-çıkış listesi
    public function index()
    {
        $logs     = EntryLog::with('student.user')->latest()->get();
        $students = Student::with('user')->get();
        return view('admin.entry_logs.index', compact('logs', 'students'));
    }

    // Çıkış kaydı ekle
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'exit_time'  => 'required',
        ]);

        $exitTime = Carbon::parse($request->exit_time);

        // En erken çıkış 06:30
        if ($exitTime->format('H:i') < '06:30') {
            return back()->withErrors(['exit_time' => 'En erken çıkış saati 06:30\'dur.'])->withInput();
        }

        EntryLog::create([
            'student_id' => $request->student_id,
            'exit_time'  => $exitTime,
            'is_late'    => false,
        ]);

        return redirect('/admin/entry-logs')->with('success', 'Çıkış kaydı oluşturuldu.');
    }

    // Giriş saati işle
    public function markEntry(Request $request, $id)
    {
        $request->validate([
            'entry_time' => 'required',
        ]);

        $log       = EntryLog::findOrFail($id);
        $entryTime = Carbon::parse($request->entry_time);

        // 23:00'dan sonra gelirse geç sayılır
        $isLate = $entryTime->format('H:i') > '23:00';

        $log->update([
            'entry_time' => $entryTime,
            'is_late'    => $isLate,
        ]);

        return redirect('/admin/entry-logs')->with('success', 'Giriş kaydedildi.' . ($isLate ? ' Öğrenci geç geldi!' : ''));
    }
}
