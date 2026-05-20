<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Lapangan;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::create([
            'name'     => 'Admin Lapangan',
            'email'    => 'admin@lapangan.com',
            'password' => Hash::make('admin123'),
            'role'     => 'superadmin',
        ]);

        // Data lapangan contoh
        $lapangan = [
            [
                'nama'         => 'Lapangan Futsal A',
                'jenis'        => 'Futsal',
                'deskripsi'    => 'Lapangan futsal indoor dengan lantai vynil berkualitas tinggi. Dilengkapi pencahayaan LED dan tribun penonton.',
                'harga_per_jam'=> 100000,
                'status'       => 'aktif',
                'jam_buka'     => '07:00',
                'jam_tutup'    => '22:00',
            ],
            [
                'nama'         => 'Lapangan Futsal B',
                'jenis'        => 'Futsal',
                'deskripsi'    => 'Lapangan futsal outdoor dengan rumput sintetis premium. Cocok untuk latihan dan pertandingan.',
                'harga_per_jam'=> 80000,
                'status'       => 'aktif',
                'jam_buka'     => '07:00',
                'jam_tutup'    => '21:00',
            ],
            [
                'nama'         => 'Lapangan Badminton 1',
                'jenis'        => 'Badminton',
                'deskripsi'    => 'Lapangan badminton indoor standar BWF. Lantai kayu dengan pencahayaan optimal.',
                'harga_per_jam'=> 50000,
                'status'       => 'aktif',
                'jam_buka'     => '06:00',
                'jam_tutup'    => '22:00',
            ],
            [
                'nama'         => 'Lapangan Badminton 2',
                'jenis'        => 'Badminton',
                'deskripsi'    => 'Lapangan badminton dengan AC sentral. Nyaman untuk bermain di siang hari.',
                'harga_per_jam'=> 60000,
                'status'       => 'aktif',
                'jam_buka'     => '06:00',
                'jam_tutup'    => '22:00',
            ],
            [
                'nama'         => 'Lapangan Basket',
                'jenis'        => 'Basket',
                'deskripsi'    => 'Lapangan basket full-size dengan ring standar NBA. Outdoor dengan permukaan aspal halus.',
                'harga_per_jam'=> 120000,
                'status'       => 'aktif',
                'jam_buka'     => '07:00',
                'jam_tutup'    => '21:00',
            ],
            [
                'nama'         => 'Lapangan Voli',
                'jenis'        => 'Voli',
                'deskripsi'    => 'Lapangan voli pasir outdoor. Cocok untuk beach volleyball.',
                'harga_per_jam'=> 70000,
                'status'       => 'aktif',
                'jam_buka'     => '07:00',
                'jam_tutup'    => '20:00',
            ],
        ];

        foreach ($lapangan as $data) {
            Lapangan::create($data);
        }
    }
}
