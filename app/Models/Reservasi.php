<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservasi extends Model
{
    protected $table = 'reservasi';

    protected $fillable = [
        'lapangan_id', 'nama_pemesan', 'no_hp', 'email',
        'tanggal', 'jam_mulai', 'jam_selesai', 'durasi_jam',
        'total_harga', 'status', 'catatan', 'alasan_tolak'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'total_harga' => 'decimal:2',
    ];

    public function lapangan(): BelongsTo
    {
        return $this->belongsTo(Lapangan::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending'  => '<span class="badge-pending">Pending</span>',
            'approved' => '<span class="badge-approved">Approved</span>',
            'rejected' => '<span class="badge-rejected">Rejected</span>',
            default    => '-',
        };
    }

    public function getTotalFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->total_harga, 0, ',', '.');
    }
}
