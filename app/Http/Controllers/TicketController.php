<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;

class TicketController extends Controller
{
    // Öğrencinin kendi talepleri
    public function index()
    {
        $student = auth()->user()->student;
        $tickets = $student->tickets()->latest()->get();
        return view('student.tickets', compact('tickets'));
    }

    // Yeni talep oluştur
    public function store(Request $request)
    {
        $request->validate([
            'type'        => 'required|in:ariza,sikayet',
            'title'       => 'required|string|max:255',
            'description' => 'required|string|max:1000',
        ]);

        $student = auth()->user()->student;

        $student->tickets()->create([
            'type'        => $request->type,
            'title'       => $request->title,
            'description' => $request->description,
            'status'      => 'bekliyor',
        ]);

        return redirect('/student/tickets')->with('success', 'Talebiniz iletildi.');
    }

    // Admin - tüm talepler
    public function adminIndex(Request $request)
    {
        $query = Ticket::with('student.user')->latest();

        if ($request->type) {
            $query->where('type', $request->type);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $tickets = $query->get();
        return view('admin.tickets.index', compact('tickets'));
    }

    // Admin - durum güncelle
    public function update(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->update([
            'status'     => $request->status,
            'admin_note' => $request->admin_note,
        ]);

        return redirect('/admin/tickets')->with('success', 'Talep güncellendi.');
    }
}
