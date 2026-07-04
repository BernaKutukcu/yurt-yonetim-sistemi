<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::with('user')->latest()->get();
        return view('admin.announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('admin.announcements.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'        => 'required',
            'content'      => 'required',
            'target'       => 'required',
            'published_at' => 'required|date',
        ]);

        Announcement::create([
            'user_id'      => auth()->id(),
            'title'        => $request->title,
            'content'      => $request->content,
            'target'       => $request->target,
            'published_at' => $request->published_at,
        ]);

        return redirect('/admin/announcements')->with('success', 'Duyuru yayınlandı.');
    }

    public function destroy($id)
    {
        Announcement::findOrFail($id)->delete();
        return redirect('/admin/announcements')->with('success', 'Duyuru silindi.');
    }
}
