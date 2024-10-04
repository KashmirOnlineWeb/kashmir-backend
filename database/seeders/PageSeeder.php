<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Models\Page;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = DB::raw('CURRENT_TIMESTAMP');

        $data = [
                    [
                        'name'       => 'Home',
                        'slug'       => 'home',
                        'status'     => 1,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ],
                    [
                        'name'       => 'Testimonials',
                        'slug'       => 'testimonials',
                        'status'     => 1,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ],
                    [
                        'name'       => 'FAQs',
                        'slug'       => 'faqs',
                        'status'     => 1,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]
                ];

        DB::table('pages')->insert($data);
    }
}
