<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\MealMenuController;
use App\Http\Controllers\EntryLogController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Öğrenci yönetimi
Route::get('/admin/students', [StudentController::class, 'index'])->middleware('role:admin')->name('admin.students');
Route::get('/admin/students/create', [StudentController::class, 'create'])->middleware('role:admin')->name('admin.students.create');
Route::post('/admin/students', [StudentController::class, 'store'])->middleware('role:admin')->name('admin.students.store');
Route::get('/admin/students/{id}/edit', [StudentController::class, 'edit'])->middleware('role:admin')->name('admin.students.edit');
Route::put('/admin/students/{id}', [StudentController::class, 'update'])->middleware('role:admin')->name('admin.students.update');
Route::delete('/admin/students/{id}', [StudentController::class, 'destroy'])->middleware('role:admin')->name('admin.students.destroy');

// Oda yönetimi
Route::get('/admin/rooms', [RoomController::class, 'index'])->middleware('role:admin')->name('admin.rooms');
Route::get('/admin/rooms/create', [RoomController::class, 'create'])->middleware('role:admin')->name('admin.rooms.create');
Route::post('/admin/rooms', [RoomController::class, 'store'])->middleware('role:admin')->name('admin.rooms.store');
Route::get('/admin/rooms/{id}/edit', [RoomController::class, 'edit'])->middleware('role:admin')->name('admin.rooms.edit');
Route::put('/admin/rooms/{id}', [RoomController::class, 'update'])->middleware('role:admin')->name('admin.rooms.update');
Route::delete('/admin/rooms/{id}', [RoomController::class, 'destroy'])->middleware('role:admin')->name('admin.rooms.destroy');
Route::get('/', function () {
    return view('welcome');
});

// Admin
Route::get('/admin/dashboard', function () {
    $studentCount  = \App\Models\Student::count();
    $roomCount     = \App\Models\Room::count();
    $pendingLeaves = \App\Models\LeaveRequest::where('status', 'pending')->count();
    $latePayments  = \App\Models\Payment::where('status', 'late')->count();
    $recentLeaves  = \App\Models\LeaveRequest::with('student.user')->latest()->take(10)->get();
    return view('admin.dashboard', compact('studentCount', 'roomCount', 'pendingLeaves', 'latePayments', 'recentLeaves'));
})->middleware('role:admin')->name('admin.dashboard');

// İzin yönetimi
Route::get('/admin/leaves', [LeaveRequestController::class, 'index'])->middleware('role:admin')->name('admin.leaves');
Route::post('/admin/leaves/{id}/approve', [LeaveRequestController::class, 'approve'])->middleware('role:admin')->name('admin.leaves.approve');
Route::post('/admin/leaves/{id}/reject', [LeaveRequestController::class, 'reject'])->middleware('role:admin')->name('admin.leaves.reject');
Route::delete('/admin/leaves/{id}', [LeaveRequestController::class, 'destroy'])->middleware('role:admin')->name('admin.leaves.destroy');
// Personel
Route::get('/admin/staff/{id}/edit', [App\Http\Controllers\StaffController::class, 'edit'])->middleware('role:admin')->name('admin.staff.edit');
Route::put('/admin/staff/{id}', [App\Http\Controllers\StaffController::class, 'update'])->middleware('role:admin')->name('admin.staff.update');


// Ödeme yönetimi
Route::get('/admin/payments', [PaymentController::class, 'index'])->middleware('role:admin')->name('admin.payments');
Route::get('/admin/payments/create', [PaymentController::class, 'create'])->middleware('role:admin')->name('admin.payments.create');
Route::post('/admin/payments', [PaymentController::class, 'store'])->middleware('role:admin')->name('admin.payments.store');
Route::post('/admin/payments/{id}/paid', [PaymentController::class, 'markPaid'])->middleware('role:admin')->name('admin.payments.paid');
Route::delete('/admin/payments/{id}', [PaymentController::class, 'destroy'])->middleware('role:admin')->name('admin.payments.destroy');

// Duyuru yönetimi
Route::get('/admin/announcements', [AnnouncementController::class, 'index'])->middleware('role:admin')->name('admin.announcements');
Route::get('/admin/announcements/create', [AnnouncementController::class, 'create'])->middleware('role:admin')->name('admin.announcements.create');
Route::post('/admin/announcements', [AnnouncementController::class, 'store'])->middleware('role:admin')->name('admin.announcements.store');
Route::delete('/admin/announcements/{id}', [AnnouncementController::class, 'destroy'])->middleware('role:admin')->name('admin.announcements.destroy');
// Yemekhane
Route::get('/admin/meals', [MealMenuController::class, 'index'])->middleware('role:admin')->name('admin.meals');
Route::get('/admin/meals/monthly', [MealMenuController::class, 'monthly'])->middleware('role:admin')->name('admin.meals.monthly');
Route::post('/admin/meals/monthly', [MealMenuController::class, 'monthlyStore'])->middleware('role:admin')->name('admin.meals.monthly.store');
Route::get('/admin/meals/create', [MealMenuController::class, 'create'])->middleware('role:admin')->name('admin.meals.create');
Route::post('/admin/meals', [MealMenuController::class, 'store'])->middleware('role:admin')->name('admin.meals.store');
Route::delete('/admin/meals/{id}', [MealMenuController::class, 'destroy'])->middleware('role:admin')->name('admin.meals.destroy');
// Öğrenci
Route::get('/student/dashboard', [StudentController::class, 'dashboard'])->middleware('role:student')->name('student.dashboard');
Route::get('/student/leaves', [StudentController::class, 'leaves'])->middleware('role:student')->name('student.leaves');
Route::post('/student/leaves', [StudentController::class, 'leaveStore'])->middleware('role:student')->name('student.leaves.store');
Route::delete('/student/leaves/{id}', [StudentController::class, 'leaveDestroy'])->middleware('role:student')->name('student.leaves.destroy');
// Öğrencinin ödemeleri
Route::get('/student/payments', [StudentController::class, 'payments'])->middleware('role:student')->name('student.payments');
// Öğrenci yemekhane kısmı
Route::get('/student/meals', [StudentController::class, 'meals'])->middleware('role:student')->name('student.meals');
// Öğrenci Duyuru
Route::get('/student/announcements', [StudentController::class, 'announcements'])->middleware('role:student')->name('student.announcements');
// Veli
Route::get('/parent/dashboard', function () {
    return view('parent.dashboard');
})->middleware('role:parent')->name('parent.dashboard');

