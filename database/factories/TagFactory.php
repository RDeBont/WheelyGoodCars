<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Car;
use App\Models\Tag;
use App\Models\User;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tag>
 */
class TagFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    private static int $index = 0;

    public function definition(): array
    {

        $tags = [
            ['name' => 'Sport', 'color' => '#7F1D1D'],
            ['name' => 'Elektrisch', 'color' => '#14532D'],
            ['name' => 'Hybride', 'color' => '#1E3A8A'],
            ['name' => 'SUV', 'color' => '#78350F'],
            ['name' => 'Sedan', 'color' => '#581C87'],
            ['name' => 'Hatchback', 'color' => '#164E63'],
            ['name' => 'Luxe', 'color' => '#3F3F46'],
            ['name' => 'Compact', 'color' => '#052E16'],
            ['name' => 'Familie', 'color' => '#1E293B'],
            ['name' => 'Offroad', 'color' => '#422006'],
            ['name' => '4x4', 'color' => '#4C1D95'],
            ['name' => 'Diesel', 'color' => '#0F172A'],
            ['name' => 'Benzine', 'color' => '#374151'],
            ['name' => 'Automaat', 'color' => '#7C2D12'],
            ['name' => 'Handgeschakeld', 'color' => '#3B0764'],
            ['name' => 'Nieuw', 'color' => '#064E3B'],
            ['name' => 'Occasion', 'color' => '#7F1D1D'],
            ['name' => 'Dealeronderhouden', 'color' => '#083344'],
            ['name' => 'Zuinig', 'color' => '#365314'],
            ['name' => 'Snelle levering', 'color' => '#831843'],
        ];

        $tag = $tags[self::$index];
        self::$index++;

        return [
            'name' => $tag['name'],
            'color' => $tag['color'],
        ];
    }
}
