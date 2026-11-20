<?php

namespace Database\Seeders;

use App\Models\Media;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat User Contoh
        $user1 = User::create([
            'name' => 'Naufal',
            'email' => 'naufal@example.com',
            'password' => Hash::make('password123'),
        ]);

        $user2 = User::create([
            'name' => 'OtakuUser',
            'email' => 'otaku@example.com',
            'password' => Hash::make('password123'),
        ]);

        // 2. Data Media (dari Modul 3)
        $m1 = Media::create([
            'title' => 'Frieren: Beyond Journey\'s End',
            'type' => 'Anime',
            'genre' => 'Adventure, Fantasy',
            'status' => 'Finished',
            'episodes' => 28,
            'score' => 9.3,
        ]);

        $m2 = Media::create([
            'title' => 'Attack on Titan',
            'type' => 'Anime',
            'genre' => 'Action, Drama',
            'status' => 'Finished',
            'episodes' => 89,
            'score' => 9.0,
        ]);

        $m3 = Media::create([
            'title' => 'Jujutsu Kaisen',
            'type' => 'Anime',
            'genre' => 'Action, Supernatural',
            'status' => 'Ongoing',
            'episodes' => 47,
            'score' => 8.6,
        ]);

        $m4 = Media::create([
            'title' => 'Steins;Gate',
            'type' => 'Anime',
            'genre' => 'Sci-Fi, Thriller',
            'status' => 'Finished',
            'episodes' => 24,
            'score' => 9.1,
        ]);

        // 3. Tambahkan ke koleksi user contoh
        $user1->media()->attach([$m1->id, $m2->id]);
    }
}