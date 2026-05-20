<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LapanganController extends Controller
{
    public function index(Request $request)
{
    $lapangan = Lapangan::withCount('reservasi as reservasi_count')
        ->when($request->filled('search'), function ($q) use ($request) {
            $q->where(function ($sub) use ($request) {
                $sub->where('nama', 'like', '%' . $request->search . '%')
                    ->orWhere('jenis', 'like', '%' . $request->search . '%');
            });
        })
        ->when($request->filled('jenis'), fn($q) => $q->where('jenis', $request->jenis))
        ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
        ->latest()
        ->paginate(10)
        ->withQueryString();

    return view('admin.lapangan.index', compact('lapangan'));
}
    public function create()
    {
        $jenisOptions = ['Futsal', 'Badminton', 'Basket', 'Voli', 'Tenis', 'Lainnya'];
        return view('admin.lapangan.create', compact('jenisOptions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'          => 'required|string|max:100',
            'jenis'         => 'required|in:Futsal,Badminton,Basket,Voli,Tenis,Lainnya',
            'deskripsi'     => 'nullable|string',
            'harga_per_jam' => 'required|numeric|min:1000',
            'foto'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status'        => 'required|in:aktif,nonaktif',
            'jam_buka'      => 'required',
            'jam_tutup'     => 'required|after:jam_buka',
        ], [
            'nama.required'          => 'Nama lapangan wajib diisi.',
            'harga_per_jam.required' => 'Harga per jam wajib diisi.',
            'foto.image'             => 'File harus berupa gambar.',
            'foto.max'               => 'Ukuran foto maksimal 2MB.',
            'jam_tutup.after'        => 'Jam tutup harus setelah jam buka.',
        ]);

        $data = $request->except('foto');

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = Str::uuid() . '.' . $file->extension();
            $file->move(public_path('uploads/lapangan'), $filename);
            $data['foto'] = $filename;
        }

        Lapangan::create($data);

        return redirect()->route('admin.lapangan.index')
            ->with('success', 'Lapangan berhasil ditambahkan!');
    }

    public function show(Lapangan $lapangan)
    {
        $lapangan->load('reservasi');
        $reservasiTerbaru = $lapangan->reservasi()->with('lapangan')->latest()->take(10)->get();
        return view('admin.lapangan.show', compact('lapangan', 'reservasiTerbaru'));
    }

    public function edit(Lapangan $lapangan)
    {
        $jenisOptions = ['Futsal', 'Badminton', 'Basket', 'Voli', 'Tenis', 'Lainnya'];
        return view('admin.lapangan.edit', compact('lapangan', 'jenisOptions'));
    }

    public function update(Request $request, Lapangan $lapangan)
    {
        $request->validate([
            'nama'          => 'required|string|max:100',
            'jenis'         => 'required|in:Futsal,Badminton,Basket,Voli,Tenis,Lainnya',
            'deskripsi'     => 'nullable|string',
            'harga_per_jam' => 'required|numeric|min:1000',
            'foto'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status'        => 'required|in:aktif,nonaktif',
            'jam_buka'      => 'required',
            'jam_tutup'     => 'required|after:jam_buka',
        ]);

        $data = $request->except('foto');

        if ($request->hasFile('foto')) {
            // Hapus foto lama
            if ($lapangan->foto && file_exists(public_path('uploads/lapangan/' . $lapangan->foto))) {
                unlink(public_path('uploads/lapangan/' . $lapangan->foto));
            }
            $file = $request->file('foto');
            $filename = Str::uuid() . '.' . $file->extension();
            $file->move(public_path('uploads/lapangan'), $filename);
            $data['foto'] = $filename;
        }

        $lapangan->update($data);

        return redirect()->route('admin.lapangan.index')
            ->with('success', 'Lapangan berhasil diperbarui!');
    }

    public function destroy(Lapangan $lapangan)
    {
        if ($lapangan->foto && file_exists(public_path('uploads/lapangan/' . $lapangan->foto))) {
            unlink(public_path('uploads/lapangan/' . $lapangan->foto));
        }

        $lapangan->delete();

        return redirect()->route('admin.lapangan.index')
            ->with('success', 'Lapangan berhasil dihapus!');
    }

    public function toggleStatus(Lapangan $lapangan)
    {
        $lapangan->update([
            'status' => $lapangan->status === 'aktif' ? 'nonaktif' : 'aktif'
        ]);

        return back()->with('success', 'Status lapangan berhasil diubah!');
    }
}
