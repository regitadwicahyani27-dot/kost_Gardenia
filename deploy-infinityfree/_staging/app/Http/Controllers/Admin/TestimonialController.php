<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::latest()->paginate(15);
        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function create()
    {
        return view('admin.testimonials.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'label' => ['nullable', 'string', 'max:255'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'content' => ['required', 'string', 'max:1000'],
        ]);

        Testimonial::create([
            'name' => $request->name,
            'label' => $request->label ?: 'Penghuni Aktif',
            'rating' => $request->rating,
            'content' => $request->content,
            'status' => 'approved',
        ]);

        return redirect()->route('admin.testimonial.index')->with('success', 'Testimoni berhasil ditambahkan!');
    }

    public function edit(Testimonial $testimonial)
    {
        return view('admin.testimonials.edit', compact('testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'label' => ['nullable', 'string', 'max:255'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'content' => ['required', 'string', 'max:1000'],
            'status' => ['required', 'in:approved,rejected'],
        ]);

        $testimonial->update([
            'name' => $request->name,
            'label' => $request->label ?: 'Penghuni Aktif',
            'rating' => $request->rating,
            'content' => $request->content,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.testimonial.index')->with('success', 'Testimoni berhasil diperbarui!');
    }

    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();
        return back()->with('success', 'Testimoni dihapus!');
    }
}
