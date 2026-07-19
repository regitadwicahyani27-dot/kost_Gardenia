<?php

namespace App\Http\Controllers;

use App\Models\Room;

class RoomController extends Controller
{
    // Halaman publik (tanpa login)
    public function index()
    {
        $query = Room::where('is_available', true)->with(['primaryPhoto', 'facilities']);

        if (request('lantai')) {
            $query->where('floor', request('lantai'));
        }

        $rooms = $query->orderBy('name')->get();
        return view('rooms.index', compact('rooms'));
    }

    public function show(Room $room)
    {
        $room->load(['photos', 'facilities']);
        return view('rooms.show', compact('room'));
    }

    // Halaman user (setelah login)
    public function indexUser()
    {
        $query = Room::with(['primaryPhoto', 'facilities']);

        if (request('lantai')) {
            $query->where('floor', request('lantai'));
        }

        $rooms = $query->orderBy('name')->get();
        return view('user.rooms.index', compact('rooms'));
    }

    public function showUser(Room $room)
    {
        $room->load(['photos', 'facilities']);
        return view('user.rooms.show', compact('room'));
    }
}