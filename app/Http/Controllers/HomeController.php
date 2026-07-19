<?php
namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Testimonial;

class HomeController extends Controller
{
    public function index()
    {
        $rooms = Room::where('is_available', true)->noActiveBooking()->with('primaryPhoto')->take(3)->get();
        $testimonials = Testimonial::where('status', 'approved')->with('user')->latest()->take(3)->get();

        return view('home', compact('rooms', 'testimonials'));
    }

    public function tentang()
    {
        return view('tentang');
    }
}