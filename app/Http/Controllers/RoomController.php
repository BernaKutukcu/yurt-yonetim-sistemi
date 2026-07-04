<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;

class RoomController extends Controller
{
    // Odaları listele
    public function index()
    {
        $rooms = Room::withCount('students')->latest()->get();
        return view('admin.rooms.index', compact('rooms'));
    }

    // Oda ekleme formu
    public function create()
    {
        return view('admin.rooms.create');
    }

    // Oda kaydet
    public function store(Request $request)
    {
        $request->validate([
            'room_number' => 'required|unique:rooms,room_number,NULL,id,block,' . $request->block,
            'floor'       => 'required|integer',
            'block'       => 'required',
            'capacity'    => 'required|integer|min:1',
        ]);

        Room::create($request->all());

        return redirect('/admin/rooms')->with('success', 'Oda başarıyla eklendi.');
    }

    // Oda düzenleme formu
    public function edit($id)
    {
        $room = Room::findOrFail($id);
        return view('admin.rooms.edit', compact('room'));
    }

    // Oda güncelle
    public function update(Request $request, $id)
    {
        $room = Room::findOrFail($id);
        $room->update($request->all());
        return redirect('/admin/rooms')->with('success', 'Oda güncellendi.');
    }

    // Oda sil
    public function destroy($id)
    {
        Room::findOrFail($id)->delete();
        return redirect('/admin/rooms')->with('success', 'Oda silindi.');
    }
}