// Giriş-Çıkış
Route::get('/admin/entry-logs', [EntryLogController::class, 'index'])->middleware('role:admin')->name('admin.entry-logs');
Route::post('/admin/entry-logs', [EntryLogController::class, 'store'])->middleware('role:admin')->name('admin.entry-logs.store');
Route::patch('/admin/entry-logs/{id}/entry', [EntryLogController::class, 'markEntry'])->middleware('role:admin')->name('admin.entry-logs.entry');

//Admin-ayarlar
Route::get('/admin/settings', [App\Http\Controllers\SettingsController::class, 'index'])->middleware('role:admin')->name('admin.settings');
Route::post('/admin/settings', [App\Http\Controllers\SettingsController::class, 'update'])->middleware('role:admin')->name('admin.settings.update');
Route::post('/admin/settings/password', [App\Http\Controllers\SettingsController::class, 'changePassword'])->middleware('role:admin')->name('admin.settings.password');
Route::post('/admin/settings/profile', [App\Http\Controllers\SettingsController::class, 'updateProfile'])->middleware('role:admin')->name('admin.settings.profile');

// Arıza & Şikayet - Öğrenci
Route::get('/student/tickets', [App\Http\Controllers\TicketController::class, 'index'])->middleware('role:student')->name('student.tickets');
Route::post('/student/tickets', [App\Http\Controllers\TicketController::class, 'store'])->middleware('role:student')->name('student.tickets.store');

// Arıza & Şikayet - Admin
Route::get('/admin/tickets', [App\Http\Controllers\TicketController::class, 'adminIndex'])->middleware('role:admin')->name('admin.tickets');
Route::patch('/admin/tickets/{id}', [App\Http\Controllers\TicketController::class, 'update'])->middleware('role:admin')->name('admin.tickets.update');

// Personel - Admin yönetimi
Route::get('/admin/staff', [App\Http\Controllers\StaffController::class, 'index'])->middleware('role:admin')->name('admin.staff');
Route::get('/admin/staff/create', [App\Http\Controllers\StaffController::class, 'create'])->middleware('role:admin')->name('admin.staff.create');
Route::post('/admin/staff', [App\Http\Controllers\StaffController::class, 'store'])->middleware('role:admin')->name('admin.staff.store');
Route::delete('/admin/staff/{id}', [App\Http\Controllers\StaffController::class, 'destroy'])->middleware('role:admin')->name('admin.staff.destroy');

// Nöbet takvimi
Route::get('/admin/duty', [App\Http\Controllers\StaffController::class, 'dutyIndex'])->middleware('role:admin')->name('admin.duty');
Route::post('/admin/duty', [App\Http\Controllers\StaffController::class, 'dutyStore'])->middleware('role:admin')->name('admin.duty.store');
Route::delete('/admin/duty/{id}', [App\Http\Controllers\StaffController::class, 'dutyDestroy'])->middleware('role:admin')->name('admin.duty.destroy');

// Güvenlik personeli paneli
Route::get('/staff/dashboard', [App\Http\Controllers\StaffController::class, 'dashboard'])->middleware('role:staff')->name('staff.dashboard');
Route::post('/staff/entry-logs', [App\Http\Controllers\StaffController::class, 'entryLogStore'])->middleware('role:staff')->name('staff.entry-logs.store');
Route::patch('/staff/entry-logs/{id}', [App\Http\Controllers\StaffController::class, 'entryLogUpdate'])->middleware('role:staff')->name('staff.entry-logs.update');

Route::get('/admin/duty/{id}/edit', [App\Http\Controllers\StaffController::class, 'dutyEdit'])->middleware('role:admin')->name('admin.duty.edit');
Route::put('/admin/duty/{id}', [App\Http\Controllers\StaffController::class, 'dutyUpdate'])->middleware('role:admin')->name('admin.duty.update');

// Temizlik görevleri
Route::get('/admin/cleaning', [App\Http\Controllers\StaffController::class, 'cleaningIndex'])->middleware('role:admin')->name('admin.cleaning');
Route::post('/admin/cleaning', [App\Http\Controllers\StaffController::class, 'cleaningStore'])->middleware('role:admin')->name('admin.cleaning.store');
Route::delete('/admin/cleaning/{id}', [App\Http\Controllers\StaffController::class, 'cleaningDestroy'])->middleware('role:admin')->name('admin.cleaning.destroy');

// Temizlik personeli - görevi tamamlandı işaretle
Route::patch('/staff/cleaning/{id}', [App\Http\Controllers\StaffController::class, 'cleaningDone'])->middleware('role:staff')->name('staff.cleaning.done');

// Yemekhane personeli - yemek servis edildi
Route::patch('/staff/meals/{id}/serve', [App\Http\Controllers\StaffController::class, 'mealServe'])->middleware('role:staff')->name('staff.meals.serve');

Route::patch('/staff/duty/{id}/done', [App\Http\Controllers\StaffController::class, 'dutyDone'])->middleware('role:staff')->name('staff.duty.done');
