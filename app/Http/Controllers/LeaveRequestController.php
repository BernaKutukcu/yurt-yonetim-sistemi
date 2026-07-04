<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveRequest;

class LeaveRequestController extends Controller
{
    // İzin taleplerini listele
    public function index(Request $request)
    {
        $query = LeaveRequest::with('student.user')->latest();

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $leaves = $query->get();
        return view('admin.leaves.index', compact('leaves'));
    }

    // İzni onayla
    public function approve($id)
    {
        $leave = LeaveRequest::findOrFail($id);
        $leave->update([
            'status'      => 'approved',
            'approved_by' => auth()->id(),
        ]);
        return redirect('/admin/leaves')->with('success', 'İzin onaylandı.');
    }

    // İzni reddet
    public function reject($id)
    {
        $leave = LeaveRequest::findOrFail($id);
        $leave->update([
            'status'      => 'rejected',
            'approved_by' => auth()->id(),
        ]);
        return redirect('/admin/leaves')->with('success', 'İzin reddedildi.');
    }

    // İzni sil
    public function destroy($id)
    {
        LeaveRequest::findOrFail($id)->delete();
        return redirect('/admin/leaves')->with('success', 'İzin talebi silindi.');
    }
}
