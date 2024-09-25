<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\LlibreDeText;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LlibreDeText>
 */
class LlibreDeTextFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = LlibreDeText::class;
    
    public function definition()
    {
        return [
            'titol' => $this->faker->sentence,
            'curs' => $this->faker->randomElement(['1r ESO', '2n ESO', '3r ESO', '4t ESO']),
            'editorial' => $this->faker->company,
            'observacions' => $this->faker->paragraph,
            'user_id' => User::factory(),
            'categoria' => $this->faker->randomElement(['Llibres de text', 'Llibres de lectura', 'Roba']),
        ];
    }
}
