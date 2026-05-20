<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lapangan extends Model
{
    protected $table = 'lapangan';

    protected $fillable = [
        'nama', 'jenis', 'deskripsi', 'harga_per_jam',
        'foto', 'status', 'jam_buka', 'jam_tutup'
    ];

    public function reservasi(): HasMany
    {
        return $this->hasMany(Reservasi::class);
    }

    public function getFotoUrlAttribute(): string
    {
        return $this->foto
            ? asset('uploads/lapangan/' . $this->foto)
            : asset('images/no-image.jpg');
    }

    public function getHargaFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->harga_per_jam, 0, ',', '.');
    }
}
