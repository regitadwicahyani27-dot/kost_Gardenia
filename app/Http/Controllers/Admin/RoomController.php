<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\Room;
use App\Models\RoomPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::with(['facilities', 'primaryPhoto'])->latest()->paginate(20);
        return view('admin.rooms.index', compact('rooms'));
    }

    public function create()
    {
        $facilities = Facility::all();
        return view('admin.rooms.create', compact('facilities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:standard,deluxe,vip',
            'floor' => 'required|in:1,2',
            'price' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'photos.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'facilities' => 'nullable|array',
            'facilities.*' => 'exists:facilities,id',
        ]);

        $kamar = Room::create([
            'name' => $request->name,
            'type' => $request->type,
            'floor' => $request->floor,
            'price' => $request->price,
            'description' => $request->description,
            'is_available' => true,
        ]);

        if ($request->has('facilities')) {
            $kamar->facilities()->attach($request->facilities);
        }

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $index => $photo) {
                $path = $photo->store('rooms', 'public');
                RoomPhoto::create([
                    'room_id' => $kamar->id,
                    'photo_path' => $path,
                    'is_primary' => $index === 0,
                ]);
            }
        }

        return redirect()->route('admin.kamar.index')->with('success', 'Kamar berhasil ditambahkan!');
    }

    public function edit(Room $kamar)
    {
        $facilities = Facility::all();
        return view('admin.rooms.edit', compact('kamar', 'facilities'));
    }

    public function update(Request $request, Room $kamar)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:standard,deluxe,vip',
            'floor' => 'required|in:1,2',
            'price' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'facilities' => 'nullable|array',
            'facilities.*' => 'exists:facilities,id',
        ]);

        $kamar->update([
            'name' => $request->name,
            'type' => $request->type,
            'floor' => $request->floor,
            'price' => $request->price,
            'description' => $request->description,
            'is_available' => $request->has('is_available'),
        ]);

        $kamar->facilities()->sync($request->facilities ?? []);

        return redirect()->route('admin.kamar.index')->with('success', 'Kamar berhasil diperbarui!');
    }

    public function destroy(Room $kamar)
    {
        foreach ($kamar->photos as $photo) {
            Storage::disk('public')->delete($photo->photo_path);
            $photo->delete();
        }

        $kamar->facilities()->detach();
        $kamar->delete();

        return redirect()->route('admin.kamar.index')->with('success', 'Kamar berhasil dihapus!');
    }

    public function uploadPhoto(Request $request, Room $kamar)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $path = $request->file('photo')->store('rooms', 'public');
        $isPrimary = $kamar->photos()->count() === 0;

        RoomPhoto::create([
            'room_id' => $kamar->id,
            'photo_path' => $path,
            'is_primary' => $isPrimary,
        ]);

        return back()->with('success', 'Foto berhasil diupload!');
    }

    public function deletePhoto(RoomPhoto $photo)
    {
        Storage::disk('public')->delete($photo->photo_path);

        $roomId = $photo->room_id;
        $wasPrimary = $photo->is_primary;
        $photo->delete();

        if ($wasPrimary) {
            $firstPhoto = RoomPhoto::where('room_id', $roomId)->first();
            if ($firstPhoto) {
                $firstPhoto->update(['is_primary' => true]);
            }
        }

        return back()->with('success', 'Foto berhasil dihapus!');
    }

    public function toggleAvailability(Room $kamar)
    {
        $kamar->update(['is_available' => !$kamar->is_available]);
        $status = $kamar->is_available ? 'Tersedia' : 'Terisi';
        return back()->with('success', "Status kamar {$kamar->name} berhasil diubah menjadi {$status}!");
    }
}