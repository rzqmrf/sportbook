<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use App\Models\Reservasi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ApiController extends Controller
{
    // ─── HELPER ──────────────────────────────────────────────────────────────

    private function success($data = null, string $message = 'OK', int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ], $code);
    }

    private function error(string $message = 'Error', int $code = 400, $errors = null): JsonResponse
    {
        $res = ['success' => false, 'message' => $message];
        if ($errors) $res['errors'] = $errors;
        return response()->json($res, $code);
    }

    // ─── AUTH ─────────────────────────────────────────────────────────────────

    /**
     * POST /api/login
     * Body: { "email": "...", "password": "..." }
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return $this->error('Validasi gagal', 422, $validator->errors());
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return $this->error('Email atau password salah', 401);
        }

        $user  = Auth::user();
        $token = $user->createToken('api-token')->plainTextToken;

        return $this->success([
            'user'  => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role,
            ],
            'token' => $token,
            'token_type' => 'Bearer',
        ], 'Login berhasil');
    }

    /**
     * POST /api/logout
     * Header: Authorization: Bearer {token}
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return $this->success(null, 'Logout berhasil');
    }

    /**
     * GET /api/me
     * Header: Authorization: Bearer {token}
     */
    public function me(Request $request): JsonResponse
    {
        return $this->success([
            'id'         => $request->user()->id,
            'name'       => $request->user()->name,
            'email'      => $request->user()->email,
            'role'       => $request->user()->role,
            'created_at' => $request->user()->created_at,
        ], 'Data user');
    }

    // ─── LAPANGAN ────────────────────────────────────────────────────────────

    /**
     * GET /api/lapangan
     * Query: ?jenis=Futsal&status=aktif&search=nama
     */
    public function lapanganIndex(Request $request): JsonResponse
    {
        $query = Lapangan::withCount('reservasi');

        if ($request->filled('search'))  $query->where('nama', 'like', '%'.$request->search.'%');
        if ($request->filled('jenis'))   $query->where('jenis', $request->jenis);
        if ($request->filled('status'))  $query->where('status', $request->status);

        $lapangan = $query->latest()->paginate($request->get('per_page', 10));

        return response()->json([
            'success' => true,
            'message' => 'Daftar lapangan',
            'data'    => $lapangan->map(fn($l) => $this->formatLapangan($l)),
            'meta'    => [
                'current_page' => $lapangan->currentPage(),
                'last_page'    => $lapangan->lastPage(),
                'per_page'     => $lapangan->perPage(),
                'total'        => $lapangan->total(),
            ],
        ]);
    }

    /**
     * GET /api/lapangan/{id}
     */
    public function lapanganShow(Lapangan $lapangan): JsonResponse
    {
        $lapangan->loadCount('reservasi');
        return $this->success($this->formatLapangan($lapangan), 'Detail lapangan');
    }

    /**
     * POST /api/lapangan
     * Header: Authorization: Bearer {token}
     * Body: multipart/form-data
     */
    public function lapanganStore(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nama'          => 'required|string|max:100',
            'jenis'         => 'required|in:Futsal,Badminton,Basket,Voli,Tenis,Lainnya',
            'deskripsi'     => 'nullable|string',
            'harga_per_jam' => 'required|numeric|min:1000',
            'foto'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status'        => 'required|in:aktif,nonaktif',
            'jam_buka'      => 'required',
            'jam_tutup'     => 'required|after:jam_buka',
        ]);

        if ($validator->fails()) {
            return $this->error('Validasi gagal', 422, $validator->errors());
        }

        $data = $request->except('foto');

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = Str::uuid().'.'.$file->extension();
            $file->move(public_path('uploads/lapangan'), $filename);
            $data['foto'] = $filename;
        }

        $lapangan = Lapangan::create($data);
        $lapangan->loadCount('reservasi');

        return $this->success($this->formatLapangan($lapangan), 'Lapangan berhasil ditambahkan', 201);
    }

    /**
     * PUT /api/lapangan/{id}
     * Header: Authorization: Bearer {token}
     */
    public function lapanganUpdate(Request $request, Lapangan $lapangan): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nama'          => 'sometimes|required|string|max:100',
            'jenis'         => 'sometimes|required|in:Futsal,Badminton,Basket,Voli,Tenis,Lainnya',
            'deskripsi'     => 'nullable|string',
            'harga_per_jam' => 'sometimes|required|numeric|min:1000',
            'foto'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status'        => 'sometimes|required|in:aktif,nonaktif',
            'jam_buka'      => 'sometimes|required',
            'jam_tutup'     => 'sometimes|required',
        ]);

        if ($validator->fails()) {
            return $this->error('Validasi gagal', 422, $validator->errors());
        }

        $data = $request->except('foto');

        if ($request->hasFile('foto')) {
            if ($lapangan->foto && file_exists(public_path('uploads/lapangan/'.$lapangan->foto))) {
                unlink(public_path('uploads/lapangan/'.$lapangan->foto));
            }
            $file = $request->file('foto');
            $filename = Str::uuid().'.'.$file->extension();
            $file->move(public_path('uploads/lapangan'), $filename);
            $data['foto'] = $filename;
        }

        $lapangan->update($data);
        $lapangan->loadCount('reservasi');

        return $this->success($this->formatLapangan($lapangan), 'Lapangan berhasil diperbarui');
    }

    /**
     * DELETE /api/lapangan/{id}
     * Header: Authorization: Bearer {token}
     */
    public function lapanganDestroy(Lapangan $lapangan): JsonResponse
    {
        if ($lapangan->foto && file_exists(public_path('uploads/lapangan/'.$lapangan->foto))) {
            unlink(public_path('uploads/lapangan/'.$lapangan->foto));
        }
        $lapangan->delete();
        return $this->success(null, 'Lapangan berhasil dihapus');
    }

    // ─── RESERVASI ───────────────────────────────────────────────────────────

    /**
     * GET /api/reservasi
     * Header: Authorization: Bearer {token}
     * Query: ?status=pending&lapangan_id=1&tanggal=2025-01-01
     */
    public function reservasiIndex(Request $request): JsonResponse
    {
        $query = Reservasi::with('lapangan:id,nama,jenis,harga_per_jam');

        if ($request->filled('status'))      $query->where('status', $request->status);
        if ($request->filled('lapangan_id')) $query->where('lapangan_id', $request->lapangan_id);
        if ($request->filled('tanggal'))     $query->whereDate('tanggal', $request->tanggal);
        if ($request->filled('search')) {
            $query->where(fn($q) =>
                $q->where('nama_pemesan', 'like', '%'.$request->search.'%')
                  ->orWhere('no_hp', 'like', '%'.$request->search.'%')
            );
        }

        $reservasi = $query->latest()->paginate($request->get('per_page', 10));

        return response()->json([
            'success' => true,
            'message' => 'Daftar reservasi',
            'data'    => $reservasi->map(fn($r) => $this->formatReservasi($r)),
            'meta'    => [
                'current_page' => $reservasi->currentPage(),
                'last_page'    => $reservasi->lastPage(),
                'per_page'     => $reservasi->perPage(),
                'total'        => $reservasi->total(),
            ],
            'stats' => [
                'pending'  => Reservasi::where('status', 'pending')->count(),
                'approved' => Reservasi::where('status', 'approved')->count(),
                'rejected' => Reservasi::where('status', 'rejected')->count(),
            ],
        ]);
    }

    /**
     * GET /api/reservasi/{id}
     * Header: Authorization: Bearer {token}
     */
    public function reservasiShow(Reservasi $reservasi): JsonResponse
    {
        $reservasi->load('lapangan');
        return $this->success($this->formatReservasi($reservasi), 'Detail reservasi');
    }

    /**
     * POST /api/reservasi
     * Public — tidak perlu token
     * Body: JSON
     */
    public function reservasiStore(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'lapangan_id'  => 'required|exists:lapangan,id',
            'nama_pemesan' => 'required|string|max:100',
            'no_hp'        => 'required|string|max:20',
            'email'        => 'nullable|email',
            'tanggal'      => 'required|date|after_or_equal:today',
            'jam_mulai'    => 'required|date_format:H:i',
            'durasi_jam'   => 'required|integer|min:1|max:8',
            'catatan'      => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return $this->error('Validasi gagal', 422, $validator->errors());
        }

        $lapangan   = Lapangan::findOrFail($request->lapangan_id);
        $jamMulai   = $request->jam_mulai;
        $durasi     = (int) $request->durasi_jam;
        $jamSelesai = date('H:i', strtotime($jamMulai.' +'.$durasi.' hours'));
        $total      = $lapangan->harga_per_jam * $durasi;

        // Cek bentrok jadwal
        $bentrok = Reservasi::where('lapangan_id', $request->lapangan_id)
            ->where('tanggal', $request->tanggal)
            ->where('status', 'approved')
            ->where(fn($q) =>
                $q->whereBetween('jam_mulai', [$jamMulai, $jamSelesai])
                  ->orWhereBetween('jam_selesai', [$jamMulai, $jamSelesai])
            )->exists();

        if ($bentrok) {
            return $this->error('Jadwal bentrok dengan reservasi yang sudah ada', 409);
        }

        $reservasi = Reservasi::create([
            'lapangan_id'  => $request->lapangan_id,
            'nama_pemesan' => $request->nama_pemesan,
            'no_hp'        => $request->no_hp,
            'email'        => $request->email,
            'tanggal'      => $request->tanggal,
            'jam_mulai'    => $jamMulai,
            'jam_selesai'  => $jamSelesai,
            'durasi_jam'   => $durasi,
            'total_harga'  => $total,
            'catatan'      => $request->catatan,
            'status'       => 'pending',
        ]);

        $reservasi->load('lapangan');

        return $this->success($this->formatReservasi($reservasi), 'Reservasi berhasil dibuat, menunggu konfirmasi admin', 201);
    }

    /**
     * POST /api/reservasi/track
     * Public — lacak riwayat booking berdasarkan no_hp
     * Body: { "no_hp": "..." }
     */
    public function reservasiTrack(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'no_hp' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->error('Validasi gagal', 422, $validator->errors());
        }

        $reservasi = Reservasi::with('lapangan:id,nama,jenis,harga_per_jam')
            ->where('no_hp', $request->no_hp)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Riwayat reservasi untuk nomor ' . $request->no_hp,
            'data'    => $reservasi->map(fn($r) => $this->formatReservasi($r)),
        ]);
    }


    /**
     * POST /api/reservasi/{id}/approve
     * Header: Authorization: Bearer {token}
     */
    public function reservasiApprove(Reservasi $reservasi): JsonResponse
    {
        if ($reservasi->status !== 'pending') {
            return $this->error('Reservasi sudah diproses sebelumnya', 400);
        }

        $reservasi->update(['status' => 'approved', 'alasan_tolak' => null]);
        $reservasi->load('lapangan');

        return $this->success($this->formatReservasi($reservasi), 'Reservasi berhasil disetujui');
    }

    /**
     * POST /api/reservasi/{id}/reject
     * Header: Authorization: Bearer {token}
     * Body: { "alasan_tolak": "..." }
     */
    public function reservasiReject(Request $request, Reservasi $reservasi): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'alasan_tolak' => 'required|string|min:5',
        ]);

        if ($validator->fails()) {
            return $this->error('Validasi gagal', 422, $validator->errors());
        }

        $reservasi->update(['status' => 'rejected', 'alasan_tolak' => $request->alasan_tolak]);
        $reservasi->load('lapangan');

        return $this->success($this->formatReservasi($reservasi), 'Reservasi berhasil ditolak');
    }

    /**
     * DELETE /api/reservasi/{id}
     * Header: Authorization: Bearer {token}
     */
    public function reservasiDestroy(Reservasi $reservasi): JsonResponse
    {
        $reservasi->delete();
        return $this->success(null, 'Reservasi berhasil dihapus');
    }

    // ─── DASHBOARD STATS ─────────────────────────────────────────────────────

    /**
     * GET /api/dashboard
     * Header: Authorization: Bearer {token}
     */
    public function dashboard(): JsonResponse
    {
        $data = [
            'total_lapangan'       => Lapangan::count(),
            'lapangan_aktif'       => Lapangan::where('status', 'aktif')->count(),
            'total_reservasi'      => Reservasi::count(),
            'reservasi_pending'    => Reservasi::where('status', 'pending')->count(),
            'reservasi_approved'   => Reservasi::where('status', 'approved')->count(),
            'reservasi_rejected'   => Reservasi::where('status', 'rejected')->count(),
            'pendapatan_bulan_ini' => (float) Reservasi::where('status', 'approved')
                ->whereMonth('tanggal', now()->month)
                ->whereYear('tanggal', now()->year)
                ->sum('total_harga'),
            'reservasi_terbaru'    => Reservasi::with('lapangan:id,nama,jenis')
                ->latest()->take(5)
                ->get()->map(fn($r) => $this->formatReservasi($r)),
        ];

        return $this->success($data, 'Statistik dashboard');
    }

    // ─── FORMAT HELPERS ──────────────────────────────────────────────────────

    private function formatLapangan(Lapangan $l): array
    {
        return [
            'id'              => $l->id,
            'nama'            => $l->nama,
            'jenis'           => $l->jenis,
            'deskripsi'       => $l->deskripsi,
            'harga_per_jam'   => (float) $l->harga_per_jam,
            'harga_format'    => $l->harga_format,
            'foto_url'        => $l->foto ? asset('uploads/lapangan/'.$l->foto) : null,
            'status'          => $l->status,
            'jam_buka'        => substr($l->jam_buka, 0, 5),
            'jam_tutup'       => substr($l->jam_tutup, 0, 5),
            'total_reservasi' => $l->reservasi_count ?? 0,
            'created_at'      => $l->created_at,
            'updated_at'      => $l->updated_at,
        ];
    }

    private function formatReservasi(Reservasi $r): array
    {
        return [
            'id'            => $r->id,
            'nama_pemesan'  => $r->nama_pemesan,
            'no_hp'         => $r->no_hp,
            'email'         => $r->email,
            'tanggal'       => $r->tanggal?->format('Y-m-d'),
            'jam_mulai'     => substr($r->jam_mulai, 0, 5),
            'jam_selesai'   => substr($r->jam_selesai, 0, 5),
            'durasi_jam'    => $r->durasi_jam,
            'total_harga'   => (float) $r->total_harga,
            'total_format'  => $r->total_format,
            'status'        => $r->status,
            'catatan'       => $r->catatan,
            'alasan_tolak'  => $r->alasan_tolak,
            'lapangan'      => $r->lapangan ? [
                'id'    => $r->lapangan->id,
                'nama'  => $r->lapangan->nama,
                'jenis' => $r->lapangan->jenis,
            ] : null,
            'created_at'    => $r->created_at,
            'updated_at'    => $r->updated_at,
        ];
    }
}
