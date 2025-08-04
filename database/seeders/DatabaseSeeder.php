<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Book;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Super Admin
        User::create([
            'name' => 'Super Administrator',
            'email' => 'admin@prigi.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'phone' => '081234567890',
            'address' => 'Desa Prigi, Watulimo, Trenggalek',
            'is_active' => true
        ]);

        // Petugas
        User::create([
            'name' => 'Petugas Perpustakaan',
            'email' => 'petugas@prigi.com',
            'password' => Hash::make('petugas123'),
            'role' => 'petugas',
            'phone' => '082345678901',
            'address' => 'Desa Prigi, Watulimo, Trenggalek',
            'is_active' => true
        ]);

        // Member
        User::create([
            'name' => 'Warga Desa Prigi',
            'email' => 'member@prigi.com',
            'password' => Hash::make('member123'),
            'role' => 'member',
            'phone' => '083456789012',
            'address' => 'Desa Prigi, Watulimo, Trenggalek',
            'is_active' => true
        ]);

        // Create Categories
        $categories = [
            ['name' => 'Fiksi', 'description' => 'Novel, cerita pendek, dan karya fiksi lainnya', 'color' => '#3B82F6'],
            ['name' => 'Non-Fiksi', 'description' => 'Buku pengetahuan, biografi, dan fakta', 'color' => '#10B981'],
            ['name' => 'Sejarah', 'description' => 'Buku-buku tentang sejarah dan peradaban', 'color' => '#F59E0B'],
            ['name' => 'Sains', 'description' => 'Buku sains dan teknologi', 'color' => '#8B5CF6'],
            ['name' => 'Agama', 'description' => 'Buku-buku keagamaan dan spiritual', 'color' => '#059669'],
            ['name' => 'Pendidikan', 'description' => 'Buku pelajaran dan pendidikan', 'color' => '#DC2626'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Create Sample Books
        $books = [
            [
                'title' => 'Laskar Pelangi',
                'isbn' => '9786020331775',
                'author' => 'Andrea Hirata',
                'publisher' => 'Bentang Pustaka',
                'publication_year' => 2005,
                'pages' => 529,
                'description' => 'Novel tentang perjuangan anak-anak Belitung untuk mendapatkan pendidikan.',
                'stock' => 5,
                'available_stock' => 5,
                'location' => 'Rak A-1',
                'category_id' => 1,
                'is_featured' => true,
            ],
            [
                'title' => 'Bumi Manusia',
                'isbn' => '9786020382173',
                'author' => 'Pramoedya Ananta Toer',
                'publisher' => 'Lentera Dipantara',
                'publication_year' => 1980,
                'pages' => 535,
                'description' => 'Novel sejarah yang mengisahkan kehidupan di masa kolonial Belanda.',
                'stock' => 3,
                'available_stock' => 3,
                'location' => 'Rak A-2',
                'category_id' => 1,
                'is_featured' => true,
            ],
            [
                'title' => 'Sejarah Indonesia Modern',
                'isbn' => '9786232163027',
                'author' => 'Ricklefs M.C.',
                'publisher' => 'Gadjah Mada University Press',
                'publication_year' => 2008,
                'pages' => 783,
                'description' => 'Sejarah lengkap Indonesia dari masa kolonial hingga era reformasi.',
                'stock' => 4,
                'available_stock' => 4,
                'location' => 'Rak B-1',
                'category_id' => 3,
                'is_featured' => false,
            ],
            [
                'title' => 'Fisika Dasar',
                'isbn' => '9786024246328',
                'author' => 'Halliday & Resnick',
                'publisher' => 'Erlangga',
                'publication_year' => 2019,
                'pages' => 1200,
                'description' => 'Buku teks fisika dasar untuk mahasiswa dan pelajar.',
                'stock' => 6,
                'available_stock' => 6,
                'location' => 'Rak C-1',
                'category_id' => 4,
                'is_featured' => true,
            ],
            [
                'title' => 'Tafsir Al-Quran Tematik',
                'isbn' => '9786025734021',
                'author' => 'Tim Lajnah Pentashihan',
                'publisher' => 'Lajnah Pentashihan Mushaf Al-Quran',
                'publication_year' => 2020,
                'pages' => 450,
                'description' => 'Tafsir Al-Quran berdasarkan tema-tema tertentu.',
                'stock' => 8,
                'available_stock' => 8,
                'location' => 'Rak D-1',
                'category_id' => 5,
                'is_featured' => false,
            ],
            [
                'title' => 'Psikologi Pendidikan',
                'isbn' => '9786024733947',
                'author' => 'Santrock, John W.',
                'publisher' => 'Salemba Humanika',
                'publication_year' => 2018,
                'pages' => 628,
                'description' => 'Konsep dan aplikasi psikologi dalam dunia pendidikan.',
                'stock' => 4,
                'available_stock' => 4,
                'location' => 'Rak E-1',
                'category_id' => 6,
                'is_featured' => true,
            ],
        ];

        foreach ($books as $book) {
            Book::create($book);
        }
    }
}
