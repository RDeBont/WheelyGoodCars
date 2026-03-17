<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Tag;
use App\Models\Car;
use App\Models\CarTag;
use App\Models\Offer;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'is_admin' => true,
            'password' => 'testpassword',
        ]);


        Tag::factory(20)->create();


        User::factory(150)->create();

        Car::factory(250)->create();

        $cars = Car::all();
        $tagIds = Tag::pluck('id')->toArray();

        foreach ($cars as $car) {
            $count = rand(1, 4);
            $randomTags = array_rand(array_flip($tagIds), $count);
            $car->tags()->attach((array) $randomTags);
        }

    }
}
