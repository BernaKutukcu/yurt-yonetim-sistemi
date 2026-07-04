<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use App\Models\Room;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    // Öğrencileri listele
    public function index(Request $request)
    {
        $query = Student::with('user', 'room')->latest();

        if ($request->block) {
            $query->whereHas('room', function($q) use ($request) {
                $q->where('block', $request->block);
            });
        }

        $students = $query->get();
        return view('admin.students.index', compact('students'));
    }

    // Öğrenci ekleme formu
    public function create()
    {
        $rooms = Room::all();
        return view('admin.students.create', compact('rooms'));
    }

    // Öğrenci kaydet
    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required',
            'email'             => 'required|email|unique:users',
            'tc_no'             => 'required|size:11|unique:students',
            'phone'             => 'required',
            'city'              => 'required',
            'address'           => 'required',
            'department'        => 'required',
            'birth_date'        => 'required|date',
            'iban'              => 'required',
            'mother_name'       => 'required',
            'father_name'       => 'required',
            'parent_phone'      => 'required',
            'registration_date' => 'required|date',
        ]);

        // Yatak dolu mu?
        if ($request->room_id && $request->bed_number) {
            $bedTaken = \App\Models\Student::where('room_id', $request->room_id)
                ->where('bed_number', $request->bed_number)
                ->exists();
            if ($bedTaken) {
                return back()->withErrors(['bed_number' => 'Bu yatak zaten dolu.'])->withInput();
            }
        }

        // Oda kapasitesi doldu mu?
        if ($request->room_id) {
            $room = \App\Models\Room::findOrFail($request->room_id);
            $occupants = \App\Models\Student::where('room_id', $request->room_id)->count();
            if ($occupants >= $room->capacity) {
                return back()->withErrors(['room_id' => 'Bu oda dolu, kapasite aşılamaz.'])->withInput();
            }
        }

        // Önce kullanıcı oluştur
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->tc_no),
            'role'     => 'student',
        ]);

        // Sonra öğrenci profili oluştur
        Student::create([
            'user_id'           => $user->id,
            'tc_no'             => $request->tc_no,
            'phone'             => $request->phone,
            'city'              => $request->city,
            'address'           => $request->address,
            'department'        => $request->department,
            'birth_date'        => $request->birth_date,
            'iban'              => $request->iban,
            'mother_name'       => $request->mother_name,
            'father_name'       => $request->father_name,
            'parent_phone'      => $request->parent_phone,
            'registration_date' => $request->registration_date,
            'room_id'           => $request->room_id ?? null,
            'bed_number'        => $request->bed_number ?? null,
        ]);

        return redirect('/admin/students')->with('success', 'Öğrenci başarıyla eklendi.');
    }

    // Öğrenci düzenleme formu
    public function edit($id)
    {
        $student = Student::with('user')->findOrFail($id);
        $rooms = Room::all();
        return view('admin.students.edit', compact('student', 'rooms'));
    }

    // Öğrenci güncelle
    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        // Yatak dolu mu? (kendisi hariç)
        if ($request->room_id && $request->bed_number) {
            $bedTaken = \App\Models\Student::where('room_id', $request->room_id)
                ->where('bed_number', $request->bed_number)
                ->where('id', '!=', $id)
                ->exists();
            if ($bedTaken) {
                return back()->withErrors(['bed_number' => 'Bu yatak zaten dolu.'])->withInput();
            }
        }

        // Oda kapasitesi doldu mu? (kendisi hariç)
        if ($request->room_id) {
            $room = \App\Models\Room::findOrFail($request->room_id);
            $occupants = \App\Models\Student::where('room_id', $request->room_id)
                ->where('id', '!=', $id)
                ->count();
            if ($occupants >= $room->capacity) {
                return back()->withErrors(['room_id' => 'Bu oda dolu, kapasite aşılamaz.'])->withInput();
            }
        }

        $student->update($request->except(['name', 'email', 'password', '_token', '_method']));
        $student->user->update(['name' => $request->name, 'email' => $request->email]);

        return redirect('/admin/students')->with('success', 'Öğrenci güncellendi.');
    }

    // Öğrenci sil
    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $student->user->delete();
        return redirect('/admin/students')->with('success', 'Öğrenci silindi.');
    }

    // Öğrenci kendi paneli
    public function dashboard()
    {
        $user    = auth()->user();
        $student = $user->student;

        $unpaidCount = $student->payments()->where('status', 'unpaid')->count();
        $leaveCount  = $student->leaveRequests()->count();
        $payments    = $student->payments()->latest()->take(5)->get();
        $leaves      = $student->leaveRequests()->latest()->take(5)->get();
        $todayMenu   = \App\Models\MealMenu::where('date', today())->first();

        return view('student.dashboard', compact(
            'student', 'unpaidCount', 'leaveCount', 'payments', 'leaves', 'todayMenu'
        ));
    }

    // Öğrencinin izin talepleri
    public function leaves()
    {
        $student = auth()->user()->student;
        $leaves  = $student->leaveRequests()->latest()->get();
        return view('student.leaves', compact('leaves'));
    }

    // Yeni izin talebi
    public function leaveStore(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'address'     => 'required|string|max:500',
            'reason'      => 'required|string|max:500',
        ]);

        $student = auth()->user()->student;

        $student->leaveRequests()->create([
            'start_date'  => $request->start_date,
            'end_date'    => $request->end_date,
            'address'     => $request->address,
            'phone'       => $request->phone,
            'description' => $request->reason,
            'status'      => 'pending',
        ]);

        return redirect('/student/leaves')->with('success', 'İzin talebiniz iletildi.');
    }

    // İzin talebi iptal et
    public function leaveDestroy($id)
    {
        $student = auth()->user()->student;
        $leave   = $student->leaveRequests()->findOrFail($id);

        if ($leave->status === 'pending') {
            $leave->delete();
        }

        return redirect('/student/leaves')->with('success', 'İzin talebi iptal edildi.');
    }

    // Öğrencinin ödemeleri
    public function payments()
    {
        $student  = auth()->user()->student;
        $payments = $student->payments()->latest()->get();
        return view('student.payments', compact('payments'));
    }

    // Yemekhane
    public function meals()
    {
        $menus = \App\Models\MealMenu::orderBy('date', 'desc')->get();
        return view('student.meals', compact('menus'));
    }

    // Duyurular
    public function announcements()
    {
        $announcements = \App\Models\Announcement::where('target', 'all')
            ->orWhere('target', 'student')
            ->latest('published_at')
            ->get();
        return view('student.announcements', compact('announcements'));
    }
}
