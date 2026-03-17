<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Car;
use App\Models\Tag;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Car>
 */
class CarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $makes = [
            'Toyota' => ['Corolla', 'Yaris', 'RAV4', 'Camry'],
            'Volkswagen' => ['Golf', 'Polo', 'Tiguan', 'Passat'],
            'BMW' => ['3 Serie', '5 Serie', 'X5', 'X3'],
            'Mercedes' => ['A-Klasse', 'C-Klasse', 'E-Klasse', 'GLC'],
            'Ford' => ['Focus', 'Fiesta', 'Kuga', 'Mustang'],
            'Audi' => ['A3', 'A4', 'Q5', 'Q3'],
            'Peugeot' => ['208', '308', '3008', '5008'],
            'Renault' => ['Clio', 'Megane', 'Kadjar', 'Captur'],
            'Hyundai' => ['i20', 'i30', 'Tucson', 'Kona'],
            'Kia' => ['Picanto', 'Ceed', 'Sportage', 'Sorento'],
        ];

        $make = $this->faker->randomElement(array_keys($makes));
        $model = $this->faker->randomElement($makes[$make]);

        return [
            'user_id'=> User::inRandomOrder()->first()?->id ?? User::factory(),
            'license_plate' => strtoupper($this->faker->bothify('??-###-?')),
            'make' => $make,
            'model' => $model,
            'price' => $this->faker->numberBetween(5000, 75000),
            'mileage' => $this->faker->numberBetween(0, 300000),
            'seats' => $this->faker->randomElement([2, 4, 5, 7]),
            'doors' => $this->faker->randomElement([2, 3, 4, 5]),
            'production_year' => $this->faker->numberBetween(2000, 2024),
            'weight' => $this->faker->numberBetween(900, 3500),
            'color' => $this->faker->randomElement(['Zwart', 'Wit', 'Grijs', 'Blauw', 'Rood', 'Zilver', 'Groen']),
            'image' => null,
            'sold_at' => $this->faker->optional(0.3)->dateTimeBetween('-2 years', 'now'),
            'views' => $this->faker->numberBetween(0, 500),
        ];
    }
}
