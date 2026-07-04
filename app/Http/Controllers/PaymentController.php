<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Student;

class PaymentController extends Controller
{
    // Ödemeleri listele
    public function index(Request $request)
    {
        $query = Payment::with('student.user')->latest();

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $payments = $query->get();
        return view('admin.payments.index', compact('payments'));
    }

    // Ödeme ekleme formu
    public function create()
    {
        $students = Student::with('user')->get();
        return view('admin.payments.create', compact('students'));
    }

    // Ödeme kaydet
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required',
            'amount'     => 'required|numeric',
            'due_date'   => 'required|date',
        ]);

        Payment::create([
            'student_id'   => $request->student_id,
            'amount'       => $request->amount,
            'due_date'     => $request->due_date,
            'payment_date' => $request->payment_date ?? null,
            'status'       => $request->status ?? 'unpaid',
        ]);

        return redirect('/admin/payments')->with('success', 'Ödeme kaydı eklendi.');
    }

    // Ödeme durumu güncelle
    public function markPaid($id)
    {
        Payment::findOrFail($id)->update([
            'status'       => 'paid',
            'payment_date' => now(),
        ]);
        return redirect('/admin/payments')->with('success', 'Ödeme alındı olarak işaretlendi.');
    }

    // Ödeme sil
    public function destroy($id)
    {
        Payment::findOrFail($id)->delete();
        return redirect('/admin/payments')->with('success', 'Ödeme kaydı silindi.');
    }
}
